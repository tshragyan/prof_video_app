<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Plan
 * @package App\Models
 *
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $max_video_count
 * @property integer $max_video_size
 * @property double $price
 * @property double $old_price
 * @property double $annual_price
 * @property double $old_annual_price
 */
class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'max_video_count',
        'max_video_size',
        'price',
        'old_price',
        'annual_price',
        'old_annual_price',
    ];

    public static array $columns = [
        'id',
        'name',
        'description',
        'max_video_count',
        'max_video_size',
        'price',
        'old_price',
        'annual_price',
        'old_annual_price',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
