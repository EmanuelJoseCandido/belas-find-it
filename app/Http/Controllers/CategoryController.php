<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{

    /**
     * Create a new instance of CategoryService.
     *
     * @param  CategoryService $categoryService
     * @return void
     */
    public function __construct(protected CategoryService $categoryService)
    {
    }

    /**
     * Display a list of all occurrence record resources.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CategoryResource::collection($this->categoryService->getAll());
    }

    /**
     * Store a newly created occurrence record resource in the database.
     *
     * @param  CategoryRequest $categoryRequest
     * @return CategoryResource
     */
    public function store(CategoryRequest $categoryRequest): CategoryResource
    {
        $category = $categoryRequest->validated();
        $category = $this->categoryService->create($category);
        return new CategoryResource($category);
    }

    /**
     * Show a specific occurrence record resource.
     *
     * @param  int $id
     * @return CategoryResource|null
     */
    public function show(int $identify): CategoryResource|null
    {
        $category = $this->categoryService->get($identify);
        return is_null($category) ? null : new CategoryResource($category);
    }

    /**
     * Update a newly created occurrence record resource in the database.
     *
     * @param  CategoryRequest $categoryRequest
     * @param  int $id
     * @return CategoryResource
     */
    public function update(CategoryRequest $categoryRequest, int $id): CategoryResource
    {
        $category = $categoryRequest->validated();
        $category = $this->categoryService->update($category, $id);
        return new CategoryResource($category);
    }

    /**
     * Remove a newly created occurrence record resource from the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->categoryService->delete($id);
    }

    /**
     * Force the deletion of a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function forceDelete($id)
    {
        return $this->categoryService->forceDelete($id);
    }

    /**
     * Restore a specific occurrence record resource in the database.
     *
     * @param  int  $id
     * @return bool
     */
    public function restore($id)
    {
        return $this->categoryService->restore($id);
    }
}
