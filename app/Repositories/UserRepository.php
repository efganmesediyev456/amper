<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserTemp;
use App\Models\UserVerify;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function createTemp(array $data): UserTemp
    {
        $data['password'] = Hash::make($data['password']);
        return UserTemp::updateOrCreate(['email'=>$data['email']],$data);
    }

    public function getUserVerify(array $data){
       return UserVerify::where('email', $data['email'])
                ->where('otp_code', $data['otp_code'])
                ->first();
    }

    public function getByEmail(string $email): ?User
    {
       return User::where('email', $email)->first();

    }

    public function getTempUser(string $email): ?UserTemp
    {
        return UserTemp::where('email', $email)->first();
    }


    public function update(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user->refresh();
    }
}
