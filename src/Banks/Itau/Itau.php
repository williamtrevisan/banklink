<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau;

use Banklink\Banks\Itau\Entities\Account;
use Banklink\Banks\Itau\Pipelines\Authenticator;
use Banklink\Banks\Itau\Pipelines\NavigationLoader;
use Banklink\Contracts\Bank;
use Banklink\Entities;

final readonly class Itau implements Bank
{
    public function __construct(
        private Authenticator $authenticator,
        private NavigationLoader $navigationLoader,
    ) {}

    public function authenticate(string $token): static
    {
        $this->authenticator->authenticate($token);

        session()->forget('sign_command_operation');
        session()->forget('anti_pirate_operation');
        session()->forget('guardian_callback_operation');

        $this->navigationLoader->load();

        return $this;
    }

    public function account(): Entities\Account
    {
        return Account::from(config());
    }
}
