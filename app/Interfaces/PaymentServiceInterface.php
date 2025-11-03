<?php

namespace App\Interfaces;

use App\DTO\PaymentDTO;
use App\DTO\GetPaymentsDTO;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentServiceInterface
{
    public function pay(PaymentDTO $paymentDTO): Payment;

    /**
     * Get paginated payments by account ID
     *
     * @param GetPaymentsDTO $dto
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaymentsByAccountId(GetPaymentsDTO $dto): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
