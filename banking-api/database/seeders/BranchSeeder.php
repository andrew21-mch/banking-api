<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = [
            [
                'branch_code' => '0001',
                'branch_name' => 'Branch 1',
            ],
            [
                'branch_code' => '0002',
                'branch_name' => 'Branch 2',
            ],
            [
                'branch_code' => '0003',
                'branch_name' => 'Branch 3',
            ],
            [
                'branch_code' => '0004',
                'branch_name' => 'Branch 4',
            ],
            [
                'branch_code' => '0005',
                'branch_name' => 'Branch 5',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
