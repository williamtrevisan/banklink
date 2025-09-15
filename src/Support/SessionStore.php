<?php

declare(strict_types=1);

namespace Banklink\Support;

final class SessionStore
{
    /** @var array<string, mixed> */
    private static array $data = [];

    public static function put(string $key, mixed $value): void
    {
        self::$data[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$data[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset(self::$data[$key]);
    }

    public static function pull(string $key, mixed $default = null): mixed
    {
        $value = self::get($key, $default);
        self::forget($key);

        return $value;
    }

    public static function forget(string $key): void
    {
        unset(self::$data[$key]);
    }

    public static function flush(): void
    {
        self::$data = [];
    }

    /** @return array<string, mixed> */
    public static function all(): array
    {
        return self::$data;
    }
}
