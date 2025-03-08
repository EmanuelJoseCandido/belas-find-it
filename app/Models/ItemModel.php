<?php

namespace App\Models;

use App\Enums\Items\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'location',
        'status',
        'image',
        'user_id',
        'found_date'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusEnum::class,
        ];
    }

    public function category()
    {
        return $this->belongsTo(CategoryModel::class);
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    public function claims()
    {
        return $this->hasMany(ClaimModel::class);
    }
}
