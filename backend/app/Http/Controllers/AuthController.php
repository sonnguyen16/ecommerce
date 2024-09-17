<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\TokenAbility;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);

            Auth::attempt($request->only('phone', 'password'));
            $token = $this->generateTokens(Auth::user());

            return response()->json([
                'message' => 'Đăng ký thành công',
                'tokens' => $token
            ]);

        }catch (\Exception $e){
            return response()->json([
                'message' => 'Đăng ký thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try{
            $credentials = $request->only('phone', 'password');

            if (Auth::attempt($credentials)) {
                $request->user()->tokens()->delete();
                $token = $this->generateTokens(Auth::user());
                return response()->json([
                    'message' => 'Đăng nhập thành công',
                    'tokens' => $token
                ]);
            }

            return response()->json(['message' => 'Đăng nhập thất bại',], 401);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Đăng nhập thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        return response()->json(Auth::user());
    }

    public function generateTokens($user, $rtExpireTime = null): array
    {
        $atExpireTime = now()->addMinutes(config('sanctum.expiration'));
        $rtExpireTime = $rtExpireTime ?? now()->addMinutes(config('sanctum.rt_expiration'));

        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API], $atExpireTime);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN], $rtExpireTime);

        return [
            'access_token' => [
                'token' => $accessToken->plainTextToken,
                'expires_at' => $atExpireTime
            ],
            'refresh_token' => [
                'token' => $refreshToken->plainTextToken,
                'expires_at' => $rtExpireTime
            ],
        ];
    }

    public function refresh(Request $request)
    {
        $user = Auth::user();

        $token = substr($request->header('Authorization'), 7);
        $check = PersonalAccessToken::findToken($token);

        if (!$check) {
            return response()->json([
                'message' => 'Token không hợp lệ'
            ], 401);
        }
        $rtExpireTime = $check->expires_at;

        $request->user()->tokens()->delete();

        $tokens = $this->generateTokens($user, $rtExpireTime);

        return response()->json([
            'message' => 'Làm mới token thành công',
            'tokens' => $tokens
        ]);
    }

    public function logout(Request $request)
    {
       $request->user()->tokens()->delete();
       return response()->json(['message' => 'Đăng xuất thành công']);
    }
}
