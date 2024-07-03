<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PaymentAccount;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentAccount>
 */
class PaymentAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->creditCardNumber,
            'account_type' => $this->faker->randomElement(['kfs','uch']),
            'expiration_date' => $this->faker->creditCardExpirationDate,
            'account_status' => 'active'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (PaymentAccount $paymentAccount) {
            $users = User::factory()->count(3)->create();

            $paymentAccount->users()->attach($users[0]->id, ['role' => 'owner']);
            $paymentAccount->users()->attach($users[1]->id, ['role' => 'fiscal_officer']);
            $paymentAccount->users()->attach($users[2]->id, ['role' => 'account_supervisor']);
        });
    }
}
