<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromoCode;
use App\Models\PromCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{

    /**
     * @param StorePromoCode $request
     * @return \Illuminate\Http\JsonResponse
     *  Create Promo Codde
     */
    public function create(StorePromoCode $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->validated();
        // Retrieve data from the API request
        $code = Str::random(8);
        $expiryDate = $input['expiry_date'];
        $maxUsages = $input['max_usages'];
        $maxUsagesPerUser = $input['max_usages_per_user'];
        $userIds = $input['user_ids'];
        $type = $input['type'];
        $amount = $input['amount'];
        // Create a new promo code in the database
        $promoCode = PromCode::create([
            'code' => $code,
            'expiry_date' => $expiryDate,
            'max_usages' => $maxUsages,
            'max_usages_per_user' => $maxUsagesPerUser,
            'user_ids' => json_encode($userIds),
            'type' => $type,
            'amount' => $amount
        ]);

        // Return the created promo code as JSON response
        return response()->json(['promo_code' => $promoCode], 200);
    }

}
