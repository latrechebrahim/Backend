<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        try {
            DB::table('users')->insert([
                'firstname' => Str::random(10),
                'lastname' => Str::random(10),
                'phonenumber' => rand(500000000, 999999999),
                'email' => Str::random(10).'@example.com',
                'password' => Hash::make('password'),
                'confirmpassword' => Hash::make('password'),
            ]);
            $this->command->info('User created successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error while registering user: ' . $e->getMessage());
        }

    }
}
