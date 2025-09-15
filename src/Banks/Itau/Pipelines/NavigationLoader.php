<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Pipelines;

use Banklink\Banks\Itau\Actions\Navigation\GetNavigation;
use Banklink\Banks\Itau\Actions\Navigation\LoadNavigation;
use Banklink\Support\PageParser;
use Illuminate\Pipeline\Pipeline;

final class NavigationLoader
{
    public function load(): void
    {
        $menu = app(Pipeline::class)
            ->through([
                LoadNavigation::class,
                GetNavigation::class,
            ])
            ->thenReturn();

        $pageParser = PageParser::make()->html($menu);

        session()->put('card_details_operation', $pageParser->value("a[onclick*='cartoes']", 'data-op'));
        session()->put('checking_account_operation', $pageParser->value("a[onclick*='contaCorrente']", 'data-op'));
    }
}
