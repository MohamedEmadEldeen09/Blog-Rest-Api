<?php

/**
 * ----------------- Test Cases -----------------
 * 1. ensure that this route not accessible by the guest
 * 2. ensure that this route not accessible by the normal user
 * 3. admin can delete the catagory
 * ---------------------------------------------
 */

use App\Models\Admin;
use App\Models\Catagory;
use App\Models\User;

describe('Test the catagory destroying process by the admin', function () {
    it('ensure that this route not accessible by the guest or the normal user', function () {
        $catagory = Catagory::factory()->create();

        $response = $this->deleteJson(route('catagory.destroy', [
            'catagory' => $catagory->id
        ]));

        $response->assertUnauthorized();
    });

    it('ensure that this route not accessible by the normal user', function () {
        $catagory = Catagory::factory()->create();

        $user = User::factory()->create();

        $token = $user->createToken("secret_for_" . $user->email)->plainTextToken;

        $response = $this->withToken($token)->deleteJson(route('catagory.destroy', [
                'catagory' => $catagory->id
            ]));

        $response->assertUnauthorized();
    });

    it('admin can delete the catagory', function () {
        $catagory = Catagory::factory()->create();

        $admin = Admin::factory()->create();

        $token = $admin->createToken("secret_for_" . $admin->email)->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson(route('catagory.destroy', [
                'catagory' => $catagory->id
            ]));

        $response->assertNoContent();

        $this->assertDatabaseMissing('catagories', [
            'id' => $catagory->id
        ]);

        expect(Catagory::count())->toBe(0);
    });
});