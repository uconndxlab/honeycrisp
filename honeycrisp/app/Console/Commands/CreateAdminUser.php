<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'hc:admin {name} {email}';
    protected $description = 'Create a new admin user with an auto-generated password';

    public function handle()
    {
        // Get the arguments
        $name = $this->argument('name');
        $email = $this->argument('email');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("A user with the email {$email} already exists.");
            return;
        }

        // Auto-generate a password
        $password = Str::random(12);

        // Create the user and assign them the admin role
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'price_group' => 'internal',
            'password' => Hash::make($password),
            'role' => 'admin', // Assuming you have a role field in your users table
        ]);

        if ($user) {
            $this->info("Admin user created successfully.");
            $this->info("Name: {$name}");
            $this->info("Email: {$email}");
            $this->info("Password: {$password}"); // Show the password in the console
        } else {
            $this->error("Failed to create admin user.");
        }
    }
}
