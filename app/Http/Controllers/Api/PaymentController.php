<?php

namespace App\Http\Controllers\Api;

use App\DTO\PaymentDTO;
use App\DTO\GetPaymentsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaymentsRequest;
use App\Http\Requests\Api\PaymentRequest;
use App\Http\Resources\Api\PaymentCollection;
use App\Http\Resources\Api\PaymentResource;
use App\Interfaces\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService
    ) {
    }

    public function store(PaymentRequest $request): JsonResponse
    {
        $dto = new PaymentDTO(
            amount: $request->input('amount'),
            account: $request->input('account'),
        );

        $payment = $this->paymentService->pay($dto);

        return new JsonResponse(
            new PaymentResource($payment),
            JsonResponse::HTTP_CREATED
        );
    }

    public function getPaymentsByAccountId(GetPaymentsRequest $request): JsonResponse
    {

        $dto = new GetPaymentsDTO(
            account: $request->input('account'),
            limit: $request->input('limit', 10),
            offset: $request->input('offset', 0)
        );

        $payments = $this->paymentService->getPaymentsByAccountId($dto);

        return new JsonResponse(
           new PaymentCollection( $payments)
        );
    }
}
