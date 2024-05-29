<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\User;
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
        ]);

        Facility::factory()->create([
            'name' => 'Digital Experience Group',
            'abbreviation' => 'DXG',
            'status' => 'active',
            'address' => '384 Mansfield Road, Storrs, CT 06269',
            'email' => 'joel@uconn.edu',
            'description' => 'The Digital Experience Group (DXG) is a team of developers, designers, and strategists who create digital experiences for the University of Connecticut.',
        ]);
            


    }
}
