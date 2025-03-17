<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Users\GenderEnum;
use App\Enums\Users\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are table name
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'role',
        'slug',
        'password',
        'is_blocked',
        'is_login',
        'is_tour',
        'is_changed_password_once',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gender' => GenderEnum::class,
            'role' => RoleEnum::class,
            'is_blocked' => 'boolean',
            'is_login' => 'boolean',
            'is_tour' => 'boolean',
            'is_changed_password_once' => 'boolean',
        ];
    }


    public function items()
    {
        return $this->hasMany(ItemModel::class);
    }

    public function claims()
    {
        return $this->hasMany(ClaimModel::class);
    }
}
