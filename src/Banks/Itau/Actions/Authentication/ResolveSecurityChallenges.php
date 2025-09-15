<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Authentication;

use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class ResolveSecurityChallenges
{
    public function __construct(
        private AuthenticationRepository $httpRepository
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $pageParser = PageParser::make()
            ->html($this->httpRepository->fetchChallengeTokens());

        session()->put('sign_command_operation', $pageParser->extract('opSignCommand', '/__opSignCommand\s*=\s*"([^"]+)"/'));
        session()->put('anti_pirate_operation', $pageParser->extract('opMaquinaPirata', '/__opMaquinaPirata\s*=\s*"([^"]+)"/'));
        session()->put('guardian_callback_operation', $pageParser->extract('loadPage', "/loadPage\\('([^']+)'/"));

        return $next($passable);
    }
}
