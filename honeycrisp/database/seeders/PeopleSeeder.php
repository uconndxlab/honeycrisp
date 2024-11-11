<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PeopleSeeder extends Seeder
{
    public function run()
    {
        // Path to your CSV file
        $filePath = base_path('database/people.csv');
        
        // Open and read the CSV file
        if (!File::exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $people = array_map('str_getcsv', file($filePath));
        array_shift($people); // Remove header row

        // Loop through each row in the CSV

        foreach ($people as $data) {
            $netid = $data[5];  // NETID column
            $name = $data[1];   // FULL_NAME column
            $email = $data[12]; // EMAIL column

            // check if user exists, and continue if they do
            if (User::where('netid', $netid)->exists()) {
                $this->command->info("User already exists: {$netid}");
                continue;
            }

            // Create the user
            User::create([
                'netid' => $netid,
                'name' => $name,
                'email' => $netid . '@uconn.edu',
                'price_group' => 'internal'
            ]);

            $this->command->info("User created: {$netid}");
        }

        $this->command->info('PeopleSeeder complete');

    }
}
