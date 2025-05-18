<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\UserService;

class UserController extends Controller
{
    /**
     * Create a new instance of userService.
     *
     * @param  UserService $userService
     * @return void
     */
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * Display a list of all occurrence record resources.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection($this->userService->getAll());
    }

    /**
     * Store a newly created occurrence record resource in the database.
     *
     * @param  UserRequest $claimRequest
     * @return UserResource
     */
    public function store(UserRequest $claimRequest): UserResource
    {
        $claim = $claimRequest->validated();
        $claim = $this->userService->create($claim);
        return new UserResource($claim);
    }

    /**
     * Show a specific occurrence record resource.
     *
     * @param  int $id
     * @return UserResource|null
     */
    public function show(int $identify): UserResource|null
    {
        $claim = $this->userService->get($identify);
        return is_null($claim) ? null : new UserResource($claim);
    }

    /**
     * Update a newly created occurrence record resource in the database.
     *
     * @param  UserRequest $claimRequest
     * @param  int $id
     * @return UserResource
     */
    public function update(UserRequest $claimRequest, int $id): UserResource
    {
        $claim = $claimRequest->validated();
        $claim = $this->userService->update($claim, $id);
        return new UserResource($claim);
    }

    /**
     * Remove a newly created occurrence record resource from the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->userService->delete($id);
    }

    /**
     * Force the deletion of a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function forceDelete($id)
    {
        return $this->userService->forceDelete($id);
    }

    /**
     * Restore a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function restore($id)
    {
        return $this->userService->restore($id);
    }
}
