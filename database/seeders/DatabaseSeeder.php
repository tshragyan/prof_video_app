<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Plan;
use App\Models\PlanChargeRequest;
use App\Models\Product;
use App\Models\ShopifyErrorLog;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Plan::query()->create([
            'name' => 'basic',
            'description' => 'description',
            'max_video_count' => 100,
            'max_video_size' => 100,
            'price' => 50,
            'old_price' => 60,
            'annual_price' => 500,
            'old_annual_price' => 600,
        ]);

        User::query()->create([
            'name' => 'Daniel',
            'email' => 'tshragyand@gmail.com',
            'shopify_data' => '{}',
            'shopify_id' => '123456',
            'plan_id' => 2,
            'shopify_token' => 'token',
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLES_USER,
            'shopify_username' => 'username',
        ]);

        for ($i = 0; $i < 10; $i++) {
            Product::query()
                ->create([
                    'title' => Str::random(6),
                    'user_id' => 1,
                    'shopify_data' => '[]'
                ]);
        }

        for ($i = 1; $i < 10; $i++) {
            Video::query()
                ->create([
                    'title' => Str::random(6),
                    'src' => 'https://www.youtube.com/watch?v=GMqWWpEZnnQ',
                    'size' => '100',
                    'user_id' => 1,
                    'product_id' => ++$i,
                ]);
        }


        PlanChargeRequest::query()
            ->create([
                'user_id' => 1,
                'plan_id' => 1,
                'type' => PlanChargeRequest::TYPE_ANNUAL,
                'status' => PlanChargeRequest::STATUS_ACTIVE,
                'external_id' => 'abcdef',
                'activated_at' => time(),
                'shopify_data' => 'abcdef',
            ]);


        ShopifyErrorLog::query()
            ->create([
                'user_id' => 1,
                'method' => 'getProducts',
                'data' => '{}',
            ]);



        User::query()->create([
            'name' => 'admin',
            'email' => 'admin@videocrat.com',
            'shopify_data' => '{}',
            'shopify_id' => '123456',
            'plan_id' => 2,
            'shopify_token' => 'token',
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLES_ADMIN,
            'shopify_username' => 'username',
            'password' => Hash::make('ch658741'),
        ]);



        User::query()->create([
            'name' => 'admin',
            'email' => 'super-admin@videocrat.com',
            'shopify_data' => '{}',
            'shopify_id' => '123456',
            'plan_id' => 2,
            'shopify_token' => 'token',
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLES_SUPER_ADMIN,
            'shopify_username' => 'username',
            'password' => Hash::make('d658741'),
        ]);
    }
}
