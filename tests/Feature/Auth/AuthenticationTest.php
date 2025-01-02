<?php

use App\Models\User;

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
    /* create a random user */
    $user = User::factory()->create();

    /* first lets log the user in and get the token */
    $token = $user->createToken("secret_for_" . $user->email)->plainTextToken;

    /* check if the user realy logged in */
    $response = $this->withToken($token)->getJson(route('profile.profile'))->assertSuccessful();
    $response->assertJsonFragment(['name' => $user->name]);

    /* log out the user */
    $response = $this->withToken($token)->post(route('logout'));

    /* check if the response is 204 so the user logged out successfully*/
    $response->assertNoContent();
});
