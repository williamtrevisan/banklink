<?php

declare(strict_types=1);

namespace Banklink\Banks;

use Banklink\Contracts\Bank;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

final readonly class BankManager
{
    private Repository $config;

    public function __construct(
        private Container $container
    ) {
        $this->config = $container->make('config');
    }

    public function driver(): string
    {
        return $this->config->get('banklink.bank', '');
    }

    public function createBankDriver(): Bank
    {
        $driver = $this->driver();

        if ($driver === '' || $driver === '0') {
            throw new InvalidArgumentException('No default bank driver is configured.');
        }

        $config = $this->config($driver);

        $bank = ucfirst(mb_strtolower($driver));
        $class = "\\Banklink\\Banks\\$bank\\$bank";
        if (! class_exists($class)) {
            throw new InvalidArgumentException("Bank class [$class] does not exist.");
        }

        if (! is_subclass_of($class, Bank::class)) {
            throw new InvalidArgumentException("Bank class [$class] must implement Bank interface.");
        }

        if (method_exists($class, 'create')) {
            return $class::create($config, $this->container);
        }

        return $this->container->make($class);
    }

    public function config(string $driver): array
    {
        $config = $this->config->get("banklink.banks.$driver");
        if (! $config) {
            throw new InvalidArgumentException("Bank driver [$driver] is not defined.");
        }

        return $config;
    }
}
