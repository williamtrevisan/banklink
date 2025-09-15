<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Authentication;

use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class ProcessPasswordAuthentication
{
    public function __construct(
        private AuthenticationRepository $httpRepository,
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $this->httpRepository->loadPasswordForm();

        $this->httpRepository->executeSignCommand();
        $this->httpRepository->executeAntiPirateCommand();

        $pageParser = PageParser::make()
            ->html($this->httpRepository->fetchGuardianResponse());

        session()->put('submit_password_operation', $pageParser->value('input[name="op"]', 'value'));
        session()->put('letter_password', $this->passwordToLetter($pageParser));

        $homePageParser = PageParser::make()
            ->html($this->httpRepository->submitPassword());

        session()->put('menu_operation', $homePageParser->extract('obterMenu', '/url\s*:\s*"([^"]+)"/'));
        session()->put('menu_load_operation', $homePageParser->value('a#HomeLogo', 'data-op'));

        return $next($passable);
    }

    private function passwordToLetter(PageParser $pageParser): string
    {
        $password = config()->get('banklink.banks.itau.password');
        $mapper = $this->letters($pageParser);

        $letterPassword = '';

        for ($i = 0; $i < mb_strlen((string) $password); $i++) {
            $digit = $password[$i];
            $letterPassword .= $mapper[$digit] ?? '';
        }

        return $letterPassword;
    }

    private function letters(PageParser $pageParser): array
    {
        return array_reduce(
            array_filter(
                $pageParser->elements('.campoTeclado'),
                fn (\Symfony\Component\DomCrawler\Crawler $node): bool => $node->attr('aria-label') && $node->attr('rel'),
            ),
            function (array $mapper, $node): array {
                $letter = str_replace('tecla_', '', $node->attr('rel'));
                $numbers = explode(' ou ', (string) $node->attr('aria-label'));

                foreach ($numbers as $number) {
                    $mapper[mb_trim($number)] = $letter;
                }

                return $mapper;
            },
            initial: [],
        );
    }
}
