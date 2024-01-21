<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ','',$token);

        if (!$token) {
            return response()->json(['error' => 'Authorization token not found'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));

            return $next($request);


        }catch(SignatureInvalidException $e){
            return response()->json(['error' => 'invalid token'], 401);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
