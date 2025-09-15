<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Authentication;

use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class ProcessITokenAuthentication
{
    public function __construct(
        private AuthenticationRepository $httpRepository,
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $iTokenFormOperation = PageParser::make()
            ->html($this->httpRepository->fetchGuardianResponse())
            ->extract('loadPage', "/loadPage\\s*\\(\\s*[\"']([^\"';]+)/s");
        session()->put('itoken_form_operation', $iTokenFormOperation);

        $pageParser = PageParser::make()
            ->html($this->httpRepository->loadITokenForm());

        session()->put('submit_itoken_operation', $pageParser->extract('__appValidOP', "/var\\s+__appValidOP\\s*=\\s*[\"']([^\"';]+)[;\"']/s"));
        session()->put('password_form_operation', $pageParser->value('input[name="op"]', 'value'));

        $this->httpRepository->submitIToken($passable);

        return $next($passable);
    }
}
