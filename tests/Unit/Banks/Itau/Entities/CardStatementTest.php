<?php

declare(strict_types=1);

use Banklink\Banks\Itau\Entities\CardStatement;

describe('statement period', function (): void {
    it('correctly identifies statement period', function (): void {
        $transactions = data()
            ->get('card.statements')
            ->collect()
            ->map(fn (array $statement): CardStatement => CardStatement::from('::id::', $statement))
            ->flatMap->holders()
            ->flatMap->transactions();

        expect($transactions)
            ->first()->statementPeriod()->value()->toBe('2025-09')
            ->last()->statementPeriod()->value()->toBe('2025-10');
    });
});
