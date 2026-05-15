<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponse;

class VerifyIaeKey
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        $nim ='102022400117';
        $headerkey = $request->header('X-IAE-KEY');

        if(!$headerkey || $headerkey !== $nim){
            return $this->errorResponse('Unauthorized',401);
        }
        return $next($request);
    }
}
