<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): Response
    {
        $request->authenticate();

        $admin = Admin::where('email', $request->email)->first();

        $token = $admin->createToken('admin')->plainTextToken;

        $resData = [
            "user" => new UserResource($admin),
            "token" => $token
        ];

        return response($resData, 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
