<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimRequest;
use App\Http\Resources\ClaimResource;
use App\Services\ClaimService;

class ClaimController extends Controller
{
    /**
     * Create a new instance of claimService.
     *
     * @param  ClaimService $claimService
     * @return void
     */
    public function __construct(protected ClaimService $claimService)
    {
    }

    /**
     * Display a list of all occurrence record resources.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ClaimResource::collection($this->claimService->getAll());
    }

    /**
     * Store a newly created occurrence record resource in the database.
     *
     * @param  ClaimRequest $claimRequest
     * @return ClaimResource
     */
    public function store(ClaimRequest $claimRequest): ClaimResource
    {
        $claim = $claimRequest->validated();
        $claim = $this->claimService->create($claim);
        return new ClaimResource($claim);
    }

    /**
     * Show a specific occurrence record resource.
     *
     * @param  int $id
     * @return ClaimResource|null
     */
    public function show(int $identify): ClaimResource|null
    {
        $claim = $this->claimService->get($identify);
        return is_null($claim) ? null : new ClaimResource($claim);
    }

    /**
     * Update a newly created occurrence record resource in the database.
     *
     * @param  ClaimRequest $claimRequest
     * @param  int $id
     * @return ClaimResource
     */
    public function update(ClaimRequest $claimRequest, int $id): ClaimResource
    {
        $claim = $claimRequest->validated();
        $claim = $this->claimService->update($claim, $id);
        return new ClaimResource($claim);
    }

    /**
     * Remove a newly created occurrence record resource from the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->claimService->delete($id);
    }

    /**
     * Force the deletion of a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function forceDelete($id)
    {
        return $this->claimService->forceDelete($id);
    }

    /**
     * Restore a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function restore($id)
    {
        return $this->claimService->restore($id);
    }
}
