<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\User;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // generate 5 accounts from registered users and branch
        $users = User::all();
        $branches = Branch::all();

        $types = ['savings', 'current', 'fixed'];
        foreach ($users as $user) {
            foreach ($branches as $branch) {
                $user->accounts()->create([
                    'account_num' => $this->generateAccountNumber($branch, $user),
                    'balance' => 0,
                    'type' => $types[rand(0, 2)],
                ]);
            }
        }
    }

    private function generateAccountNumber($branch, $user)
    {
        $accountNumber = $branch->branch_code . $user->id;
        return $accountNumber;
    }
}
