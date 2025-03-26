<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateProfileRequest;
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
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        Auth::attempt($request->only('phone', 'password'));
        $token = $this->generateTokens(Auth::user());

        return response()->json([
            'message' => 'Đăng ký thành công',
            'tokens' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            $request->user()->tokens()->delete();
            $tokens = $this->generateTokens(Auth::user());

            return response()->json([
                'message' => 'Đăng nhập thành công',
                'tokens' => $tokens
            ]);
        }

        return response()->json(['message' => 'Đăng nhập thất bại',], 401);
    }

    public function profile(Request $request)
    {
        return response()->json(User::query()->where('id',Auth::id())->with('shop', 'ward', 'district', 'province')->first());
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
            'expire_time' => $atExpireTime->timestamp
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

    public function updateProfile(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        if($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $path = $avatar->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);
        return response()->json(['message' => 'Cập nhật thông tin thành công']);
    }
}
