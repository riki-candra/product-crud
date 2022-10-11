<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PassportAuthControllerTest extends TestCase
{

  public function testRegister()
  {
    $response = $this->json('POST', 'api/register', [
      'name' => $name = 'Name Test ' . time(),
      'email' => $email = time() . '@mail.com',
      'password' => $password = '12345678',
    ]);

    // write the response in laravel.log
    \Log::info(1, [$response->getContent()]);

    $response->assertStatus(200);

    // receive the token
    $this->assertArrayHasKey('token', $response->json());
  }


  public function testLogin()
  {
    $user = [
      'email' => time() . '@mail.com',
      'password' => '12345678',
    ];

    // simulated landing
    $response = $this->json('POST', 'api/login', [
      'email' => $user['email'],
      'password' => $user['password']
    ]);

    // write the response in laravel.log
    \Log::info(1, [$response->getContent()]);

    // determine whether the login is successful and receive token
    $response->assertStatus(200);

    // receive the token
    $this->assertArrayHasKey('token', $response->json());
  }
}
