<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PlanChargeRequest
 * @package App\Models
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $plan_id
 * @property string $external_id
 * @property integer $type
 * @property integer $status
 * @property integer $activated_at
 * @property integer $canceled_at
 * @property integer $created_at
 * @property string $shopify_data
 * @property integer $updated_at
 * @property User $user
 * @property Plan $plan
 */
class PlanChargeRequest extends Model
{
    use HasFactory;

    const TYPE_ANNUAL = 1;
    const TYPE_MONTHLY = 2;
    const TYPES_MAPPING = [
        self::TYPE_ANNUAL => 'ANNUAL',
        self::TYPE_MONTHLY => 'MONTHLY',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_CANCELED = 3;
    const STATUS_MAPPINGS = [
      self::STATUS_ACTIVE => 'Active',
      self::STATUS_PENDING => 'Pending',
      self::STATUS_CANCELED => 'Canceled',
    ];

    public static array $columns = [
        'id',
        'user_id',
        'plan_id',
        'type',
        'external_id',
        'status',
        'activated_at',
        'canceled_at',
    ];

    protected $fillable = [
      'user_id',
      'plan_id',
      'type',
      'external_id',
      'shopify_data',
      'status',
      'activated_at',
      'canceled_at',
    ];

    public function Plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
