<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Account;

use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Banklink\Support\PageParser;

final readonly class GetCheckingAccountStatement
{
    public function __construct(
        private CheckingAccountRepository $checkingAccountRepository,
    ) {}

    public function get(string $operation): string
    {
        $statement = $this->checkingAccountRepository->statements($operation);

        return PageParser::make()
            ->html($statement)
            ->extract('carregaExtrato', '/url\s*=\s*"([^"]+)"/');
    }
}
