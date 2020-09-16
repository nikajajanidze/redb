<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Str;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testLogin()
    {
        $response = $this->postJson('/api/auth/login', [
        'email' => 'nikajajanidze@gmail.com',
        'password' => 'nika'
        ]);

        $response->assertStatus(200);
    }

    public function testRegister()
    {
        $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => $this->email(),
        'password' => 'nikaNika123',
        'password_confirmation' => 'nikaNika123'
        ]);

        //$response->dump();
        $response->assertStatus(201);
    }

    protected function email()
    {
        return strtolower(Str::random(6)) . '@gmail.com';
    }
}
