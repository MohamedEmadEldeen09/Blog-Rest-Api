<?php

use App\Models\Admin;

test('admin can authenticate using the login screen', function () {
    $admin = Admin::factory()->create();

    $response = $this->post(route('admin.login'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated('admin');

    $response->assertOk();
});

test('admins can not authenticate with invalid password', function () {
    $admin = Admin::factory()->create();

    $this->post(route('admin.login'), [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('admins can logout', function () {
    /* create an admin */
    $admin = Admin::factory()->create();

    /* first lets log the admin in and get the token */
    $token = $admin->createToken("secret_for_" . $admin->email)->plainTextToken;

    /* check if the admin realy logged in */
    $response = $this->withToken($token)->getJson(route('admin.welcome'))->assertSuccessful();
    $response->assertJsonFragment(['message' => 'Welcome ' . $admin->name]);

    /* log out the admin */
    $response = $this->withToken($token)->postJson(route('admin.logout'));

    /* check if the response is 204 so the admin logged out successfully*/
    $response->assertNoContent();
});
