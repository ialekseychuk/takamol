<?php

namespace App\DTO;

final readonly class PaymentDTO
{
    public function __construct(
        public int $amount,
        public int $account,
    ) {
    }

}
