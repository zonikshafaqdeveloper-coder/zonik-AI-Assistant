<?php

namespace Tests\Unit;

use App\Http\Controllers\MobilePriceListController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AssistantProductSearchTest extends TestCase
{
    /** @dataProvider wordMatches */
    public function test_product_word_matching_avoids_unrelated_results(
        string $requested,
        string $catalogueWord,
        bool $shouldMatch
    ): void {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantSearchWordScore');
        $method->setAccessible(true);

        $score = $method->invoke(new MobilePriceListController(), $requested, $catalogueWord);
        $this->assertSame($shouldMatch, $score > 0);
    }

    public function wordMatches(): array
    {
        return [
            'exact product word' => ['juice', 'juice', true],
            'one letter speech typo' => ['jucie', 'juice', true],
            'close long variant' => ['chocolates', 'chocolate', true],
            'short coincidental typo' => ['so', 'to', false],
            'substring in unrelated product' => ['oil', 'toilet', false],
            'different grocery word' => ['mango', 'orange', false],
        ];
    }

    public function test_spoken_variant_name_selects_the_unique_option_without_a_click(): void
    {
        $options = [
            ['id' => 1, 'brand' => 'Real', 'name' => 'Real Mango Juice'],
            ['id' => 2, 'brand' => 'Real', 'name' => 'Real Orange Juice'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantClarificationChoice');
        $method->setAccessible(true);

        $selected = $method->invoke(new MobilePriceListController(), 'mango wala 2 pack add karo', $options);

        $this->assertSame(1, $selected['id']);
    }

    public function test_ambiguous_brand_does_not_silently_choose_wrong_variant(): void
    {
        $options = [
            ['id' => 1, 'brand' => 'Real', 'name' => 'Real Mango Juice'],
            ['id' => 2, 'brand' => 'Real', 'name' => 'Real Orange Juice'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantClarificationChoice');
        $method->setAccessible(true);

        $this->assertNull($method->invoke(new MobilePriceListController(), 'Real brand add karo', $options));
    }

    public function test_common_voice_noise_is_normalized_before_order_understanding(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'normalizeAssistantCustomerUtterance');
        $method->setAccessible(true);

        $normalized = $method->invoke(
            new MobilePriceListController(),
            'bhai Reel... Reel orange joos ek krdo na'
        );

        $this->assertSame('bhai Real orange juice ek kar do na', $normalized);
    }

    public function test_taaza_variant_understands_taza_pronunciation(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantRequestedProductVariant');
        $method->setAccessible(true);

        $this->assertSame('taaza', $method->invoke(new MobilePriceListController(), 'Amul taza milk ek add karo'));
        $this->assertSame('taaza', $method->invoke(new MobilePriceListController(), 'Amul taja doodh chahiye'));
    }

    public function test_pack_size_is_not_confused_with_order_quantity(): void
    {
        $controller = new MobilePriceListController();
        $quantity = new ReflectionMethod(MobilePriceListController::class, 'assistantSpokenOrderQuantity');
        $quantity->setAccessible(true);
        $packSize = new ReflectionMethod(MobilePriceListController::class, 'assistantRequestedPackSize');
        $packSize->setAccessible(true);

        $this->assertSame(2, $quantity->invoke($controller, '200ml wale do'));
        $this->assertSame('200ml', $packSize->invoke($controller, '200ml wale do'));
        $this->assertSame(1, $quantity->invoke($controller, 'do litre wala ek'));
        $this->assertSame('2ltr', $packSize->invoke($controller, 'do litre wala ek'));
    }

    public function test_half_word_prefixes_help_without_overpowering_single_short_terms(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantSearchWordScore');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertSame(70, $method->invoke($controller, 'ora', 'orange'));
        $this->assertSame(58, $method->invoke($controller, 'ju', 'juice'));
        $this->assertSame(0, $method->invoke($controller, 'so', 'to'));
    }

    public function test_compact_phonetic_product_names_can_be_ranked_safely(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantSearchPhraseScore');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertGreaterThanOrEqual(140, $method->invoke($controller, 'thumbs app', 'Thums Up Soft Drink 250 ml'));
        $this->assertGreaterThanOrEqual(140, $method->invoke($controller, 'coka cola', 'Coca Cola Bottle'));
        $this->assertSame(0, $method->invoke($controller, 'oil', 'Toilet Cleaner'));
    }

    public function test_product_search_tokens_include_compact_catalogue_forms(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantProductSearchTokens');
        $method->setAccessible(true);

        $tokens = $method->invoke(new MobilePriceListController(), 'Thums Up Soft Drink 250 ml');

        $this->assertContains('thumsup', $tokens);
        $this->assertContains('softdrink', $tokens);
        $this->assertContains('thumsupsoftdrink', $tokens);
    }

    public function test_pack_size_reply_selects_the_matching_visible_option(): void
    {
        $options = [
            ['id' => 1, 'brand' => 'Real', 'name' => 'Real Orange Juice', 'unit' => '200 ml', 'carton_size' => '200 ml'],
            ['id' => 2, 'brand' => 'Real', 'name' => 'Real Orange Juice', 'unit' => '1 ltr', 'carton_size' => '1 ltr'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantClarificationChoice');
        $method->setAccessible(true);

        $selected = $method->invoke(new MobilePriceListController(), '200ml wala', $options);

        $this->assertSame(1, $selected['id']);
    }

    public function test_missing_exact_juice_builds_safe_approved_alternative_queries(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantApprovedAlternativeQueries');
        $method->setAccessible(true);

        $queries = $method->invoke(new MobilePriceListController(), 'Real apple flavour juice chahiye');

        $this->assertContains('apple juice', $queries);
        $this->assertContains('real juice', $queries);
        $this->assertContains('juice', $queries);
        $this->assertNotContains('real apple flavour juice', $queries);
    }

    public function test_full_conversational_voice_reply_selects_exact_visible_product(): void
    {
        $options = [
            ['id' => 10, 'brand' => 'Amul', 'name' => 'Amul Butter CP'],
            ['id' => 11, 'brand' => 'Amul', 'name' => 'Amul Butter Unsalted'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantClarificationChoice');
        $method->setAccessible(true);

        $selected = $method->invoke(new MobilePriceListController(), 'haan Amul Butter CP jo hai wo add kar do', $options);

        $this->assertSame(10, $selected['id']);
    }

    public function test_devanagari_add_command_is_treated_as_product_request(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'looksLikeAssistantProductRequest');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(new MobilePriceListController(), 'मैगी नूडल्स ऐड कर दो'));
    }

    public function test_only_explicit_shopping_actions_override_gemini_intent(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'hasAssistantExplicitProductAction');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertTrue($method->invoke($controller, 'Real apple juice add karo'));
        $this->assertTrue($method->invoke($controller, 'mujhe orange juice chahiye'));
        $this->assertFalse($method->invoke($controller, 'aap jo theek samjho karo'));
        $this->assertFalse($method->invoke($controller, 'new order karna hai'));
        $this->assertFalse($method->invoke($controller, 'apple ko select karo'));
    }

    /** @dataProvider naturalVariantReplies */
    public function test_natural_spoken_replies_select_orange(string $reply): void
    {
        $options = [
            ['id' => 1, 'brand' => 'Real', 'name' => 'Real Apple Juice, 1 ltr'],
            ['id' => 2, 'brand' => 'Real', 'name' => 'Real Orange Juice, 1 ltr'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantClarificationChoice');
        $method->setAccessible(true);

        $selected = $method->invoke(new MobilePriceListController(), $reply, $options);
        $this->assertSame(2, $selected['id']);
    }

    public function naturalVariantReplies(): array
    {
        return [
            'orange wala' => ['orange wala'],
            'voice-only affirmative selection' => ['haan orange wala select kardo'],
            'full request' => ['mujhe real orange juice chahiye'],
            'selection command' => ['orange wala select kar do'],
            'position' => ['second wala'],
        ];
    }

    public function test_hinglish_selection_command_ignores_postpositions(): void
    {
        $options = [
            ['id' => 1, 'brand' => 'Real', 'name' => 'Real Apple Juice, 1 ltr'],
            ['id' => 2, 'brand' => 'Real', 'name' => 'Real Orange Juice, 1 ltr'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantClarificationChoice');
        $method->setAccessible(true);

        $selected = $method->invoke(new MobilePriceListController(), 'apple ko select karo', $options);

        $this->assertSame(1, $selected['id']);
    }

    /** @dataProvider deliveryReplies */
    public function test_only_real_delivery_replies_advance_to_payment(string $reply, bool $expected): void
    {
        $delivery = ['slots' => [
            ['date' => '2026-08-20', 'label' => '20 Aug, Thursday - 10 AM to 1 PM'],
            ['date' => '2026-08-21', 'label' => '21 Aug, Friday - 2 PM to 5 PM'],
        ]];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantDeliverySelection');
        $method->setAccessible(true);

        $selected = $method->invoke(new MobilePriceListController(), $reply, $delivery);
        $this->assertSame($expected, $selected !== null);
    }

    public function deliveryReplies(): array
    {
        return [
            'product request is not slot' => ['ek Real juice add kar do', false],
            'unrelated answer is not slot' => ['mujhe kuch aur chahiye', false],
            'day selection' => ['Friday wala slot', true],
            'position selection' => ['second slot', true],
            'exact date' => ['2026-08-20', true],
        ];
    }

    public function test_click_generated_location_and_slot_selection_advances_to_payment(): void
    {
        $controller = new MobilePriceListController();
        $delivery = [
            'locations' => [[
                'outlet_id' => 17,
                'outlet_name' => 'Mumbra Outlet',
                'label' => 'Mumbra Outlet - Kausa, Mumbra - 400612',
            ]],
            'slots' => [[
                'date' => '2026-08-21',
                'label' => '21 Aug, Friday - 2 PM to 5 PM',
            ]],
        ];
        // This is the exact shape sent by data-delivery-option after the
        // location is selected in the UI.
        $clickedValue = $delivery['locations'][0]['label'] . ', ' . $delivery['slots'][0]['label'];
        $resolveLocation = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantDeliveryLocation');
        $resolveLocation->setAccessible(true);
        $selectedLocation = $resolveLocation->invoke($controller, $clickedValue, $delivery['locations']);
        $this->assertSame(17, $selectedLocation['outlet_id']);

        $resolveSlot = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantDeliverySelection');
        $resolveSlot->setAccessible(true);
        $selectedSlot = $resolveSlot->invoke($controller, $clickedValue, $delivery);

        $this->assertSame($delivery['slots'][0]['label'], $selectedSlot['label']);

        $advanceToPayment = new ReflectionMethod(MobilePriceListController::class, 'assistantDeliverySlotPaymentResponse');
        $advanceToPayment->setAccessible(true);
        $response = $advanceToPayment->invoke($controller, null, null, $delivery['locations'][0], $delivery, $selectedSlot);

        $this->assertSame('payment_method', $response['workflow']['stage']);
        $this->assertSame('payment_method', $response['state']['stage']);
        $this->assertSame($clickedValue, $response['workflow']['delivery_details']);
        $this->assertSame(17, $response['workflow']['delivery_outlet_id']);
        $this->assertSame(17, $response['state']['delivery_outlet_id']);
    }

    public function test_spoken_position_variants_select_the_visible_delivery_location(): void
    {
        $locations = [
            ['outlet_id' => 10, 'outlet_name' => 'Zohik', 'label' => 'Zohik - Assa nagar Mulund West'],
            ['outlet_id' => 20, 'outlet_name' => 'AI Test', 'label' => 'AI Test - Mulund railway station East'],
        ];
        $method = new ReflectionMethod(MobilePriceListController::class, 'resolveAssistantDeliveryLocation');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        foreach (['first wala select karo', 'number one', 'left wala', 'पहला वाला'] as $message) {
            $this->assertSame(10, $method->invoke($controller, $message, $locations)['outlet_id']);
        }
        foreach (['second wala select karo', 'number two', 'right wala', 'दूसरा वाला'] as $message) {
            $this->assertSame(20, $method->invoke($controller, $message, $locations)['outlet_id']);
        }
    }

    public function test_cash_on_delivery_voice_variants_resolve_consistently(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'normalizeAssistantPaymentMethod');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertSame('Pay on Delivery', $method->invoke($controller, '', 'cash on delivery'));
        $this->assertSame('Pay on Delivery', $method->invoke($controller, '', 'COD kar do'));
        $this->assertSame('Pay on Delivery', $method->invoke($controller, '', 'delivery par cash'));
        $this->assertSame('Pay Online', $method->invoke($controller, '', 'UPI se karunga'));
    }

    public function test_suggestion_recovery_ignores_cart_snapshots_duplicates_and_current_cart(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantSuggestionIdsFromCards');
        $method->setAccessible(true);

        $ids = $method->invoke(new MobilePriceListController(), [
            ['id' => 41],
            ['id' => 42, 'order_snapshot' => true],
            ['id' => 41],
            ['id' => 43],
            ['id' => 44],
        ], [43], 2);

        $this->assertSame([41, 44], $ids);
    }
}
