<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Mail\VerifyEmail;
use App\Models\OTP;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    use ResponseTrait;

    public function registerUser($request)
    {
        try {
            DB::beginTransaction();
            $otp = rand(1000, 9999);

            $user = User::create([
                'name' => $request->username,
                'email' => $request->email,
                'country' => $request->country,
                'country_code' => $request->country_code,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'type' => $request->type,
                'user_number' => generateUserNumber(),
            ]);

            OTP::create([
                'key' => $request->email,
                'otp' => $otp,
                'user_id' => $user->id,
            ]);

            Mail::to($user->email)->send(new VerifyEmail($otp));

            DB::commit();
            return $this->successResponse('User registered successfully.', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'country_code' => $user->country_code,
                'phone_number' => $user->phone_number,
                'type' => $user->type,
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function VerifyEmail($request)
    {
        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)
                ->first();
            if (!$user) {
                return $this->errorResponse('User not found.', 'Verification failed');
            }
            if ($user->email_verified_at !== null) {
                return $this->errorResponse('Email already verified.', 'Verification failed');
            }

            $otpRecord = OTP::where('key', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$otpRecord) {
                return $this->errorResponse('Invalid OTP.', 'Verification failed');
            }

            $user->email_verified_at = now();
            $user->save();

            $otpRecord->user_status = 1;
            $otpRecord->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return $this->successResponse('Account Created Successfully.', [
                'user' => new UserResource($user),
                'token' => $token,
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function login($request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || $user->email_verified_at === null) {
                return $this->errorResponse('Email not verified.', 'Authentication failed', 401);
            }

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse('Invalid credentials.', 'Authentication failed', 401);
            }
            else {
                $token = $user->createToken('auth_token')->plainTextToken;

                return $this->successResponse('Login successful.', [
                    'user' => new UserResource($user),
                    'token' => $token,
                ]);
            }
        }
        catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}
