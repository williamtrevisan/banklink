<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Navigation;

use Banklink\Banks\Itau\Repositories\Contracts\MenuRepository;
use Closure;

final readonly class LoadNavigation
{
    public function __construct(
        private MenuRepository $menuRepository,
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $this->menuRepository->load(session()->pull('menu_load_operation'));

        return $next($passable);
    }
}
