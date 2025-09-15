<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Authentication;

use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class InitializeAuthenticationSession
{
    public function __construct(
        private AuthenticationRepository $httpRepository,
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $pageParser = PageParser::make()
            ->html(($response = $this->httpRepository->initializeSession())->body());

        session()->put('auth_token', $response->header('x-auth-token'));
        session()->put('flow_id', $response->header('x-flow-id'));
        session()->put('client_id', $response->header('x-client-id'));
        session()->put('security_challenge_operation', $pageParser->extract('SECAPDK', "/\\SECAPDK\\s*\\.uidap\\('([^']+)'/"));
        session()->put('challenge_ready_operation', $pageParser->extract('SECBCATCH', "/\\SECBCATCH\\s*\\.uidap\\('([^']+)'/"));
        session()->put('fetch_tokens_operation', $pageParser->extract('performRequest', '/performRequest\(\s*"([^"]+)"/'));

        return $next($passable);
    }
}
