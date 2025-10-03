<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Banks\Itau\Pipelines\CardsGetter;
use Banklink\Entities\Card;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class CardsAccessor implements Contracts\CardsAccessor
{
    /**
     * @return Collection<int, Card>
     *
     * @throws BindingResolutionException
     */
    public function all(): Collection
    {
        $bank = config()->get('banklink.bank');
        $agency = config()->get("banks.$bank.agency");
        $account = config()->get("banks.$bank.account");

        return Cache::remember(
            key: "banklink.$bank.$agency.$account.cards.all",
            ttl: now()->addMonth(),
            callback: fn () => app()->make(CardsGetter::class)->get(),
        );
    }

    /**
     * @throws BindingResolutionException
     */
    public function firstWhere(string $key, mixed $value): ?Card
    {
        return $this->all()
            ->firstWhere(function (Card $card) use ($key, $value): bool {
                if (! method_exists($card, $key)) {
                    return false;
                }

                return $value === $card->{$key}();
            });
    }
}
