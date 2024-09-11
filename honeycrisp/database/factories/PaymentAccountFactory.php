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
            'account_name' => $this->faker->company,
            'account_number' => $this->faker->creditCardNumber,
            'account_type' => $this->faker->randomElement(['kfs','uch']),
            'expiration_date' => $this->faker->creditCardExpirationDate,
            'account_status' => 'active',
            
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (PaymentAccount $paymentAccount) {

            // attach user #1 as the owner of the payment account
            $paymentAccount->users()->attach(1, ['role' => 'owner']);

            // attach user #2 as fiscal officer of the payment account
            $paymentAccount->users()->attach(2, ['role' => 'fiscal_officer']);
        });
    }
}
