<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Branch;

class UserTransactionsTest extends TestCase
{



    use RefreshDatabase;

    public function test_user_can_create_account_and_perform_transactions()
    {
        Branch::create([
            'branch_code' => "0005",
            'branch_name' => 'test',
        ]);

        $role = \Spatie\Permission\Models\Role::create(['name' => 'customer']);
        $permission = \Spatie\Permission\Models\Permission::create(['name' => 'customer']);

        $role->givePermissionTo($permission);

        $create = $this->post('/api/auth/register', [
            "name"=> "Andrew",
            "email"=> "drew@gmail.com",
            "password"=>"password",
            "phone"=> "672769636",
            "city"=> "Bamenda",
            "country"=> "Cameroon",
            "address"=> "Bambili Ccast street",
            "dob"=> "2000-05-09",
            "branch_code"=> 1,
            "create_account"=> true,
            "account_type"=> "savings"
        ]);

        // login user
        $login = $this->post('/api/auth/login', [
            "email"=> "drew@gmail.com",
            "password"=>"password",
        ]);

        $token = $login->json('token');
        $this->assertNotNull($token);

        $deposit = $this->json('POST', '/api/transactions/deposit', [
            "account_number"=> "00051",
            "amount"=> 100000,
            'user_id' => 1,
        ],
        ['Authorization' => 'Bearer ' . $token]);
        

        
        $withdraw = $this->json('POST', '/api/transactions/deposit', [
            "account_number"=> "00051",
            "amount"=> 10000,
            'user_id' => 1,
        ],
        ['Authorization' => 'Bearer ' . $token]);

        // create second account
        $create2 = $this->post('/api/auth/register', [
            "name"=> "receiver",
            "email"=> "receiver@gmail.com",
            "password"=>"password",
            "phone"=> "672769631",
            "city"=> "Bamenda 2",
            "country"=> "Cameroon",
            "address"=> "Bambili Ccast street",
            "dob"=> "2000-04-09",
            "branch_code"=> 1,
            "create_account"=> true,
            "account_type"=> "deposit"
        ]);

        $transfer = $this->json('POST', '/api/transactions/transfer', [
            "account_number"=> "00051",
            "amount"=> 10000,
            'user_id' => 1,
            'destination_account_number' => '00052',
        ], 
        ['Authorization' => 'Bearer ' . $token]);


        $balance1 = $this->json('POST', '/api/transactions/balance', [
            "account_number"=> "00051",
            'user_id' => 1,
        ], 
        ['Authorization' => 'Bearer ' . $token]);

        $balance2 = $this->json('POST', '/api/transactions/balance', [
            "account_number"=> "00052",
            'user_id' => 2,
        ], 
        ['Authorization' => 'Bearer ' . $token]);

        $statement = $this->json('GET', '/api/transactions/statement/1');
        $this->withoutExceptionHandling();
        $create->assertStatus(200);
        $create->assertJson([
            'message' => 'success',
        ]);

        $login->assertStatus(200);
        $deposit->assertStatus(200);
        $withdraw->assertStatus(200);
        $create2->assertStatus(200);
        $transfer->assertStatus(200);
        $statement->assertStatus(200);
        $balance1->assertStatus(200);
        $balance2->assertStatus(401);


    }
}
