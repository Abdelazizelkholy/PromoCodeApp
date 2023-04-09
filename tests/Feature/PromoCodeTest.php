<?php

namespace Tests\Feature;

use App\Http\Requests\StorePromoCode;
use App\Models\PromCode;
use App\Models\PromCodeUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test storing a promo code.
     *
     * @return void
     */
    public function testStorePromoCode()
    {

        // Generate fake promo code data
        $promoCodeData = [
            'code' => $this->faker->unique()->bothify('??###??'),
            'expiry_date' => now()->addDays(7),
            'max_usages' => 100,
            'max_usages_per_user' => 5,
            'user_ids' => json_encode([1, 2, 3]),
            'type' => 'percentage',
            'amount' => 20,
        ];

        // Create a fake StorePromoCodeRequest with the promo code data
       StorePromoCode::create('/api/promo_codes', 'POST', $promoCodeData);



        // Call the storePromoCode method on the PromoCode model to store the promo code
        $promoCode = PromCode::create($promoCodeData);

        // Assert the promo code is stored in the database
        $this->assertDatabaseHas('prom_codes', [
            'code' => $promoCodeData['code'],
            'expiry_date' => $promoCodeData['expiry_date'],
            'max_usages' => $promoCodeData['max_usages'],
            'max_usages_per_user' => $promoCodeData['max_usages_per_user'],
            'user_ids' => json_decode($promoCodeData['user_ids']),
            'type' => $promoCodeData['type'],
            'amount' => $promoCodeData['amount'],
        ]);

        // Assert the promo code model is returned
        $this->assertInstanceOf(PromoCode::class, $promoCode);
    }


    public function testStorePromoCodeAndCheckApplied()
    {

        $user = User::factory()->create();
        // Create a new promo code
        $promoCode = PromCode::factory()->create();

        $this->actingAs($user);

        // Generate a fake price and promo code
        $price = $this->faker->randomFloat(2, 1, 100);
        $promoCodeValue = $promoCode->code;

        // Make a POST request to the store promo code endpoint
        $response = $this->post('http://127.0.0.1:8000/api/promo_codes/check', [
            'price' => $price,
            'promo_code' => $promoCodeValue,
        ]);



        // Assert that the response has a 200 status code
        $response->assertStatus(200);


        // Assert that a PromCodeUser record is created for the authenticated user
        $this->assertDatabaseHas('prom_code_users', [
            'user_id' => auth()->id(),
            'promo_code_id' => $promoCode->id,
        ]);

        // Assert that the promo code usages and usages per user are updated
        $this->assertEquals(1, PromCodeUser::where('promo_code_id', $promoCode->id)->count());
        $this->assertEquals(1, PromCodeUser::where('user_id', auth()->id())->count());
    }

}
