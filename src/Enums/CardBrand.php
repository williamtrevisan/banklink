<?php

declare(strict_types=1);

namespace Banklink\Enums;

enum CardBrand: string
{
    case Visa = 'visa';
    case Mastercard = 'mastercard';
}
