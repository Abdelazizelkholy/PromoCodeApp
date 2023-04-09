<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckPromCode;
use App\Models\PromCode;
use App\Models\PromCodeUser;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * @param CheckPromCode $request
     * @return \Illuminate\Http\JsonResponse
     *  Check Promo Code
     */
    public function checkPromoCode(CheckPromCode $request): \Illuminate\Http\JsonResponse
    {

        $input = $request->validated();
        $price = $input['price'];
        $promoCode = $input['promo_code'];

        // Check if the promo code exists in the database
        $promoCode = PromCode::where('code', $promoCode)->first();
        if (!$promoCode) {
            // Promo code not found, return 404 response
            return response()->json(['error' => 'Promo code not found'], 404);
        }

        // Check if the promo code is expired
        if ($promoCode->expiry_date && $promoCode->expiry_date < now()) {
            // Promo code expired, return 404 response
            return response()->json(['error' => 'Promo code has expired'], 404);
        }

        // Check if the promo code is available for the requested user
        $userId = auth()->id(); // Assuming user is authenticated

        if ($promoCode->user_ids && !in_array($userId, json_decode($promoCode->user_ids))) {
            // Promo code not available for the requested user, return 404 response
            return response()->json(['error' => 'Promo code not available for this user'], 404);
        }


        // Check if the promo code has remaining usages
        $usages = PromCodeUser::where('promo_code_id' , $promoCode->id)->count();
        if ($promoCode->max_usages && $usages >= $promoCode->max_usages) {
            // Promo code exceeded max usages, return 404 response
            return response()->json(['error' => 'Promo code has exceeded maximum usages'], 404);
        }

        // Check if the promo code has remaining usages per user
        $userPromoCount = PromCodeUser::where('user_id' , $userId)->count();
        if ($promoCode->max_usages_per_user &&  $userPromoCount >= $promoCode->max_usages_per_user) {
            // Promo code exceeded max usages per user, return 404 response
            return response()->json(['error' => 'Promo code has exceeded maximum usages per user'], 404);
        }

        // Calculate the discounted price based on promo code type and amount
        if ($promoCode->type === 'percentage') {
            $discountedAmount = $price * ($promoCode->amount / 100);
        } else {
            $discountedAmount = $promoCode->amount;
        }

        // Calculate the final price after applying the promo code
        $finalPrice = $price - $discountedAmount;

        // Update promo code usages and usages per user
        PromCodeUser::create([
            'user_id' => $userId,
            'promo_code_id' => $promoCode->id
        ]);

        // Return the response with discounted amount and final price
        return response()->json([
            'price' => $price,
            'promocode_discounted_amount' => $discountedAmount,
            'final_price' => $finalPrice,
        ]);

    }
}
