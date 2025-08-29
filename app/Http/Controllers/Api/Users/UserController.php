<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Services\Api\UserProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserProfileService $profileService;

    public function __construct(UserProfileService $profileService)
    {
        parent::__construct();
        $this->profileService = $profileService;
    }

    public function index()
    {
        return UserResource::collection(User::paginate(10));
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $updatedUser = $this->profileService->updateProfile(
                $user,
                $request->validated()
            );
            $this->notificationService->sendNotification(
                $user,
                'profile_update',
                [
                    'message' => __("api.Profil uğurla yeniləndi")
                ]
            );
            return $this->responseMessage('success', __('api.Profile has been successfully updated'), $user->fresh(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }
}
