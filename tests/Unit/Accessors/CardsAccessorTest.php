<?php

declare(strict_types=1);

use Banklink\Accessors\Contracts\CardsAccessor;
use Banklink\Entities\Card;
use Illuminate\Support\Collection;

beforeEach(function () {
    cache()->flush();

    config([
        'banklink.bank' => 'itau',
        'banks.itau.agency' => '9999',
        'banks.itau.account' => '99999',
    ]);
});

it('caches cards for one month', function () {
    $visa = $this->mock(Card::class);
    $master = $this->mock(Card::class);
    $cards = collect([$visa, $master]);

    $accessor = new class($cards) implements CardsAccessor {
        public function __construct(private Collection $cards)
        {
        }

        public function all(): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            return cache()->remember(
                key: "banklink.$bank.$agency.$account.cards.all",
                ttl: now()->addMonth(),
                callback: fn () => $this->cards,
            );
        }

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
    };

    expect($accessor->all())
        ->toBe($cards)
        ->and($accessor->all())->toBe($cards)
        ->and(cache()->has('banklink.itau.9999.99999.cards.all'))->toBeTrue();
});

it('uses account-specific cache keys', function () {
    $card = $this->mock(Card::class);
    $cards = collect([$card]);

    $accessor = new class($cards) implements CardsAccessor {
        public function __construct(private Collection $cards)
        {
        }

        public function all(): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            return cache()->remember(
                key: "banklink.$bank.$agency.$account.cards.all",
                ttl: now()->addMonth(),
                callback: fn () => $this->cards,
            );
        }

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
    };

    $accessor->all();
    expect(cache()->has('banklink.itau.9999.99999.cards.all'))->toBeTrue();

    config(['banks.itau.account' => '88888']);
    $accessor->all();
    expect(cache()->has('banklink.itau.9999.88888.cards.all'))->toBeTrue();
});

it('firstWhere uses cached cards', function () {
    $visa = $this->mock(Card::class)
        ->allows('id')
        ->andReturn('visa')
        ->getMock();

    $master = $this->mock(Card::class)
        ->allows('id')
        ->andReturn('master')
        ->getMock();

    $cards = collect([$visa, $master]);
    $invocations = 0;

    $accessor = new class($cards, $invocations) implements CardsAccessor {
        public function __construct(
            private Collection $cards,
            private int &$invocations
        ) {
        }

        public function all(): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            return cache()->remember(
                key: "banklink.$bank.$agency.$account.cards.all",
                ttl: now()->addMonth(),
                callback: function () {
                    $this->invocations++;
                    return $this->cards;
                },
            );
        }

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
    };

    expect($accessor->firstWhere('id', 'master'))
        ->toBe($master)
        ->and($accessor->firstWhere('id', 'visa'))->toBe($visa)
        ->and($invocations)->toBe(1);
});
