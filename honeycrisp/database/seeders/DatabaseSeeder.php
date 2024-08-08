<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\User;
use App\Models\PaymentAccount;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Joel Salisbury',
            'netid' => 'jrs06005',
            'email' => 'joel@uconn.edu',
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Marlene Schwartz',
            'netid' => 'mbs12001',
            'email' => 'marlene.schwartz@uconn.edu',
            'role' => 'user',
            'status' => 'active',
            'price_group' => 'external_nonprofit',
        ]);

        Facility::factory()->create([
            'name' => 'Digital Experience Group',
            'abbreviation' => 'DXG',
            'status' => 'active',
            'address' => '384 Mansfield Road, Storrs, CT 06269',
            'email' => 'joel@uconn.edu',
            'description' => 'The Digital Experience Group (DXG) is a team of developers, designers, and strategists who create digital experiences for the University of Connecticut.',
        ]);

        PaymentAccount::factory()->create([
            'account_name' => 'UConn Foundation',
            'account_number' => '1234567890',
            'account_type' => 'uch',
            'expiration_date' => '2024-04-24',
            'account_status' => 'active',

        ]);

        PaymentAccount::factory()->create([
            'account_name' => 'Kuali Financial System',
            'account_number' => '0987654321',
            'account_type' => 'kfs',
            'expiration_date' => '2024-04-24',
            'account_status' => 'active',
        ]);

        PaymentAccount::factory()->create([
            'account_name' => 'Other Account',
            'account_number' => '1357924680',
            'account_type' => 'kfs',
            'expiration_date' => '2024-04-24',
            'account_status' => 'active',
        ]);

        // create 10 random payment accounts
        PaymentAccount::factory()->count(30)->create();

        // create 100 random users
        User::factory()->count(4800)->create();
            


    }
}
