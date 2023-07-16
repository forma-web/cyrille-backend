<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\UpdateUserDTO;
use App\Http\Requests\V1\CheckRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\UserService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function update(UpdateUserRequest $request): UserResource
    {
        return UserResource::make(
            $this->userService->update(UpdateUserDTO::fromRequest($request))
        );
    }

    public function current(): UserResource
    {
        return UserResource::make($this->userService->current());
    }

    public function emailVerify(): Response
    {
        $this->userService->emailVerify();

        return response()->noContent();
    }

    public function emailCheck(CheckRequest $request): Response
    {
        $code = $request->validated('code');

        abort_if(
            ! $this->userService->emailCheck($code),
            SymfonyResponse::HTTP_UNAUTHORIZED,
            __('auth.otp.invalid'),
        );

        return response()->noContent();
    }
}
