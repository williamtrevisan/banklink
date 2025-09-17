<?php

declare(strict_types=1);

namespace Banklink\Providers;

use Banklink\Banklink;
use Banklink\Banks\BankManager;
use Banklink\Banks\Itau\Actions\Authentication\InitializeAuthenticationSession;
use Banklink\Banks\Itau\Actions\Authentication\LoadSecurityChallengeComponents;
use Banklink\Banks\Itau\Actions\Authentication\ProcessITokenAuthentication;
use Banklink\Banks\Itau\Actions\Authentication\ProcessPasswordAuthentication;
use Banklink\Banks\Itau\Actions\Authentication\ResolveSecurityChallenges;
use Banklink\Banks\Itau\Actions\Card\GetAllCards;
use Banklink\Banks\Itau\Actions\Card\GetCardDetails;
use Banklink\Banks\Itau\Actions\Navigation\GetNavigation;
use Banklink\Banks\Itau\Actions\Navigation\LoadNavigation;
use Banklink\Banks\Itau\Pipelines\Authenticator;
use Banklink\Banks\Itau\Pipelines\CardsGetter;
use Banklink\Banks\Itau\Pipelines\NavigationLoader;
use Banklink\Contracts\Bank;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(InitializeAuthenticationSession::class);
        $this->app->scoped(LoadSecurityChallengeComponents::class);
        $this->app->scoped(ResolveSecurityChallenges::class);
        $this->app->scoped(ProcessITokenAuthentication::class);
        $this->app->scoped(ProcessPasswordAuthentication::class);
        $this->app->scoped(LoadNavigation::class);
        $this->app->scoped(GetNavigation::class);
        $this->app->scoped(GetCardDetails::class);
        $this->app->scoped(GetAllCards::class);

        $this->app->scoped(Authenticator::class);
        $this->app->scoped(NavigationLoader::class);
        $this->app->scoped(CardsGetter::class);

        $this->app->scoped(Bank::class, fn ($app): Bank => new BankManager($app)->createBankDriver());
        $this->app->scoped('banklink', Banklink::class);
    }
}
