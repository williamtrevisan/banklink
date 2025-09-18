<?php

declare(strict_types=1);

namespace Banklink\Enums;

enum TransactionType: string
{
    case Card = 'card';
    case CheckingAccount = 'checking_account';

    public function isCard(): bool
    {
        return $this === TransactionType::Card;
    }

    public function isCheckingAccount(): bool
    {
        return $this === TransactionType::CheckingAccount;
    }
}
