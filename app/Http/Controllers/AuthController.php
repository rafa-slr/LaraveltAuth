<?php

namespace App\Http\Controllers;

  
use App\User;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginAuthRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Str;
use App\Notifications\SignupActive;

class AuthController extends Controller
{
    public function login(LoginAuthRequest $request){
        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'No autorizado'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }

    public function signup(AuthRequest $request){
        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => Str::random(60),
        ]);
        $user->save();
        $user->notify(new SignupActive($user));
        return response()->json([
            'message' => 'Usuario creado correctamente!!'], 201);
       
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json(['message' => 
            'Successfully logged out']);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }

    public function signupActivate($token){
        $user = User::where('activation_token',$token)->first();
        if (!$user) {
            return response()->json(['message' => 'El token de activación es invalido'],404);
        }

        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return $user;
    }
}
