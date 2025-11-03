<?php

namespace App\DTO;

final readonly class GetPaymentsDTO
{
    public function __construct(
        public int $account,
        public ?int $limit = 10,
        public ?int $offset = 0
    ) {
    }
}
