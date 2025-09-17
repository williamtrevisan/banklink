<?php

declare(strict_types=1);

namespace Banklink\Support;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Collection;

final readonly class PageParser
{
    public function __construct(private Crawler $crawler) {}

    public static function make(): self
    {
        return new self(new Crawler());
    }

    public function html(string $html = ''): self
    {
        return tap($this, fn () => $this->crawler->add($html));
    }

    public function elements(string $selector): Collection
    {
        $elements = collect();

        $this->crawler
            ->filter($selector)
            ->each(fn ($node) => $elements->push($node));

        return $elements;
    }

    public function extract(string $keyword, string $pattern): string
    {
        return str($this->crawler->filter("script:contains('$keyword')")->text())
            ->match($pattern)
            ->value();
    }

    public function value(string $selector, ?string $attribute = null): string
    {
        $element = $this->crawler->filter($selector);
        if ($element->count() === 0) {
            return '';
        }

        if ($attribute === null || $attribute === '' || $attribute === '0') {
            return $element->text();
        }

        return $element->attr($attribute) ?? '';
    }
}
