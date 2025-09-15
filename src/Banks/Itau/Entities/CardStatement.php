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
    public function __construct(
        private readonly string $cardId,
        private readonly StatementStatus $status,
        private readonly DateTimeImmutable $dueDate,
        private readonly ?DateTimeImmutable $closingDate,
        private readonly string $amount,
        private readonly string $period,
        /** @var Holder[] */
        private readonly array $holders,
    ) {}

    public static function from(string $cardId, array $statement): static
    {
        return new self(
            cardId: $cardId,
            status: str_contains((string) $statement['status'], 'fechada') ? StatementStatus::Closed : StatementStatus::Open,
            dueDate: DateTimeImmutable::createFromFormat('Y-m-d', $statement['dataVencimento']) ?: new DateTimeImmutable(),
            closingDate: isset($statement['dataFechamentoFatura'])
                ? DateTimeImmutable::createFromFormat('Y-m-d', $statement['dataFechamentoFatura']) ?: null
                : null,
            amount: $statement['valorAberto'] ?? '',
            period: (DateTimeImmutable::createFromFormat('Y-m-d', $statement['dataVencimento']) ?: new DateTimeImmutable())
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

    public function cardId(): string
    {
        return $this->cardId;
    }

    public function status(): StatementStatus
    {
        return $this->status;
    }

    public function dueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function closingDate(): ?DateTimeImmutable
    {
        return $this->closingDate;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function period(): string
    {
        return $this->period;
    }

    /** @return Holder[] */
    public function holders(): array
    {
        return $this->holders;
    }

    /**
     * @return CardStatement[]
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(): array
    {
        return app()->make(GetCardStatements::class)
            ->byCardId($this->cardId);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function byPeriod(string $period): array
    {
        return array_filter($this->all(), fn (CardStatement $statement): bool => $statement->period() === $period);
    }
}
