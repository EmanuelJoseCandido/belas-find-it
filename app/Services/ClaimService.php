<?php

namespace App\Services;

use App\Exceptions\Auth\UnauthorizedException;
use App\Models\ClaimModel;
use App\Traits\Essentials\VerifyTypeUserTrait;

class ClaimService
{
    use VerifyTypeUserTrait;

    protected $relations = ['item', 'user'];

    /**
     * Get all categories from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        if (!$this->verifyAdmin())
            throw new UnauthorizedException();
        return ClaimModel::withTrashed()->with($this->relations)->get();
    }

    /**
     * Create a new claim in the database
     *
     * @param  array $attributes
     * @return ClaimModel
     */
    public function create(array $attributes)
    {
        $claim = ClaimModel::create($attributes);
        return $claim->load($this->relations);
    }

    /**
     * Get a claim from the database
     *
     * @param  int $id
     * @return ClaimModel
     */
    public function get(int $id)
    {
        if (!$this->verifyAdmin())
            throw new UnauthorizedException();
        return ClaimModel::withTrashed()->with($this->relations)->findOrFail($id);
    }

    /**
     * Update a specific claim in the database
     *
     * @param  array $attributes
     * @param  int $id
     * @return ClaimModel
     */
    public function update(array $attributes, int $id)
    {
        $claim = $this->get($id);
        $claim->fill($attributes);
        $claim->save();
        return $claim->fresh($this->relations);
    }

    /**
     * Trash a specified claim in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        $claim = $this->get($id);
        return $claim->delete();
    }

    /**
     * Permanently delete a specific claim in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function forceDelete(int $id)
    {
        $claim = $this->get($id);
        return $claim->forceDelete();
    }

    /**
     * Restore a specific claim in the database
     *
     * @param  int $id
     * @return boolean
     */
    public function restore(int $id)
    {
        $claim = $this->get($id);
        return $claim->restore();
    }
}
