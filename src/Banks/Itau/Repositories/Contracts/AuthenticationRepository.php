<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories\Contracts;

use Illuminate\Http\Client\Response;

interface AuthenticationRepository
{
    public function initializeSession(): Response;

    public function loadSecurityChallenge(): void;

    public function confirmChallengeReady(): void;

    public function fetchChallengeTokens(): string;

    public function executeSignCommand(): void;

    public function executeAntiPirateCommand(): void;

    public function fetchGuardianResponse(): string;

    public function loadITokenForm(): string;

    public function submitIToken(string $token): void;

    public function loadPasswordForm(): void;

    public function submitPassword(): string;
}
