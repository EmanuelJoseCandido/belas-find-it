<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\UpdateContactStatus;
use App\Http\Resources\ContactResource;
use App\Services\ContactService;

class ContactController extends Controller
{
    /**
     * Create a new instance of ContactService.
     *
     * @param  ContactService $contactService
     * @return void
     */
    public function __construct(protected ContactService $contactService)
    {
    }

    /**
     * Display a list of all contacts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ContactResource::collection($this->contactService->getAll());
    }

    /**
     * Store a newly created contact in the database.
     *
     * @param  ContactRequest $contactRequest
     * @return ContactResource
     */
    public function store(ContactRequest $contactRequest)
    {
        $contact = $contactRequest->validated();
        $contact = $this->contactService->create($contact);
        return new ContactResource($contact);
    }

    /**
     * Display the specified contact.
     *
     * @param  int $id
     * @return ContactResource|null
     */
    public function show(int $id)
    {
        $contact = $this->contactService->get($id);
        return is_null($contact) ? null : new ContactResource($contact);
    }

    /**
     * Update the specified contact in the database.
     *
     * @param  ContactRequest $contactRequest
     * @param  int $id
     * @return ContactResource
     */
    public function update(ContactRequest $contactRequest, int $id)
    {
        $contact = $contactRequest->validated();
        $contact = $this->contactService->update($contact, $id);
        return new ContactResource($contact);
    }

    /**
     * Remove the specified contact from the database.
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->contactService->delete($id);
    }

    /**
     * Force delete the specified contact from the database.
     *
     * @param  int $id
     * @return bool
     */
    public function forceDelete($id)
    {
        return $this->contactService->forceDelete($id);
    }

    /**
     * Restore the specified contact in the database.
     *
     * @param  int $id
     * @return bool
     */
    public function restore($id)
    {
        return $this->contactService->restore($id);
    }

    /**
     * Update status of a specific contact
     *
     * @param int $id
     * @param UpdateContactStatus $updateContactStatus
     * @return ContactResource
     */
    public function updateStatus(int $id, UpdateContactStatus $updateContactStatus)
    {
        $contactStatus = $updateContactStatus->validated();

        $contact = $this->contactService->updateStatus($id, $contactStatus['status']);
        return new ContactResource($contact);
    }
}
