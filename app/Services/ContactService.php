<?php

namespace App\Services;

use App\Enums\Contacts\StatusEnum;
use App\Exceptions\Auth\UnauthorizedException;
use App\Models\ContactModel;
use App\Services\Custom\External\WeSenderService;
use App\Traits\Essentials\VerifyTypeUserTrait;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    use VerifyTypeUserTrait;

    protected $relations = ['item', 'item.user'];

    /**
     * Create a new instance of itemService.
     *
     * @param  WeSenderService $weSenderService
     * @return void
     */
    public function __construct(protected WeSenderService $weSenderService)
    {
    }

    /**
     * Get all contacts from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        if (!$this->verifyAdmin())
            throw new UnauthorizedException();

        return ContactModel::withTrashed()->with($this->relations)->get();
    }

    /**
     * Create a new contact in the database
     *
     * @param  array $attributes
     * @return ContactModel
     */
    public function create(array $attributes)
    {

        $contact = ContactModel::create($attributes);
        $contact->load($this->relations);

        if (!empty($attributes['item_id']) && $contact->item) {

            $user = $contact->item->user;

            if ($user && $user->phone) {
                $itemStatus = $contact->item->status;
                $itemTitle = $contact->item->title;
                $contactName = $attributes['name'];
                $contactPhone = $attributes['phone'];

                if ($itemStatus === 'perdido') {
                    $message = "Olá! Temos boas notícias " . $contactName . " informou que encontrou seu item " . $itemTitle . " que você reportou como perdido. Entre em contacto através do telefone " . $contactPhone . " para mais detalhes e combinar a devolução.";
                } else {

                    $message = "Olá! " . $contactName . " entrou em contacto sobre o item " . $itemTitle . " que você encontrou. Esta pessoa afirma ser o proprietário. Entre em contacto através do telefone " . $contactPhone . " para verificar os detalhes e combinar a devolução.";
                }

                $this->weSenderService->sendMessage($user->phone, $message);
            }
        }

        return $contact;
    }

    /**
     * Get a contact from the database
     *
     * @param  int $id
     * @return ContactModel
     */
    public function get(int $id)
    {
        if (!$this->verifyAdmin())
            throw new UnauthorizedException();

        return ContactModel::withTrashed()->with($this->relations)->findOrFail($id);
    }

    /**
     * Update a specific contact in the database
     *
     * @param  array $attributes
     * @param  int $id
     * @return ContactModel
     */
    public function update(array $attributes, int $id)
    {
        $contact = $this->get($id);
        $contact->fill($attributes);
        $contact->save();
        return $contact->fresh('item');
    }

    /**
     * Trash a specified contact in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        $contact = $this->get($id);
        return $contact->delete();
    }

    /**
     * Permanently delete a specific contact in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function forceDelete(int $id)
    {
        $contact = $this->get($id);
        return $contact->forceDelete();
    }

    /**
     * Restore a specific contact in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function restore(int $id)
    {
        $contact = $this->get($id);
        return $contact->restore();
    }

    /**
     * Update status of a contact
     *
     * @param int $id
     * @param StatusEnum $status
     * @return ContactModel
     */
    public function updateStatus(int $id, StatusEnum $status)
    {
        $contact = $this->get($id);
        $contact->status = $status;
        $contact->save();
        return $contact->fresh('item');
    }

    /**
     * Send an email notification about the new contact
     *
     * This is a placeholder - implement according to your needs
     *
     * @param ContactModel $contact
     * @param string $message
     * @return void
     */
    private function sendContactNotification(ContactModel $contact, string $message)
    {
        // Implementação de envio de email (se necessário)
        // if ($contact->item && $contact->item->user && $contact->item->user->email) {
        //     Mail::to($contact->item->user->email)->send(new ContactNotification($contact, $message));
        // }
    }
}
