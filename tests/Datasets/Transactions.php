<?php

declare(strict_types=1);

dataset('checking_account.transactions', [
    [
        [
            'dataLancamento' => '01/08/2025',
            'descricaoLancamento' => 'Programa De Cashback',
            'valorLancamento' => '42,00',
            'ePositivo' => true,
            'indicadorOperacao' => 'debito',
        ],
        [
            'dataLancamento' => '02/07/2025',
            'descricaoLancamento' => 'IOF',
            'valorLancamento' => '1,86',
            'ePositivo' => true,
            'indicadorOperacao' => 'debito',
        ],
        [
            'dataLancamento' => '30/05/2025',
            'descricaoLancamento' => 'INT ITAU BLACK',
            'valorLancamento' => '1195,71',
            'ePositivo' => false,
            'indicadorOperacao' => 'debito',
        ],
        [
            'dataLancamento' => '10/04/2025',
            'descricaoLancamento' => 'PAG BOLETO  PAG TIT BANC',
            'valorLancamento' => '424,05',
            'ePositivo' => false,
            'indicadorOperacao' => 'debito',
        ],
        [
            'dataLancamento' => '02/06/2025',
            'descricaoLancamento' => 'ITAU MC       6902-2590',
            'valorLancamento' => '1195,71',
            'ePositivo' => false,
            'indicadorOperacao' => 'debito',
        ],
        [
            'dataLancamento' => '02/06/2025',
            'descricaoLancamento' => 'EST ITAU MC   6902-2590',
            'valorLancamento' => '1195,71',
            'ePositivo' => true,
            'indicadorOperacao' => 'debito',
        ],
    ],
]);

dataset('checking_account.transactions.cashback', [
    [
        'dataLancamento' => '01/08/2025',
        'descricaoLancamento' => 'Programa De Cashback',
        'valorLancamento' => '42,00',
        'ePositivo' => true,
        'indicadorOperacao' => 'debito',
    ],
]);

dataset('checking_account.transactions.fee', [
    [
        'dataLancamento' => '02/07/2025',
        'descricaoLancamento' => 'IOF',
        'valorLancamento' => '1,86',
        'ePositivo' => true,
        'indicadorOperacao' => 'debito',
    ],
]);

dataset('checking_account.transactions.invoice_payment', [
    [
        'dataLancamento' => '30/05/2025',
        'descricaoLancamento' => 'INT ITAU BLACK',
        'valorLancamento' => '1195,71',
        'ePositivo' => false,
        'indicadorOperacao' => 'debito',
    ],
]);

dataset('checking_account.transactions.purchase', [
    [
        'dataLancamento' => '10/04/2025',
        'descricaoLancamento' => 'PAG BOLETO  PAG TIT BANC',
        'valorLancamento' => '424,05',
        'ePositivo' => false,
        'indicadorOperacao' => 'debito',
    ],
]);

dataset('checking_account.transactions.refund', [
    [
        'dataLancamento' => '02/06/2025',
        'descricaoLancamento' => 'ITAU MC       6902-2590',
        'valorLancamento' => '1195,71',
        'ePositivo' => false,
        'indicadorOperacao' => 'debito',
    ],
    [
        'dataLancamento' => '02/06/2025',
        'descricaoLancamento' => 'EST ITAU MC   6902-2590',
        'valorLancamento' => '1195,71',
        'ePositivo' => true,
        'indicadorOperacao' => 'debito',
    ],
]);
