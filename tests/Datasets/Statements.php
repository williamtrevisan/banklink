<?php

declare(strict_types=1);

dataset('card.statements', [
    [
        [
            'status' => 'fechadaPagaTotal',
            'descricao' => 'fatura fechada',
            'dataVencimento' => '2025-09-01',
            'dataMelhorCompra' => null,
            'debitoAutomatico' => false,
            'dataFechamentoFatura' => '2025-08-25',
            'lancamentosNacionais' => [
                'titularidades' => [
                    [
                        'totalTitularidade' => '2.137,55',
                        'sinalTotalTitularidade' => '+',
                        'nomeCliente' => 'WILLIAM TREVISAN - final 0838',
                        'numeroCartao' => '0838',
                        'sinalTransacoesInternacionais' => null,
                        'totalTransacoesInternacionais' => null,
                        'valorTotalSaqueInternacional' => null,
                        'sinalRepasseIOF' => null,
                        'repasseIOF' => null,
                        'lancamentos' => [
                            [
                                'data' => '2025-06-24',
                                'descricao' => 'Giuliana Market In02/03',
                                'valor' => '42,90',
                                'sinalValor' => '+',
                                'valorPrincipal' => null,
                                'sinalValorPrincipal' => null,
                                'valorJuros' => null,
                                'sinalValorJuros' => null,
                                'valorCotacaoDolar' => null,
                                'sinalValorCotacaoDolar' => null,
                            ],
                        ],
                    ],
                ],
            ],
            'comprasParceladas' => null,
        ],
        [
            'status' => 'aberta',
            'descricao' => 'fatura aberta',
            'dataVencimento' => '2025-10-01',
            'dataMelhorCompra' => '2025-09-25',
            'debitoAutomatico' => false,
            'dataFechamentoFatura' => '2025-09-24',
            'lancamentosNacionais' => [
                'titularidades' => [
                    [
                        'totalTitularidade' => '1.577,84',
                        'sinalTotalTitularidade' => '+',
                        'nomeCliente' => 'WILLIAM TREVISAN - final 0838',
                        'numeroCartao' => '0838',
                        'sinalTransacoesInternacionais' => null,
                        'totalTransacoesInternacionais' => null,
                        'valorTotalSaqueInternacional' => null,
                        'sinalRepasseIOF' => null,
                        'repasseIOF' => null,
                        'lancamentos' => [
                            [
                                'data' => '2025-06-24',
                                'descricao' => 'Giuliana Market In03/03',
                                'valor' => '42,90',
                                'sinalValor' => '+',
                                'valorPrincipal' => null,
                                'sinalValorPrincipal' => null,
                                'valorJuros' => null,
                                'sinalValorJuros' => null,
                                'valorCotacaoDolar' => null,
                                'sinalValorCotacaoDolar' => null,
                            ],
                        ],
                    ],
                ],
            ],
            'comprasParceladas' => null,
        ],
    ],
]);
