<?php

namespace App\Http\Middleware;

use App\Enums\OtpTypesEnum;
use App\Services\V1\OtpService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class EnsureEmailIsVerified
{
    public function __construct(
        private OtpService $otpService,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @return \Illuminate\Http\Response|RedirectResponse|null
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|null
    {
        $user = $request->user();

        abort_if(
            ! $user || ! $this->otpService->has($user, OtpTypesEnum::REGISTER),
            Response::HTTP_FORBIDDEN,
            'Your email address is not verified.'
        );

        return $next($request);
    }
}
