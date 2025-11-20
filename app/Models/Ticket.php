<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Ticket
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $message
 * @property int $subject
 * @property int $image
 * @property int $status
 *
 * @property User $user
 * @property TicketMessages $ticketMessages
 */
class Ticket extends Model
{
    use HasFactory;

    const STATUS_NEW = 1;
    const STATUS_READ = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_RESOLVED = 4;

    const STATUS_MAP = [
        self::STATUS_NEW => 'NEW',
        self::STATUS_READ => 'READ',
        self::STATUS_IN_PROGRESS => 'IN PROGRESS',
        self::STATUS_RESOLVED => 'RESOLVED',
    ];

    const STATUS_CACHE_COUNT_MAP = [
      self::STATUS_NEW => 'ticket.new.count',
      self::STATUS_IN_PROGRESS => 'ticket.in_progress.count',
      self::STATUS_READ => 'ticket.read.count',
      self::STATUS_RESOLVED => 'ticket.resolved.count',
    ];

    protected $fillable = ['user_id', 'message', 'subject', 'image', 'status'];

    public static array $columns = ['id','user_id', 'message', 'subject', 'image', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessages::class);
    }
}
