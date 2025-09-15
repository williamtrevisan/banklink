<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Account;

use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class GetCheckingAccountSubNavigation
{
    public function __construct(
        private CheckingAccountRepository $checkingAccountRepository,
    ) {}

    public function get(string $operation, Closure $next): string
    {
        $subNavigation = $this->checkingAccountRepository->subNavigation($operation);

        $statementOperation = PageParser::make()
            ->html($subNavigation)
            ->value("a:contains('Extrato')", 'data-op');

        return $next($statementOperation);
    }
}
