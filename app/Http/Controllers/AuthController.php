<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // POST /api/register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6',
            'email'    => 'nullable|email|unique:users,email',
            'address'  => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude'=> 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'address'  => $request->address,
            'latitude' => $request->latitude,
            'longitude'=> $request->longitude,
        ]);

        $token = $user->createToken('trashdeal-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Welcome to TrashDeal.',
            'token'   => $token,
            'user'    => [
                'id'           => $user->id,
                'name'         => $user->name,
                'phone'        => $user->phone,
                'email'        => $user->email,
                'address'      => $user->address,
                'latitude'     => $user->latitude,
                'longitude'    => $user->longitude,
                'total_points' => $user->total_points,
                'role'         => $user->role,
            ]
        ], 201);
    }

    // POST /api/login
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'login'    => 'required|string', // email OR phone
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    // Check email OR phone
    $user = User::where('email', $request->login)
                ->orWhere('phone', $request->login)
                ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials.'
        ], 401);
    }

    // Delete old tokens
    $user->tokens()->delete();

    $token = $user->createToken('trashdeal-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login successful!',
        'token'   => $token,
        'user'    => [
            'id'           => $user->id,
            'name'         => $user->name,
            'phone'        => $user->phone,
            'email'        => $user->email,
            'total_points' => $user->total_points,
            'is_premium'   => $user->is_premium,
            'role'         => $user->role,
        ]
    ]);
}

    // GET /api/profile
   public function profile(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    return response()->json([
        'success' => true,
        'user'    => [
            'id'                 => $user->id,
            'name'               => $user->name,
            'phone'              => $user->phone,
            'email'              => $user->email,
            'total_points'       => $user->total_points,
            'is_premium'         => $user->is_premium,
            'premium_plan'       => $user->premium_plan,
            'premium_expires_at' => $user->premium_expires_at,
            'role'               => $user->role,
            'address'            => $user->address,
            'latitude'           => $user->latitude,
            'longitude'          => $user->longitude,
            'profile_photo'      => $user->profile_photo
                ? asset('storage/' . $user->profile_photo)
                : null,
        ]
    ]);
}

    // POST /api/logout
    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();

        if ($token) {
            $token->delete();
        } else {
            $request->user()?->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

    // POST /api/send-otp
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone'   => 'required|string|max:15',
            'purpose' => 'required|string'
        ]);

        $otp = rand(100000, 999999);

        OtpVerification::where('phone', $request->phone)
                        ->where('purpose', $request->purpose)
                        ->delete();

        OtpVerification::create([
            'phone'      => $request->phone,
            'otp_code'   => $otp,
            'purpose'    => $request->purpose,
            'expires_at' => now()->addMinutes(10),
        ]);

        // TODO: integrate SMS gateway (Twilio / MSG91) here
        // For now, return OTP in response (remove in production!)
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'otp'     => $otp  // REMOVE THIS IN PRODUCTION
        ]);
    }

    // POST /api/verify-otp
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'otp_code' => 'required|string',
            'purpose'  => 'required|string',
        ]);

        $record = OtpVerification::where('phone', $request->phone)
                                  ->where('otp_code', $request->otp_code)
                                  ->where('purpose', $request->purpose)
                                  ->where('is_used', false)
                                  ->where('expires_at', '>', now())
                                  ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.'
            ], 422);
        }

        $record->update(['is_used' => true]);

        // Mark user as verified
        User::where('phone', $request->phone)->update(['is_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.'
        ]);
    }
}
