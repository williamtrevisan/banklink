<?php

declare(strict_types=1);

use Tests\Support\Data;
use Tests\TestCase;

uses(TestCase::class)
    ->in(__DIR__);

function data(array $items = []): Data
{
    return new Data($items);
}

function dataset_get(string $dataset): array
{
    return (new Data)
        ->get($dataset)
        ->items();
}
