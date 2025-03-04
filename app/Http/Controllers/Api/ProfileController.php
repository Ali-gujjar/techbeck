<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;
use App\Traits\FileUploadTrait;

class ProfileController extends Controller
{
    use ResponseTrait, FileUploadTrait;

    public function setup(ProfileRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();

            if ($request->hasFile('user_image')) {
                if ($user->user_image) {
                    $this->deleteFile($user->user_image);
                }

                $user->user_image = $this->uploadFile($request->file('user_image'), 'user_images');
            }

            if ($request->hasFile('emirates_image')) {
                if ($user->emirates_image) {
                    $this->deleteFile($user->emirates_image);
                }

                $user->emirates_image = $this->uploadFile($request->file('emirates_image'), 'emirates_images');
            }

            if ($request->hasFile('passport_image')) {
                if ($user->passport_image) {
                    $this->deleteFile($user->passport_image);
                }

                $user->passport_image = $this->uploadFile($request->file('passport_image'), 'passport_images');
            }

            if ($request->hasFile('trading_license_image')) {
                if ($user->trading_license_image) {
                    $this->deleteFile($user->trading_license_image);
                }

                $user->trading_license_image = $this->uploadFile($request->file('trading_license_image'), 'trading_licenses');
            }

            $user->company_name = $request->company_name;

            $user->save();
            DB::commit();

            return $this->successResponse('Profile setup completed successfully.', [
                'user' => new UserResource($user)
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
