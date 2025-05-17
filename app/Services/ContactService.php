<?php

namespace App\Services;

use App\Enums\Contacts\StatusEnum;
use App\Exceptions\Auth\UnauthorizedException;
use App\Models\ContactModel;
use App\Traits\Essentials\VerifyTypeUserTrait;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    use VerifyTypeUserTrait;

    protected $relations = ['item'];

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

        // Here you could add logic to send emails, notifications, etc.
        // For example:
        // $this->sendContactNotification($contact);

        return $contact->load('item');
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
     * @return void
     */
    private function sendContactNotification(ContactModel $contact)
    {
        // Example notification logic
        // Mail::to(config('mail.admin_email'))->send(new ContactNotification($contact));
    }
}
