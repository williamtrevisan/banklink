<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;
use Banklink\Support\Date;
use DateTimeImmutable;

final class Transaction extends Entities\Transaction
{
    public function __construct(
        private readonly DateTimeImmutable $date,
        private readonly string $description,
        private readonly string $amount,
        private readonly string $sign,
        private readonly string $paymentMethod = 'credit',
        private readonly ?Installment $installments = null,
    ) {}

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
        $date = DateTimeImmutable::createFromFormat('d/m/Y', $transaction['dataLancamento']);

        return new self(
            date: $date ?: new DateTimeImmutable(),
            description: $transaction['descricaoLancamento'] ?? '',
            amount: $transaction['valorLancamento'] ?? '',
            sign: $transaction['indicadorOperacao'] === 'credito' ? '+' : '-',
            paymentMethod: $transaction['indicadorOperacao'] ?? 'debit',
        );
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function sign(): string
    {
        return $this->sign;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function installments(): ?Installment
    {
        return $this->installments;
    }
}
