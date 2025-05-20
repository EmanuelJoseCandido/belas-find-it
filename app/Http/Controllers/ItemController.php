<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemFilterRequest;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    /**
     * Create a new instance of itemService.
     *
     * @param  ItemService $itemService
     * @return void
     */
    public function __construct(protected ItemService $itemService)
    {
    }

    /**
     * Obter a contagem de itens por status
     *
     * @return JsonResponse
     */
    public function getCountsByStatus(): JsonResponse
    {
        $counts = $this->itemService->getCountsByStatus();

        return response()->json([
            'success' => true,
            'data' => $counts
        ]);
    }

    /**
     * Display a list of all occurrence record resources.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ItemFilterRequest $itemFilterRequest): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $item = $itemFilterRequest->validated();
        return ItemResource::collection($this->itemService->getAll($item));
    }

    /**
     * Store a newly created occurrence record resource in the database.
     *
     * @param  ItemRequest $itemRequest
     * @return ItemResource
     */
    public function store(ItemRequest $itemRequest): ItemResource
    {
        $item = $itemRequest->validated();
        $item = $this->itemService->create($item);
        return new ItemResource($item);
    }

    /**
     * Show a specific occurrence record resource.
     *
     * @param  int $id
     * @return ItemResource|null
     */
    public function show(int $identify): ItemResource|null
    {
        $item = $this->itemService->get($identify);
        return is_null($item) ? null : new ItemResource($item);
    }

    /**
     * Update a newly created occurrence record resource in the database.
     *
     * @param  ItemRequest $itemRequest
     * @param  int $id
     * @return ItemResource
     */
    public function update(ItemRequest $itemRequest, int $id): ItemResource
    {
        $item = $itemRequest->validated();
        $item = $this->itemService->update($item, $id);
        return new ItemResource($item);
    }

    /**
     * Remove a newly created occurrence record resource from the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->itemService->delete($id);
    }

    /**
     * Force the deletion of a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function forceDelete($id)
    {
        return $this->itemService->forceDelete($id);
    }

    /**
     * Restore a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function restore($id)
    {
        return $this->itemService->restore($id);
    }
}
