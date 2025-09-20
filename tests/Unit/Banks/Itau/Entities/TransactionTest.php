<?php

declare(strict_types=1);

use Banklink\Banks\Itau\Entities\Transaction;
use Banklink\Enums\TransactionKind;
use Illuminate\Support\Collection;

beforeEach(function (): void {
    config([
        'banklink.bank' => 'itau',
        'banklink.banks.itau.classifiers' => [
            Tests\Stubs\Banks\Itau\Classifiers\CashbackTransactionClassifier::class,
            Tests\Stubs\Banks\Itau\Classifiers\FeeTransactionClassifier::class,
            Tests\Stubs\Banks\Itau\Classifiers\InvoicePaymentTransactionClassifier::class,
        ],
    ]);
});

describe('transaction kind', function (): void {
    describe('cashback transactions', function (): void {
        it('correctly identifies cashback transactions from multiple', function (): void {
            $transactions = data()
                ->get('checking_account.transactions')
                ->collect()
                ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));

            expect($transactions)
                ->toHaveCount(6)
                ->where(fn (Transaction $transaction): bool => $transaction->kind() === TransactionKind::Cashback)
                ->toHaveCount(1);
        });

        it('correctly identifies cashback transaction from single', function (): void {
            $transaction = dataset_get('checking_account.transactions.cashback');

            expect(Transaction::fromCheckingAccountTransaction($transaction))
                ->kind()->toBe(TransactionKind::Cashback);
        });
    });

    describe('fee transactions', function (): void {
        it('correctly identifies fee transactions from multiple', function (): void {
            $transactions = data()
                ->get('checking_account.transactions')
                ->collect()
                ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));

            expect($transactions)
                ->toHaveCount(6)
                ->where(fn (Transaction $transaction): bool => $transaction->kind() === TransactionKind::Fee)
                ->toHaveCount(1);
        });

        it('correctly identifies fee transaction from single', function (): void {
            $transaction = dataset_get('checking_account.transactions.fee');

            expect(Transaction::fromCheckingAccountTransaction($transaction))
                ->kind()->toBe(TransactionKind::Fee);
        });
    });

    describe('invoice payment transactions', function (): void {
        it('correctly identifies invoice payment transactions from multiple', function (): void {
            $transactions = data()
                ->get('checking_account.transactions')
                ->collect()
                ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));

            expect($transactions)
                ->toHaveCount(6)
                ->where(fn (Transaction $transaction): bool => $transaction->kind() === TransactionKind::InvoicePayment)
                ->toHaveCount(2);
        });

        it('correctly identifies invoice payment transaction from single', function (): void {
            $transaction = dataset_get('checking_account.transactions.invoice_payment');

            expect(Transaction::fromCheckingAccountTransaction($transaction))
                ->kind()->toBe(TransactionKind::InvoicePayment);
        });
    });

    describe('purchase transactions', function (): void {
        it('correctly identifies purchase transactions from multiple', function (): void {
            $transactions = data()
                ->get('checking_account.transactions')
                ->collect()
                ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));

            expect($transactions)
                ->toHaveCount(6)
                ->where(fn (Transaction $transaction): bool => $transaction->kind() === TransactionKind::Purchase)
                ->toHaveCount(1);
        });

        it('correctly identifies purchase transaction from single', function (): void {
            $transaction = dataset_get('checking_account.transactions.purchase');

            expect(Transaction::fromCheckingAccountTransaction($transaction))
                ->kind()->toBe(TransactionKind::Purchase);
        });
    });

    describe('refund transactions', function (): void {
        it('correctly identifies refund transactions from multiple', function (): void {
            $transactions = data()
                ->get('checking_account.transactions')
                ->collect()
                ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));

            expect($transactions)
                ->toHaveCount(6)
                ->where(fn (Transaction $transaction): bool => $transaction->kind() === TransactionKind::Refund)
                ->toHaveCount(1);
        });

        it('correctly identifies refund transaction from single', function (): void {
            $transaction = data()
                ->get('checking_account.transactions.refund')
                ->collect()
                ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction))
                ->last();

            expect($transaction)
                ->kind()->toBe(TransactionKind::Refund);
        });
    });
});
