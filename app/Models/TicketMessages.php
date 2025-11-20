<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TicketMessages
 * @package App\Models
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property bool $is_question
 * @property bool $is_answer
 * @property int $status
 * @property string $message
 * @property string $image
 *
 * @property Ticket $ticket
 * @property User $user
 */
class TicketMessages extends Model
{
    use HasFactory;

    const STATUS_NEW = 1;
    const STATUS_READ = 2;

    protected $fillable = ['ticket_id', 'status', 'message', 'image', 'user_id', 'is_question', 'is_answer'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
