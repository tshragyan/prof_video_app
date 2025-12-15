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
 * @property string $from
 * @property integer $external_url
 * @property integer $stored
 * @property integer $saved
 * @property string $path
 * @property User $user
 *
 */
class Video extends Model
{
    use HasFactory;

    const INSTAGRAM_VIDEOS_PREFIX = 'https://www.instagram.com/reel/';

    const INSTAGRAM_KEYWORD = 'Instagram';
    const TIK_TOK_KEYWORD = 'Tiktok';
    const PC_KEYWORD = 'PC';

    const STORED_LOCAL = 'Server';

    protected $fillable = [
        'title',
        'src',
        'size',
        'user_id',
        'from',
        'external_url',
        'stored',
        'saved',
        'path',
    ];

    public static array $columns = [
        'id',
        'title',
        'src',
        'size',
        'user_id',
        'from',
        'external_url',
        'stored',
        'saved',
        'path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
