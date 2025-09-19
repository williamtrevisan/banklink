<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;
use Banklink\Enums\TransactionDirection;
use Banklink\Enums\TransactionKind;
use Banklink\Enums\TransactionPaymentMethod;
use Banklink\Enums\TransactionType;
use Brick\Money\Money;
use Illuminate\Support\Carbon;

final class Transaction extends Entities\Transaction
{
    public function __construct(
        private readonly Carbon $date,
        private readonly string $description,
        private readonly Money $amount,
        private readonly TransactionDirection $direction,
        private TransactionKind $kind = TransactionKind::Purchase,
        private readonly TransactionPaymentMethod $paymentMethod = TransactionPaymentMethod::Credit,
        private readonly ?Installment $installments = null,
    ) {}

    public static function fromCardTransaction(array $transaction): static
    {
        $description = str($transaction['descricao'])
            ->deduplicate()
            ->value();

        $transaction = new self(
            date: rescue(
                fn (): Carbon => Carbon::parse($transaction['data']),
                fn (): ?\Illuminate\Support\Carbon => Carbon::createFromLocaleFormat('d / F', 'pt_BR', $transaction['data']),
                report: false,
            ),
            description: $description,
            amount: money()->of($transaction['valor']),
            direction: TransactionDirection::fromSign($transaction['sinalValor'] === '-'),
            installments: str($description)->match('/\(?\d{1,2}\/\d{1,2}\)?$/')->isNotEmpty()
                ? Installment::from($transaction)
                : null,
        );

        return tap($transaction, function (Transaction $transaction): static {
            $transaction->kind = TransactionKind::fromTransaction($transaction, transactionType: TransactionType::Card);

            return $transaction;
        });
    }

    public static function fromCheckingAccountTransaction(array $transaction): static
    {
        $transaction = new self(
            date: Carbon::createFromFormat('d/m/Y', $transaction['dataLancamento']),
            description: str($transaction['descricaoLancamento'])->deduplicate()->value(),
            amount: money()->of($transaction['valorLancamento']),
            direction: TransactionDirection::fromSign($transaction['ePositivo']),
            paymentMethod: TransactionPaymentMethod::fromOperation($transaction['indicadorOperacao']),
        );

        return tap($transaction, function (Transaction $transaction): static {
            $transaction->kind = TransactionKind::fromTransaction($transaction, transactionType: TransactionType::CheckingAccount);

            return $transaction;
        });
    }

    public function date(): Carbon
    {
        return $this->date;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function direction(): TransactionDirection
    {
        return $this->direction;
    }

    public function kind(): TransactionKind
    {
        return $this->kind;
    }

    public function paymentMethod(): TransactionPaymentMethod
    {
        return $this->paymentMethod;
    }

    public function installments(): ?Installment
    {
        return $this->installments;
    }

    public function isRefund(TransactionType $from): bool
    {
        if ($from->isCheckingAccount()) {
            return session()->get('checking_account_transactions', collect())
                ->some(function (array $transaction): bool {
                    $description = str($transaction['descricaoLancamento'])
                        ->deduplicate()
                        ->value();

                    return str($this->description)->contains($description)
                        && money()->of($transaction['valorLancamento'])->isEqualTo($this->amount)
                        && $transaction['ePositivo'] === true;
                });
        }

        return session()->get('card_transactions', collect())
            ->some(function (array $transaction): bool {
                $description = str($transaction['descricao'])
                    ->deduplicate()
                    ->value();

                return str($this->description)->contains($description)
                    && money()->of($transaction['valor'])->isEqualTo($this->amount)
                    && $transaction['sinalValor'] === '-';
            });
    }
}
