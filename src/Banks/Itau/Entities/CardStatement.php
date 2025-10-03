<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Banks\Itau\Actions\Card\GetCardStatements;
use Banklink\Entities;
use Banklink\Entities\StatementPeriod;
use Banklink\Enums\StatementStatus;
use Brick\Money\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class CardStatement extends Entities\CardStatement
{
    public function __construct(
        private readonly string $cardId,
        private readonly StatementStatus $status,
        private readonly Carbon $dueDate,
        private readonly ?Carbon $closingDate,
        private readonly Money $amount,
        private readonly StatementPeriod $period,
        /** @var Collection<int, Holder> */
        private readonly Collection $holders,
    ) {}

    public static function from(string $cardId, array $statement): static
    {
        $bank = config()->get('banklink.bank');

        return new self(
            cardId: $cardId,
            status: StatementStatus::fromString($statement['faturaTimeline']['status']),
            dueDate: $dueDate = Carbon::createFromFormat('Y-m-d', $statement['dataVencimento']),
            closingDate: $dueDate->subDays(config()->integer("banklink.banks.$bank.closing_due_interval_days")),
            amount: money()->of($statement['valorAberto'] ?? 0),
            period: StatementPeriod::fromString($dueDate->format('Y-m')),
            holders: collect($statement['lancamentosNacionais']['titularidades'] ?? [])
                ->merge($statement['comprasParceladas']['titularidades'] ?? [])
                ->map(fn ($holderData): Holder => Holder::from($holderData, $dueDate)),
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

    public function dueDate(): Carbon
    {
        return $this->dueDate;
    }

    public function closingDate(): ?Carbon
    {
        return $this->closingDate;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function period(): StatementPeriod
    {
        return $this->period;
    }

    /** @return Collection<int, Holder> */
    public function holders(): Collection
    {
        return $this->holders;
    }

    /**
     * @return Collection<int, CardStatement>
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(): Collection
    {
        return app()->make(GetCardStatements::class)
            ->byCardId($this->cardId);
    }
}
