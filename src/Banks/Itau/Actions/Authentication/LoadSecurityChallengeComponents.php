<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Authentication;

use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Closure;

final readonly class LoadSecurityChallengeComponents
{
    public function __construct(
        private AuthenticationRepository $httpRepository,
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $this->httpRepository->loadSecurityChallenge();
        $this->httpRepository->confirmChallengeReady();

        return $next($passable);
    }
}
