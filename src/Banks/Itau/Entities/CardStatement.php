<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Banks\Itau\Actions\Card\GetCardStatements;
use Banklink\Entities;
use Banklink\Enums\StatementStatus;
use DateInterval;
use DateTimeImmutable;

final class CardStatement extends Entities\CardStatement
{
    public static function from(string $cardId, array $statement): static
    {
        return new self(
            cardId: $cardId,
            status: str_contains((string) $statement['status'], 'fechada') ? StatementStatus::Closed : StatementStatus::Open,
            dueDate: DateTimeImmutable::createFromFormat('Y-m-d', $statement['dataVencimento']),
            closingDate: isset($statement['dataFechamentoFatura'])
                ? DateTimeImmutable::createFromFormat('Y-m-d', $statement['dataFechamentoFatura'])
                : null,
            amount: $statement['valorAberto'] ?? '',
            period: DateTimeImmutable::createFromFormat('Y-m-d', $statement['dataVencimento'])
                ->sub(new DateInterval('P1M'))
                ->format('Y-m'),
            holders: array_map(
                fn ($holderData): Holder => Holder::from($holderData),
                array_merge(
                    $statement['lancamentosNacionais']['titularidades'] ?? [],
                    $statement['comprasParceladas']['titularidades'] ?? [],
                )
            ),
        );
    }

    /**
     * @return CardStatement[]
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(): array
    {
        return app()->make(GetCardStatements::class)
            ->byCardId($this->cardId());
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function byPeriod(string $period): array
    {
        return array_filter($this->all(), fn (CardStatement $statement): bool => $statement->period() === $period);
    }
}
