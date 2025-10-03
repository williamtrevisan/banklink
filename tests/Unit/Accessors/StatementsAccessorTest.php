<?php

declare(strict_types=1);

use Banklink\Accessors\Contracts\StatementsAccessor;
use Banklink\Entities\CardStatement;
use Illuminate\Support\Collection;

beforeEach(function () {
    cache()->flush();

    config([
        'banklink.bank' => 'itau',
        'banks.itau.agency' => '9999',
        'banks.itau.account' => '99999',
    ]);
});

it('caches statements for one month', function () {
    $statements = collect([
        $this->mock(CardStatement::class),
        $this->mock(CardStatement::class),
    ]);

    $invocations = 0;

    $accessor = new class($statements, $invocations) implements StatementsAccessor
    {
        public function __construct(
            private Collection $statements,
            private int &$invocations
        ) {}

        public function all(): Collection
        {
            $bank = config('banklink.bank');
            $agency = config("banks.$bank.agency");
            $account = config("banks.$bank.account");

            return cache()->remember(
                key: "banklink.$bank.$agency.$account.statements.all",
                ttl: now()->addMonth(),
                callback: function () {
                    $this->invocations++;

                    return $this->statements;
                },
            );
        }
    };

    expect($accessor->all())
        ->toBe($statements)
        ->and($accessor->all())->toBe($statements)
        ->and(cache()->has('banklink.itau.9999.99999.statements.all'))->toBeTrue()
        ->and($invocations)->toBe(1);
});

it('uses account-specific cache keys', function () {
    $statements = collect([$this->mock(CardStatement::class)]);
    $invocations = 0;

    $accessor = new class($statements, $invocations) implements StatementsAccessor
    {
        public function __construct(
            private Collection $statements,
            private int &$invocations
        ) {}

        public function all(): Collection
        {
            $bank = config('banklink.bank');
            $agency = config("banks.$bank.agency");
            $account = config("banks.$bank.account");

            return cache()->remember(
                key: "banklink.$bank.$agency.$account.statements.all",
                ttl: now()->addMonth(),
                callback: function () {
                    $this->invocations++;

                    return $this->statements;
                },
            );
        }
    };

    $accessor->all();
    expect(cache()->has('banklink.itau.9999.99999.statements.all'))
        ->toBeTrue()
        ->and($invocations)->toBe(1);

    config(['banks.itau.account' => '88888']);

    $accessor->all();
    expect(cache()->has('banklink.itau.9999.88888.statements.all'))
        ->toBeTrue()
        ->and($invocations)->toBe(2);
});
