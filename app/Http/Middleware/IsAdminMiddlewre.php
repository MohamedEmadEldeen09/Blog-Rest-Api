<?php

namespace App\Http\Middleware;

use App\Enums\AppConstantsEnum;
use App\Exceptions\UnAuthorizedToMakeActionMyException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddlewre
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user('sanctum')->email === AppConstantsEnum::MAIN_APP_CHANNEL_EMAIL->value){
            return $next($request);
        }

        throw new UnAuthorizedToMakeActionMyException('Forbidden to make this action!');
    }
}
