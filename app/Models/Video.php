<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Video
 * @package App\Models
 *
 * @property integer $id
 * @property string $title
 * @property string $src
 * @property integer $size
 * @property integer $user_id
 * @property Product $product
 * @property User $user
 *
 */
class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'src',
        'size',
        'user_id',
    ];

    public static array $columns = [
        'id',
        'title',
        'src',
        'size',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
