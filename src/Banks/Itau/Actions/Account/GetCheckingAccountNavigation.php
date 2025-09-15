<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Account;

use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class GetCheckingAccountNavigation
{
    public function __construct(
        private CheckingAccountRepository $checkingAccountRepository,
    ) {}

    public function get(mixed $operation, Closure $next): string
    {
        $checkingAccountNavigation = $this->checkingAccountRepository->navigation();

        $subNavigationOperation = PageParser::make()
            ->html($checkingAccountNavigation)
            ->extract('obterMenuContextomenuContexto', '/url\s*:\s*"([^"]+)"/');

        return $next($subNavigationOperation);
    }
}
