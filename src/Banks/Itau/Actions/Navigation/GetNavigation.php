<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Navigation;

use Banklink\Banks\Itau\Repositories\Contracts\MenuRepository;

final readonly class GetNavigation
{
    public function __construct(
        private MenuRepository $menuRepository,
    ) {}

    public function handle(): string
    {
        return $this->menuRepository->get(session()->pull('menu_operation'));
    }
}
