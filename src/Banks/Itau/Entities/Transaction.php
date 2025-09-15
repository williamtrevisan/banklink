<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;
use Banklink\Support\Date;
use DateTimeImmutable;

final class Transaction extends Entities\Transaction
{
    public static function fromCardTransaction(array $transactions): array
    {
        return array_map(function (array $transaction): Transaction {
            $hasInstallment = str($transaction['descricao'] ?? '')
                ->match('/\(?\d{1,2}\/\d{1,2}\)?$/')
                ->isNotEmpty();

            return new self(
                date: Date::normalizePtBrDate($transaction['data'], now()->year),
                description: $transaction['descricao'] ?? '',
                amount: $transaction['valor'] ?? '',
                sign: $transaction['sinalValor'] ?? '',
                installments: $hasInstallment
                    ? Installment::from($transaction)
                    : null,
            );
        }, $transactions);
    }

    public static function fromCheckingAccountTransaction(array $transaction): static
    {
        return new self(
            date: DateTimeImmutable::createFromFormat('d/m/Y', $transaction['dataLancamento']),
            description: $transaction['descricaoLancamento'] ?? '',
            amount: $transaction['valorLancamento'] ?? '',
            sign: $transaction['indicadorOperacao'] === 'credito' ? '+' : '-',
            paymentMethod: $transaction['indicadorOperacao'] ?? 'debit',
        );
    }
}
