<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Pipelines;

use Banklink\Banks\Itau\Actions\Authentication\InitializeAuthenticationSession;
use Banklink\Banks\Itau\Actions\Authentication\LoadSecurityChallengeComponents;
use Banklink\Banks\Itau\Actions\Authentication\ProcessITokenAuthentication;
use Banklink\Banks\Itau\Actions\Authentication\ProcessPasswordAuthentication;
use Banklink\Banks\Itau\Actions\Authentication\ResolveSecurityChallenges;
use Illuminate\Pipeline\Pipeline;

final class Authenticator
{
    public function authenticate(): void
    {
        app(Pipeline::class)
            ->send(null)
            ->through([
                InitializeAuthenticationSession::class,
                LoadSecurityChallengeComponents::class,
                ResolveSecurityChallenges::class,
                ProcessITokenAuthentication::class,
                ProcessPasswordAuthentication::class,
            ])
            ->thenReturn();
    }
}
