<?php

// database/seeders/ActivitySeeder.php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $activities = [
            [
                'type' => 'subscription',
                'title' => 'Spotify Premium',
                'description' => 'Monthly subscription to Spotify Premium',
                'status' => 'active',
                'amount' => 250,
            ],
            [
                'type' => 'subscription',
                'title' => 'Google Cloud Platform',
                'description' => 'Monthly subscription to Google Cloud Platform',
                'status' => 'active',
                'amount' => 300,
            ],
            [
                'type' => 'transaction',
                'title' => 'Software License Purchase',
                'description' => 'Purchased Adobe Creative Suite license for the team',
                'status' => 'completed',
                'amount' => 25500,
            ],
            [
                'type' => 'transaction',
                'title' => 'Flight Booking',
                'description' => 'Business trip to New York - Round trip ticket',
                'status' => 'pending',
                'amount' => 23750,
            ],
            [
                'type' => 'investment',
                'title' => 'Stock Purchase',
                'description' => 'Bought 50 shares of Apple Inc. (AAPL)',
                'status' => 'completed',
                'amount' => 7500,
            ],
            [
                'type' => 'system',
                'title' => 'Account Verification',
                'description' => 'Your account has been successfully verified',
                'status' => 'completed',
                'amount' => null,
            ],
        ];

        foreach ($activities as $activity) {
            Activity::create(array_merge($activity, [
                'user_id' => $user->id,
                'created_at' => now()->subDays(rand(1, 30)),
            ]));
        }
    }
}
