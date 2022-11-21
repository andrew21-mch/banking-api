<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTransactionsTest extends TestCase
{




    public function test_user_can_create_account()
    {
        $this->withoutExceptionHandling();

        $this->post('/api/auth/register', [
            "name"=> "Andrew",
            "email"=> "drew@gmail.com",
            "password"=>"password",
            "phone"=> "672769636",
            "city"=> "Bamenda",
            "country"=> "Cameroon",
            "address"=> "Bambili Ccast street",
            "dob"=> "2000-05-09",
            "branch_code"=> 1,
            "create_account"=> false,
        ])->assertStatus(201);
    }

    public function test_user_can_login(){
        $this->withoutExceptionHandling();

        $this->post('/api/auth/login', [
            "email"=> "drew@gmail.com",
            "password"=>"password",
        ])->assertStatus(200);
    }

    


}
