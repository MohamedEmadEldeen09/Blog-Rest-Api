<?php

use App\Models\Admin;
//use Laravel\Sanctum\Sanctum;

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
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin, 'admin')->post(route('admin.logout'));

    $this->assertGuest();

    $response->assertNoContent();
});
