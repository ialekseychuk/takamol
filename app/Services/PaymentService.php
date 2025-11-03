<?php

namespace App\Services;

use App\DTO\PaymentDTO;
use App\DTO\GetPaymentsDTO;
use App\Interfaces\PaymentServiceInterface;
use App\Models\Account;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PaymentService implements PaymentServiceInterface
{
    public function pay(PaymentDTO $paymentDTO): Payment
    {
        $account = Account::findOrFail($paymentDTO->account);

        return DB::transaction(function () use ($paymentDTO, $account) {
            $payment = Payment::create([
                'account_id' => $account->id,
                'amount' => $paymentDTO->amount,
            ]);

            $account->increment('balance', $paymentDTO->amount);

            return $payment;
        });
    }

    public function getPaymentsByAccountId(GetPaymentsDTO $dto): LengthAwarePaginator
    {
        return Payment::query()
            ->where('account_id', $dto->account)
            ->with('account')
            ->orderBy('created_at', 'desc')
            ->paginate(
                perPage: $dto->limit,
                page: ($dto->offset / $dto->limit) + 1
            );
    }
}
