<?php

namespace App\Services;

use App\Enums\Essentials\FilePathsEnum;
use App\Exceptions\Auth\UnauthorizedException;
use App\Models\ItemModel;
use App\Traits\Custom\StringTrait;
use App\Traits\Essentials\FileTrait;
use App\Traits\Essentials\VerifyTypeUserTrait;

class ItemService
{
    use VerifyTypeUserTrait, FileTrait, StringTrait;

    protected $relations = ['category', 'user', 'claims'];

    /**
     * Get all categories from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        /* if (!$this->verifyAdmin())
            throw new UnauthorizedException(); */
        return ItemModel::withTrashed()->with($this->relations)->get();
    }

    /**
     * Create a new item in the database
     *
     * @param  array $attributes
     * @return ItemModel
     */
    public function create(array $attributes)
    {
        $path = FilePathsEnum::ITEM_SERVICE->value;
        $attributes['image'] = $this->saveFile($attributes['image'], $path);
        $item = ItemModel::create($attributes);
        return $item->load($this->relations);
    }

    /**
     * Get a item from the database
     *
     * @param  int $id
     * @return ItemModel
     */
    public function get(int $id)
    {
       /*  if (!$this->verifyAdmin())
            throw new UnauthorizedException(); */
        return ItemModel::withTrashed()->with($this->relations)->findOrFail($id);
    }

    /**
     * Update a specific item in the database
     *
     * @param  array $attributes
     * @param  int $id
     * @return ItemModel
     */
    public function update(array $attributes, int $id)
    {
        $item = $this->get($id);

        $path = FilePathsEnum::ITEM_SERVICE->value;
        if (!empty($attributes_employee['image'])) {
            $attributes['image'] = $this->updateFile($item->image, $attributes['image'], $path);
        } else {
            $attributes['image'] = $item->image;
        }

        $item->fill($attributes);
        $item->save();
        return $item->fresh($this->relations);
    }

    /**
     * Trash a specified item in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        $item = $this->get($id);
        return $item->delete();
    }

    /**
     * Permanently delete a specific item in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function forceDelete(int $id)
    {
        $item = $this->get($id);
        return $item->forceDelete();
    }

    /**
     * Restore a specific item in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function restore(int $id)
    {
        $item = $this->get($id);
        return $item->restore();
    }
}
