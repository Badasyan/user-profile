<?php

namespace App\Services;

use App\DTOs\UpdateProfileDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get user information
     *
     * @param User $user
     * @return User
     */
    public function getUserInfo(User $user): User
    {
        return $user;
    }

    /**
     * Update user profile
     *
     * @param User $user
     * @param UpdateProfileDTO $dto
     * @return User
     */
    public function updateProfile(User $user, UpdateProfileDTO $dto): User
    {
        return $this->userRepository->update($user, $dto->toArray());
    }
}
