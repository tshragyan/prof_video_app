<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $shopify_data
 * @property string $shopify_id
 * @property integer $plan_id
 * @property string $password
 * @property string $shopify_token
 * @property integer $status
 * @property string $shopify_username
 * @property string $remember_token
 * @property Plan $plan
 * @property Video[] $videos
 * @property Product[] $products
 * @property PlanChargeRequest[] $planChargeRequests
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLES_ADMIN = 1;
    const ROLES_USER = 2;
    const ROLES_SUPER_ADMIN = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_MAP = [
      self::STATUS_ACTIVE => 'Active',
      self::STATUS_INACTIVE => 'Inactive',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'shopify_data',
        'shopify_id',
        'plan_id',
        'password',
        'role',
        'status',
        'shopify_token',
        'shopify_username',
    ];

    public static array $columns = [
        'id',
        'name',
        'email',
        'shopify_data',
        'status',
        'plan_id',
        'shopify_username',
    ];

    public function getDomain(): string
    {
        if (!str_contains($this->shopify_username, '.myshopify.com')) {
            return $this->shopify_username . '.myshopify.com';
        }

        return $this->shopify_username;
    }

    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function planChargeRequests(): HasMany
    {
        return $this->hasMany(PlanChargeRequest::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
