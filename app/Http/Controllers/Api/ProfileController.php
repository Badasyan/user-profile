<?php

namespace App\Http\Controllers\Api;

use App\DTOs\UpdateProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * Update user profile via API
     */
    public function update(UpdateProfileRequest $request)
    {
        try {
            $dto = UpdateProfileDTO::fromArray($request->validated());
            $user = $this->userService->updateProfile($request->user(), $dto);

            return response()->json([
                'user' => new UserResource($user),
                'message' => 'Profile updated successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Profile update failed. Please try again.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }
}
