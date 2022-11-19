<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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

        $roles = ['admin', 'employee', 'customer'];
        $branches = Branch::all();
        $users = [
            [
                'name' => 'Admin',
                'email' => fake()->email,
                'phone' => fake()->phoneNumber,
                'city' => fake()->city,
                'country' => fake()->country,
                'address' => fake()->address,
                'dob' => fake()->date,
                'branch_code' => $branches->random()->id,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Employee',
                'email' => fake()->email,
                'phone' => fake()->phoneNumber,
                'city' => fake()->city,
                'address' => fake()->address,
                'country' => fake()->country,
                'dob' => fake()->date,
                'branch_code' => $branches->random()->id,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Customer',
                'email' => fake()->email,
                'phone' => fake()->phoneNumber,
                'city' => fake()->city,
                'address' => fake()->address,
                'country' => fake()->country,
                'dob' => fake()->date,
                'branch_code' => $branches->random()->id,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            $user = \App\Models\User::create($user);
            $user->assignRole($roles[rand(0, 2)]);
        }
    }
}
