<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\UnauthorizedException;
use App\Models\UserModel;
use App\Traits\Essentials\VerifyTypeUserTrait;
use App\Traits\Custom\StringTrait;
use Exception;

class UserService
{
    use VerifyTypeUserTrait, StringTrait;

    protected $relations = [];


    /**
     * Get all user records from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
       /*  if (!$this->verifyAdmin())
            throw new UnauthorizedException(); */
        return UserModel::withTrashed()->with($this->relations)->get();
    }

    /**
     * Create a new user record in the database
     *
     * @param  array $attributes
     * @return UserModel
     */
    public function create(array $attributes): UserModel
    {
        $attributes['slug'] = $this->createSlug($attributes['name']);
        $attributes['password'] = bcrypt(config('default.password'));
        $user = UserModel::create($attributes);
        return $user->load($this->relations);
    }

    /**
     * Get a user record from the database by id
     *
     * @param  int $id
     * @return UserModel
     * @throws UnauthorizedException
     */
    public function get(int $id): UserModel
    {
        if (!$this->verifyAdmin()) {
            throw new UnauthorizedException();
        }

        $user = UserModel::withTrashed()->with($this->relations)->findOrFail($id);

        if (!$user instanceof UserModel) {
            throw new Exception("Unexpected type returned from findOrFail");
        }

        return $user;
    }


    /**
     * Update a specific user record in the database
     *
     * @param  array $attributes
     * @param  int $id
     * @return UserModel
     */
    public function update(array $attributes, int $id): UserModel
    {
        $user = $this->get($id);
        $attributes['slug'] = $this->createSlug($attributes['name']);
        $user->fill($attributes);
        $user->save();

        return $user->fresh($this->relations);
    }

    /**
     * Trash a specified user record in the database
     *
     * @param  int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = $this->get($id);
        return $user->delete();
    }

    /**
     * Permanently delete a specific user record in the database
     *
     * @param  int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $user = $this->get($id);
        return $user->forceDelete();
    }

    /**
     * Restore a specific user record in the database
     *
     * @param  int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $user = $this->get($id);
        return $user->restore();
    }
}
