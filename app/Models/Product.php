<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Product
 * @package App\Models
 *
 * @property integer $id
 * @property integer $title
 * @property integer $user_id
 * @property integer $shopify_data
 * @property User $user
 * @property Video $video
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'shopify_data'
    ];

    public static array $columns = [
        'id',
        'title',
        'user_id',
        'shopify_data'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): HasOne
    {
        return $this->hasOne(Video::class);
    }
}
