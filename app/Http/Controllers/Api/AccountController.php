<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function  store(Request $request)
    {
        return  new JsonResponse(
            new AccountResource(Account::create()),
            Response::HTTP_CREATED
        );
    }

    public  function  get(Account $account): JsonResponse
    {
        return new JsonResponse( new AccountResource($account));
    }
}
