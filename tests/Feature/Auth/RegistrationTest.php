<?php

use function Pest\Laravel\json;

test('new users can register', function () {
    $response = $this->post('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertJsonStructure([
        'token',
        'user' => [
            'id',
            'name',
            'email',
            'email_verified_at',
        ]
    ]);

    $this->assertJson($response->getContent());

    $response->assertStatus(201);

    /* test the authenticatoin using the token that sent back with the response */
    $protectedRouteResponse = $this->withToken(json_decode($response->getContent())->token)
        ->getJson(route('profile.profile'));

    /* check if he still authenticated with this token */
    $this->assertAuthenticated();

    $protectedRouteResponse->assertOk();
});
