<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LoginUserDTO;
use App\DTOs\RegisterUserDTO;
use App\DTOs\ResetPasswordDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;
    protected $passwordResetService;

    public function __construct(
        AuthService $authService,
        PasswordResetService $passwordResetService
    ) {
        $this->passwordResetService = $passwordResetService;
        $this->authService = $authService;
    }

    /**
     * Register a new user via API
     */
    public function register(RegisterRequest $request)
    {
        try {
            $dto = RegisterUserDTO::fromArray($request->validated());
            $result = $this->authService->register($dto);

            return response()->json([
                'token' => $result['token'],
                'message' => 'Registration successful! Welcome to our platform.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Login user via API
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginUserDTO::fromArray($request->validated());
            $result = $this->authService->login($dto);

            return response()->json([
                'token' => $result['token'],
                'message' => 'Login successful! Welcome back.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login failed. Invalid credentials.',
                'errors' => $e->errors()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed. Please try again.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Logout user via API
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());

            return response()->json([
                'message' => 'Logout successful. See you soon!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed. Please try again.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Get authenticated user info
     */
    public function user(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'user' => new UserResource($request->user()),
                'message' => 'User information retrieved successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve user information.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Send password reset link via email
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $message = $this->passwordResetService->sendResetLink($request->validated()['email']);

            return response()->json([
                'message' => $message
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to send reset link.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send reset link. Please try again.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $dto = ResetPasswordDTO::fromArray($request->validated());
            $message = $this->passwordResetService->resetPassword($dto);

            return response()->json([
                'message' => $message
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to reset password.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to reset password. Please try again.',
                'errors' => ['error' => $e->getMessage()]
            ], 500);
        }
    }
}
