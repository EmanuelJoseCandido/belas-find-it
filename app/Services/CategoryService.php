<?php

namespace App\Services;

use App\Exceptions\Auth\UnauthorizedException;
use App\Models\CategoryModel;
use App\Traits\Essentials\VerifyTypeUserTrait;

class CategoryService
{
    use VerifyTypeUserTrait;

    protected $relations = ['items'];

    /**
     * Get all categories from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
       /*  if (!$this->verifyAdmin())
            throw new UnauthorizedException(); */
        return CategoryModel::withTrashed()->with($this->relations)->get();
    }

    /**
     * Create a new category in the database
     *
     * @param  array $attributes
     * @return CategoryModel
     */
    public function create(array $attributes)
    {
        $category = CategoryModel::create($attributes);
        return $category->load($this->relations);
    }

    /**
     * Get a category from the database
     *
     * @param  int $id
     * @return CategoryModel
     */
    public function get(int $id)
    {
     /*    if (!$this->verifyAdmin())
            throw new UnauthorizedException(); */
        return CategoryModel::withTrashed()->with($this->relations)->findOrFail($id);
    }

    /**
     * Update a specific category in the database
     *
     * @param  array $attributes
     * @param  int $id
     * @return CategoryModel
     */
    public function update(array $attributes, int $id)
    {
        $category = $this->get($id);
        $category->fill($attributes);
        $category->save();
        return $category->fresh($this->relations);
    }

    /**
     * Trash a specified category in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        $category = $this->get($id);
        return $category->delete();
    }

    /**
     * Permanently delete a specific category in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function forceDelete(int $id)
    {
        $category = $this->get($id);
        return $category->forceDelete();
    }

    /**
     * Restore a specific category in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function restore(int $id)
    {
        $category = $this->get($id);
        return $category->restore();
    }
}
