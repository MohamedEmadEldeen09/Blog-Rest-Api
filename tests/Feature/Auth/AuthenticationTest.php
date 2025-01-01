<?php

use App\Models\User;
//use Laravel\Sanctum\Sanctum;

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertOk();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    /*
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post(route('logout'));
    $this->assertGuest();
    $response->assertNoContent();
    //$response->assertUnauthorized();
    //$response->assertServerError();
    */

    
    // $user = User::factory()->create();

    // $response = $this->post(route('login'), [
    //     'email' => $user->email,
    //     'password' => 'password',
    // ]);

    //$token = $response->json()['token'];

    // $headers = [
    //     //'Accept' => 'application/json',
    //     //'Content-Type' => 'application/json',
    //     //'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
    //     'Authorization' => 'Bearer ' . $token->plainTextToken,
    // ];


    //$this->post(route('logout'), [], $headers)->assertNoContent();

    // $this->assertAuthenticated();

    // $token =json_decode($response->getContent())->token;

    // //$token = $user->tokens()->first()->plainTextToken;

    // $headers = [
    //     'Accept' => 'application/json',
    //     //'Content-Type' => 'application/json',
    //     //'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
    //     'Authorization' => 'Bearer ' . $token,
    // ];

    // $response2 = $this->post(route('logout'), [], $headers);

    // $response2->assertNoContent();

    // $this->assertGuest('web');

    /*
    // Create a user
    $user = User::factory()->create();

    // Authenticate the user using Sanctum
    Sanctum::actingAs($user, ['*']);

    // Ensure the user is authenticated
    $this->assertAuthenticatedAs($user);

    // Make a POST request to the logout endpoint
    $response = $this->postJson('/api/logout');

    // Assert the response status is 204 No Content
    $response->assertStatus(204);

    // Assert the user is logged out
    $this->assertGuest();
    */
});
