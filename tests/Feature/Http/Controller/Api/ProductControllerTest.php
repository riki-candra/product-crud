<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
  /**
   * Authenticate user.
   *
   * @return array
   */
  protected function authenticate()
  {
    $user = [
      'name' => 'Test',
      'email' => 'test@mail.com',
      'password' => '12345678',
      'token' => "",
    ];

    if (!auth()->attempt(['email' => $user['email'], 'password' => $user['password']])) {

      $register_response = $this->json('POST', 'api/register', [
        'name' => $user['name'],
        'email' => $user['email'],
        'password' => $user['password'],
      ]);

      // write the register_response in laravel.log
      \Log::info(1, [$register_response->getContent()]);

      $register_response->assertStatus(200);

      // receive the token
      $this->assertArrayHasKey('token', $register_response->json());
    }

    // simulated landing
    $login_response = $this->json('POST', 'api/login', [
      'email' => $user['email'],
      'password' => $user['password'],
    ]);

    // write the login_response in laravel.log
    \Log::info(1, [$login_response->getContent()]);

    // determine whether the login is successful and receive token
    $login_response->assertStatus(200);

    // receive the token
    $this->assertArrayHasKey('token', $login_response->json());

    // set token to user
    $user['token'] = $login_response->json()['token'];

    return $user;
  }

  public function testGetListProducts()
  {
    // get the token
    $user = $this->authenticate();

    // make get list request with using token
    $list_response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $user['token'],
    ])->json('GET', 'api/products');

    // write the response to laravel.log
    \Log::info(1, [$list_response->getContent()]);

    $list_response->assertStatus(200);
  }

  public function testCreateAProduct()
  {
    // get the token
    $user = $this->authenticate();

    // make post request with using token and body
    $create_response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $user['token'],
    ])->json('POST', 'api/products', [
      'name' => 'Product 2',
      'detail' => 'Product 2 Detail',
    ]);

    // write the response in laravel.log
    \Log::info(1, [$create_response->getContent()]);

    $create_response->assertStatus(200);
  }

  public function testDetailProduct()
  {
    $user = $this->authenticate();
    $product = Product::where('name', 'Product 2')->get()->first();

    $detail_response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $user['token'],
    ])->json('GET', 'api/products/' . $product->id);

    // write the response in laravel.log
    \Log::info(1, [$detail_response->getContent()]);

    $detail_response->assertStatus(200);
  }

  public function testUpdateProduct()
  {
    // get the token
    $user = $this->authenticate();

    $product = Product::where('name', 'Product 2')->get()->first();

    // make put request with using token and body
    $update_response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $user['token'],
    ])->json('PUT', 'api/products/' . $product->id, [
      'name' => 'Product 2 - updated',
      'detail' => 'Product 2 Detail',
    ]);

    // write the response in laravel.log
    \Log::info(1, [$update_response->getContent()]);

    $update_response->assertStatus(200);
  }

  public function testDeleteProduct()
  {
    // get the token
    $user = $this->authenticate();

    $product = Product::where('name', 'Product 2 - updated')->get()->first();

    // make put request with using token and body
    $delete_response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $user['token'],
    ])->json('DELETE', 'api/products/' . $product->id);

    // write the response in laravel.log
    \Log::info(1, [$delete_response->getContent()]);

    $delete_response->assertStatus(200);

    // delete created user at the end of test
    User::where('email', $user['email'])->delete();
  }
}
