<?php

declare(strict_types=1);

use Banklink\Accessors\Contracts\TransactionsAccessor;
use Banklink\Entities\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

beforeEach(function () {
    cache()->flush();

    config([
        'banklink.bank' => 'itau',
        'banks.itau.agency' => '9999',
        'banks.itau.account' => '99999',
    ]);
});

it('caches transactions between dates for thirty minutes', function () {
    $start = Carbon::parse('2025-01-01 00:00:00');
    $end = Carbon::parse('2025-01-31 23:59:59');

    $transactions = collect([
        $this->mock(Transaction::class),
        $this->mock(Transaction::class),
    ]);

    $accessor = new class($transactions) implements TransactionsAccessor
    {
        public function __construct(private Collection $transactions) {}

        public function between(Carbon $start, Carbon $end): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            $cacheKey = "banklink.$bank.$agency.$account.transactions.between.".md5(serialize([
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s'),
            ]));

            return cache()->remember($cacheKey, 1800, fn () => $this->transactions);
        }

        public function today(): Collection {}
    };

    $cacheKey = 'banklink.itau.9999.99999.transactions.between.'.md5(serialize([
        $start->format('Y-m-d H:i:s'),
        $end->format('Y-m-d H:i:s'),
    ]));

    expect($accessor->between($start, $end))
        ->toBe($transactions)
        ->and($accessor->between($start, $end))->toBe($transactions)
        ->and(cache()->has($cacheKey))->toBeTrue();
});

it('caches today transactions for ten minutes', function () {
    Carbon::setTestNow('2025-01-15 14:30:00');

    $transactions = collect([$this->mock(Transaction::class)]);

    $accessor = new class($transactions) implements TransactionsAccessor
    {
        public function __construct(private Collection $transactions) {}

        public function between(Carbon $start, Carbon $end): Collection {}

        public function today(): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            $cacheKey = "banklink.$bank.$agency.$account.transactions.today.".md5(serialize([now()->format('Y-m-d')]));

            return cache()->remember($cacheKey, 600, fn () => $this->transactions);
        }
    };

    $cacheKey = 'banklink.itau.9999.99999.transactions.today.'.md5(serialize([now()->format('Y-m-d')]));

    expect($accessor->today())
        ->toBe($transactions)
        ->and($accessor->today())->toBe($transactions)
        ->and(cache()->has($cacheKey))->toBeTrue();

    Carbon::setTestNow();
});

it('creates different cache keys for different date ranges', function () {
    $range1Start = Carbon::parse('2025-01-01');
    $range1End = Carbon::parse('2025-01-15');

    $range2Start = Carbon::parse('2025-01-16');
    $range2End = Carbon::parse('2025-01-31');

    $accessor = new class implements TransactionsAccessor
    {
        public function between(Carbon $start, Carbon $end): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            $cacheKey = "banklink.$bank.$agency.$account.transactions.between.".md5(serialize([
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s'),
            ]));

            return cache()->remember($cacheKey, 1800, fn () => collect([]));
        }

        public function today(): Collection {}
    };

    $accessor->between($range1Start, $range1End);
    $accessor->between($range2Start, $range2End);

    $cacheKey1 = 'banklink.itau.9999.99999.transactions.between.'.md5(serialize([
        $range1Start->format('Y-m-d H:i:s'),
        $range1End->format('Y-m-d H:i:s'),
    ]));

    $cacheKey2 = 'banklink.itau.9999.99999.transactions.between.'.md5(serialize([
        $range2Start->format('Y-m-d H:i:s'),
        $range2End->format('Y-m-d H:i:s'),
    ]));

    expect(cache()->has($cacheKey1))
        ->toBeTrue()
        ->and(cache()->has($cacheKey2))->toBeTrue()
        ->and($cacheKey1)->not->toBe($cacheKey2);
});

it('uses account-specific cache keys for transactions', function () {
    $start = Carbon::parse('2025-01-01');
    $end = Carbon::parse('2025-01-31');

    $accessor = new class implements TransactionsAccessor
    {
        public function between(Carbon $start, Carbon $end): Collection
        {
            $bank = config()->get('banklink.bank');
            $agency = config()->get("banks.$bank.agency");
            $account = config()->get("banks.$bank.account");

            $cacheKey = "banklink.$bank.$agency.$account.transactions.between.".md5(serialize([
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s'),
            ]));

            return cache()->remember($cacheKey, 1800, fn () => collect());
        }

        public function today(): Collection {}
    };

    $accessor->between($start, $end);

    $cacheKey = 'banklink.itau.9999.99999.transactions.between.'.md5(serialize([
        $start->format('Y-m-d H:i:s'),
        $end->format('Y-m-d H:i:s'),
    ]));

    expect(cache()->has($cacheKey))
        ->toBeTrue();
});
