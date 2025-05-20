<?php

namespace App\Services;

use App\Enums\Essentials\FilePathsEnum;
use App\Enums\Items\StatusEnum;
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
     * Retorna a quantidade de itens para cada status e o total
     *
     * @param bool $includeTrashed Indica se deve incluir itens excluídos (soft-deleted)
     * @return array
     */
    public function getCountsByStatus(bool $includeTrashed = false)
    {
        $counts = [];

        foreach (StatusEnum::cases() as $status) {
            $query = ItemModel::query();

            if ($includeTrashed) {
                $query->withTrashed();
            }

            $counts[$status->value] = $query->where('status', $status)->count();
        }

        // Contagem total
        $query = ItemModel::query();
        if ($includeTrashed) {
            $query->withTrashed();
        }
        $counts['total'] = $query->count();

        return $counts;
    }

    /**
     * Get all categories from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    /**
     * Get all items from the database with filters
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(array $filters = [])
    {
        /* if (!$this->verifyAdmin())
            throw new UnauthorizedException(); */

        $query = ItemModel::query()->with($this->relations);

        // Apply status filter
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply search filter for title and description
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Apply category filter
        if (isset($filters['category_id']) && !empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Apply location filter
        if (isset($filters['location']) && !empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        // Apply date filter
        if (isset($filters['date']) && !empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        // You might want to add pagination here too
        return $query->get();
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

        if ($item->image) {
            $this->destroyFile($item->image);
        }

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
