<?php

declare(strict_types=1);

use Banklink\Accessors\CardsAccessor;
use Banklink\Accessors\StatementsAccessor;
use Banklink\Accessors\TransactionsAccessor;
use Banklink\Banks\Itau\Entities\Account;
use Banklink\Banks\Itau\Entities\Card;
use Banklink\Banks\Itau\Entities\CardStatement;
use Banklink\Banks\Itau\Entities\Transaction;
use Banklink\Banks\Itau\Itau;
use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;
use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Banklink\Banks\Itau\Repositories\Contracts\MenuRepository;
use Banklink\Contracts\Bank;
use Banklink\Entities;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

beforeEach(function (): void {
    $headers = json_decode(file_get_contents('tests/Fixtures/Authentication/pre-login-headers.json'), true);

    $response = $this->mock(Response::class);
    $response
        ->expects('body')
        ->andReturn(file_get_contents('tests/Fixtures/Authentication/pre-login.html'));
    $response
        ->expects('header')
        ->with('x-auth-token')
        ->andReturn($headers['X-Auth-Token']);
    $response
        ->expects('header')
        ->with('x-flow-id')
        ->andReturn($headers['X-FLOW-ID']);
    $response
        ->expects('header')
        ->with('x-client-id')
        ->andReturn($headers['X-CLIENT-ID']);

    $authenticationRepository = $this->mock(AuthenticationRepository::class);
    $authenticationRepository
        ->expects('initializeSession')
        ->andReturn($response);
    $authenticationRepository->expects('loadSecurityChallenge');
    $authenticationRepository->expects('confirmChallengeReady');
    $authenticationRepository
        ->expects('fetchChallengeTokens')
        ->andReturn(file_get_contents('tests/Fixtures/Authentication/challenge-tokens.html'));
    $authenticationRepository
        ->expects('fetchGuardianResponse')
        ->twice()
        ->andReturnUsing(function (): string|false {
            static $callCount;

            if (++$callCount === 1) {
                return file_get_contents('tests/Fixtures/Authentication/itoken-guardian.html');
            }

            return file_get_contents('tests/Fixtures/Authentication/password-guardian.html');
        });
    $authenticationRepository
        ->expects('loadITokenForm')
        ->andReturn(file_get_contents('tests/Fixtures/Authentication/itoken-form.html'));
    $authenticationRepository->expects('submitIToken');
    $authenticationRepository->expects('loadPasswordForm');
    $authenticationRepository->expects('executeSignCommand');
    $authenticationRepository->expects('executeAntiPirateCommand');
    $authenticationRepository
        ->expects('submitPassword')
        ->andReturn(file_get_contents('tests/Fixtures/Authentication/home.html'));

    $menuRepository = $this->mock(MenuRepository::class);
    $menuRepository->expects('load');
    $menuRepository
        ->expects('get')
        ->andReturn(file_get_contents('tests/Fixtures/Navigation/navigation.html'));

    config()->set('banklink.banks.itau.agency', '9999');
    config()->set('banklink.banks.itau.account', '99999');
    config()->set('banklink.banks.itau.account_digit', '9');
});

it('can authenticate', function (): void {
    $bank = app()->make(Bank::class)
        ->authenticate(token: '::token::');

    expect($bank)
        ->toBeInstanceOf(Bank::class)
        ->toBeInstanceOf(Itau::class)
        ->and(session()->has('auth_token'))->toBeTrue()
        ->and(session()->has('flow_id'))->toBeTrue()
        ->and(session()->has('client_id'))->toBeTrue()
        ->and(session()->has('card_details_operation'))->toBeTrue()
        ->and(session()->has('checking_account_operation'))->toBeTrue();
});

it('can find and return the account', function (): void {
    $account = app()->make(Bank::class)
        ->authenticate(token: '::token::')
        ->account();

    expect($account)
        ->toBeInstanceOf(Account::class);
});

describe('transactions accessor', function (): void {
    it('can mount the accessor', function (): void {
        $transactions = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->transactions();

        expect($transactions)
            ->toBeInstanceOf(TransactionsAccessor::class);
    });

    it('can find and return all transaction between dates', function (): void {
        $checkingAccountRepository = $this->mock(CheckingAccountRepository::class);
        $checkingAccountRepository
            ->expects('navigation')
            ->andReturn(file_get_contents('tests/Fixtures/CheckingAccount/navigation.html'));
        $checkingAccountRepository
            ->expects('subNavigation')
            ->andReturn(file_get_contents('tests/Fixtures/CheckingAccount/sub-navigation.html'));
        $checkingAccountRepository
            ->expects('statements')
            ->andReturn(file_get_contents('tests/Fixtures/CheckingAccount/statements.html'));
        $checkingAccountRepository
            ->expects('transactionsFrom')
            ->andReturnUsing(function (): Collection {
                $contents = file_get_contents('tests/Fixtures/CheckingAccount/statement.json');

                $statement = json_decode($contents, true);
                $transactions = $statement['lancamentos'];

                return collect($transactions)
                    ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
                    ->reject(fn (array $transaction): bool => is_null($transaction['dataLancamento']))
                    ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));
            });

        $transactions = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->transactions()
            ->between(Carbon::parse('2025-05-25'), Carbon::parse('2025-06-24'));

        expect($transactions)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(29)
            ->each->toBeInstanceOf(Transaction::class);
    });
});

describe('cards accessor', function (): void {
    it('can mount the accessor', function (): void {
        $cards = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->cards();

        expect($cards)
            ->toBeInstanceOf(CardsAccessor::class);
    });

    it('can find and return the account cards', function (): void {
        $cardRepository = $this->mock(CardRepository::class);
        $cardRepository
            ->expects('details')
            ->andReturn(file_get_contents('tests/Fixtures/Card/details.html'));
        $cardRepository
            ->expects('all')
            ->andReturn(
                collect(json_decode(file_get_contents('tests/Fixtures/Card/cards.json'), true))
                    ->map(fn (array $card): Card => Card::from($card))
            );

        $cards = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->cards()
            ->all();

        expect($cards)
            ->toBeInstanceOf(Collection::class)
            ->each->toBeInstanceOf(Entities\Card::class)
            ->and(session()->has('card_statement_operation'))->toBeTrue()
            ->and(session()->has('card_operation'))->toBeTrue();
    });

    it('can find and return the a specific card', function (): void {
        $cardRepository = $this->mock(CardRepository::class);
        $cardRepository
            ->expects('details')
            ->andReturn(file_get_contents('tests/Fixtures/Card/details.html'));
        $cardRepository
            ->expects('all')
            ->andReturn(
                collect(json_decode(file_get_contents('tests/Fixtures/Card/cards.json'), true))
                    ->map(fn (array $card): Card => Card::from($card))
            );

        $cards = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->cards()
            ->firstWhere('name', 'UNICLASS BLACK CASHBACK');

        expect($cards)
            ->toBeInstanceOf(Entities\Card::class)
            ->and(session()->has('card_statement_operation'))->toBeTrue()
            ->and(session()->has('card_operation'))->toBeTrue();
    });
});

describe('statements accessor', function (): void {
    it('can mount the accessor', function (): void {
        $cardRepository = $this->mock(CardRepository::class);
        $cardRepository
            ->expects('details')
            ->andReturn(file_get_contents('tests/Fixtures/Card/details.html'));
        $cardRepository
            ->expects('all')
            ->andReturn(
                collect(json_decode(file_get_contents('tests/Fixtures/Card/cards.json'), true))
                    ->map(fn (array $card): Card => Card::from($card))
            );

        $statements = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->cards()
            ->firstWhere('name', 'UNICLASS BLACK CASHBACK')
            ->statements();

        expect($statements)
            ->toBeInstanceOf(StatementsAccessor::class);
    });

    it('can find and return all statements', function (): void {
        $cardRepository = $this->mock(CardRepository::class);
        $cardRepository
            ->expects('details')
            ->andReturn(file_get_contents('tests/Fixtures/Card/details.html'));
        $cardRepository
            ->expects('all')
            ->andReturn(
                collect(json_decode(file_get_contents('tests/Fixtures/Card/cards.json'), true))
                    ->map(fn (array $card): Card => Card::from($card))
            );
        $cardRepository
            ->expects('statementBy')
            ->andReturnUsing(function (): Collection {
                $contents = file_get_contents('tests/Fixtures/Card/statements.json');

                $statements = json_decode($contents, true)['object'];
                $cardId = $statements['id'];

                return collect($statements['faturas'])
                    ->map(fn (array $statement): CardStatement => CardStatement::from($cardId, $statement));
            });

        $statements = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->cards()
            ->firstWhere('name', 'UNICLASS BLACK CASHBACK')
            ->statements()
            ->all();

        expect($statements)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(8)
            ->each->toBeInstanceOf(Entities\CardStatement::class);
    });

    it('can find and return all statements between dates', function (): void {
        $cardRepository = $this->mock(CardRepository::class);
        $cardRepository
            ->expects('details')
            ->andReturn(file_get_contents('tests/Fixtures/Card/details.html'));
        $cardRepository
            ->expects('all')
            ->andReturn(
                collect(json_decode(file_get_contents('tests/Fixtures/Card/cards.json'), true))
                    ->map(fn (array $card): Card => Card::from($card))
            );
        $cardRepository
            ->expects('statementBy')
            ->andReturnUsing(function (): Collection {
                $contents = file_get_contents('tests/Fixtures/Card/statements.json');

                $statements = json_decode($contents, true)['object'];
                $cardId = $statements['id'];

                return collect($statements['faturas'])
                    ->map(fn (array $statement): CardStatement => CardStatement::from($cardId, $statement));
            });

        $statements = app()->make(Bank::class)
            ->authenticate(token: '::token::')
            ->account()
            ->cards()
            ->firstWhere('name', 'UNICLASS BLACK CASHBACK')
            ->statements()
            ->byPeriod('2025-06');

        expect($statements)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(1)
            ->each->toBeInstanceOf(Entities\CardStatement::class);
    });
});
