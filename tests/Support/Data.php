<?php

declare(strict_types=1);

namespace Tests\Support;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pest\Repositories\DatasetsRepository;

final class Data
{
    public function __construct(public array $items = []) {}

    public function get(string $dataset): self
    {
        $this->items = Arr::first(DatasetsRepository::resolve([$dataset], __FILE__));

        return $this;
    }

    public function transform(?string $key, ?Closure $callback = null): self
    {
        $transformed = $callback($this->collect($key));

        match (true) {
            $transformed instanceof Collection => $this->items = $transformed->all(),
            is_array($transformed) => $this->items = $transformed,
            default => $this->items,
        };

        return $this;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function collect(?string $key = null): Collection
    {
        if (is_null($key)) {
            return collect($this->items);
        }

        return collect(data_get($this->items, $key));
    }

    public function dd(mixed ...$args): never
    {
        dd($this->items, ...$args);
    }
}
