<?php

namespace App\Http\Middleware;
use Auth;
use JWTAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
      
        try{
        $token = JWTAuth::getToken();     
        $user = JWTAuth::toUser($token);
        if($user->role !== 'superuser'){
          return response([
            'success'=>false,
            'message'=>'you are not superuser!'
          ],400);
        }
      return $next($request);
    }
    catch (JWTException $exception) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user cannot be logged out'
        ],400);
    }
    catch (TokenInvalidException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, invalid token'
        ],404);
    }
    catch (TokenExpiredException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, invalid token time'
        ],404);
    };
    
    }
}
