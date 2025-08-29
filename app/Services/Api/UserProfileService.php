<?php
namespace App\Services\Api;

use App\Contracts\UserProfileServiceContract;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserProfileService 
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(User $user, array $data): User
    {
        DB::beginTransaction();
        try {
            $updateData = $this->prepareUpdateData($data);
            
            $updatedUser = $this->userRepository->update($user->id, $updateData);

            // Send notifications if needed
            // $this->sendProfileUpdateNotifications($updatedUser, $updateData);

            DB::commit();
            return $updatedUser;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function prepareUpdateData(array $data): array
    {
        $updateData = [];
        $basicFields = ['name', 'email', 'phone'];
        foreach ($basicFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        if (isset($data['new_password'])) {
            $updateData['password'] = Hash::make($data['new_password']);
        }

        return $updateData;
    }

    

    private function sendProfileUpdateNotifications(User $user, array $updatedData)
    {
        if (isset($updatedData['email'])) {
            $user->sendEmailChangedNotification();
        }

        if (isset($updatedData['password'])) {
            $user->sendPasswordChangedNotification();
        }
    }
}
