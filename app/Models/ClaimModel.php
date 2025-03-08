<?php

namespace App\Models;

use App\Enums\Claims\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'user_id',
        'status',
        'message'
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

    public function item()
    {
        return $this->belongsTo(ItemModel::class);
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}
