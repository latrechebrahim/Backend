<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DoctorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Generate random data for multiple doctors
        for ($i = 0; $i < 10; $i++) { // Change 10 to the number of doctors you want to add
            $doctor = [
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'specialty' => $faker->randomElement(['General Physician', 'Pediatrician', 'Dermatologist']),
                'email' => $faker->unique()->safeEmail,
                'phonenumber' => self::generateRandomPhoneNumber(), // Call the method statically
                'date_birth' => $faker->date('Y-m-d', '-30 years'),
                'path' => 'C:\Users\mcomm\OneDrive\Images\Nitro\Nitro_Wallpaper_5000x2813.jpg',
                'password' => Hash::make('password'),
                'confirmpassword' => Hash::make('password'),
                'admin' => false,
                'isAvailable' => $faker->boolean(80),
            ];

            DB::table('doctors')->insert($doctor);
        }
    }

    /**
     * Generate a random phone number starting with "05", "06", or "07",
     * followed by 8 random digits.
     *
     * @return string Random phone number
     */
    private static function generateRandomPhoneNumber() {
        // Array of possible starting digits
        $startDigits = ['05', '06', '07'];

        // Choose a random starting digit
        $start = $startDigits[array_rand($startDigits)];

        // Generate 8 random digits
        $randomDigits = mt_rand(10000000, 99999999);

        // Concatenate the starting digit with the random digits
        $phoneNumber = $start . $randomDigits;

        return $phoneNumber;
    }
}
