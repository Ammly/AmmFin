<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Activity;
use App\Models\Currency;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_own_account(): void
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
        ]);

        $this->assertTrue($user->can('view', $account));
    }

    public function test_user_cannot_view_another_users_account(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $currency = Currency::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user2->id,
            'currency_id' => $currency->id,
        ]);

        $this->assertFalse($user1->can('view', $account));
    }

    public function test_user_can_view_their_own_transaction(): void
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
        ]);
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'account_id' => $account->id,
        ]);

        $this->assertTrue($user->can('view', $transaction));
    }

    public function test_user_can_view_their_own_investment(): void
    {
        $user = User::factory()->create();
        $investment = Investment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('view', $investment));
    }

    public function test_user_can_view_their_own_activity(): void
    {
        $user = User::factory()->create();
        $activity = Activity::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('view', $activity));
    }

    public function test_user_can_view_currencies(): void
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();

        $this->assertTrue($user->can('view', $currency));
    }
}
