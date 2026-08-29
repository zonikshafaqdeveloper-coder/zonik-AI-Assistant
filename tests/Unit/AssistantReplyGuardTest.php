<?php

namespace Tests\Unit;

use App\Http\Controllers\MobilePriceListController;
use App\Models\Cart;
use App\Services\OrderableProductValidator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AssistantReplyGuardTest extends TestCase
{
    /**
     * @dataProvider recipePlanningExamples
     */
    public function test_it_detects_recipe_shopping_requests(string $message, bool $expected): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantRecipePlanningRequest');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke(new MobilePriceListController(), $message));
    }

    public function recipePlanningExamples(): array
    {
        return [
            'requested maggi plan' => ['aaj mujhe maggie banana hai uske liye kya kya lena hoga', true],
            'natural maggi statement' => ['aaj maggie bana hai', true],
            'short fried rice statement' => ['aaj fried rice', true],
            'spoken fried rice plan' => ['aaj mai fried rice bana hunga usme kya kya use hoga suggest karo', true],
            'english recipe plan' => ['What ingredients do I need to cook pasta?', true],
            'ordinary direct order' => ['mujhe 2 packet maggi chahiye', false],
            'general unrelated question' => ['aaj weather kaisa hai?', false],
        ];
    }

    /**
     * @dataProvider replyExamples
     */
    public function test_it_detects_customer_repetition(string $reply, string $customer, bool $expected): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantReplyRepeatsCustomer');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke(new MobilePriceListController(), $reply, $customer));
    }

    public function replyExamples(): array
    {
        return [
            'exact copy' => ['Nahi, aur kuch nahi chahiye.', 'nahi aur kuch nahi chahiye', true],
            'customer sentence embedded in reply' => ['Ji, nahi aur kuch nahi chahiye.', 'nahi aur kuch nahi chahiye', true],
            'heavy word overlap' => ['Aur kuch nahi chahiye ji.', 'mujhe aur kuch nahi chahiye', true],
            'proper next-step response' => ['Order summary check karke confirm kijiye.', 'mujhe aur kuch nahi chahiye', false],
            'suggestion rejection response' => ['Delivery location choose kijiye.', 'nahi ye suggestions nahi chahiye', false],
        ];
    }

    /**
     * @dataProvider languageExamples
     */
    public function test_it_uses_hinglish_for_default_hindi_conversations(string $message, ?string $hint, string $expected): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantReplyLanguage');
        $method->setAccessible(true);

        $language = $method->invoke(new MobilePriceListController(), $message, $hint);
        $this->assertStringContainsString($expected, $language);
    }

    public function languageExamples(): array
    {
        return [
            'hinglish message' => ['mujhe aur kuch nahi chahiye', 'Hindi', 'Roman-script Hinglish'],
            'model labels mixed speech hindi' => ['product cart mein add kar do', 'Hindi (Latin script)', 'Roman-script Hinglish'],
            'no language hint defaults to hinglish' => ['no', null, 'Roman-script Hinglish'],
            'explicit marathi remains marathi' => ['mala he pahije', 'Marathi', 'Marathi'],
        ];
    }

    public function test_it_uses_spoken_hinglish_for_hindi_and_preserves_other_language_instructions(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantReplyLanguage');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $hindi = "\u{092E}\u{0941}\u{091D}\u{0947} \u{0928}\u{092F}\u{093E} \u{0911}\u{0930}\u{094D}\u{0921}\u{0930} \u{091A}\u{093E}\u{0939}\u{093F}\u{090F}";
        $tamil = "\u{0B8E}\u{0BA9}\u{0B95}\u{0BCD}\u{0B95}\u{0BC1} \u{0B85}\u{0BB0}\u{0BBF}\u{0B9A}\u{0BBF} \u{0BB5}\u{0BC7}\u{0BA3}\u{0BCD}\u{0B9F}\u{0BC1}\u{0BAE}\u{0BCD}";

        $this->assertStringContainsString('Roman-script Hinglish', $method->invoke($controller, $hindi, null));
        $this->assertStringContainsString('Tamil', $method->invoke($controller, $tamil, null));
        $this->assertStringContainsString('same language and writing script', $method->invoke($controller, 'Necesito hacer un pedido nuevo', null));
    }

    /**
     * @dataProvider scriptLanguageExamples
     */
    public function test_it_detects_supported_writing_systems_without_latin_keywords(string $message, string $expected): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'detectAssistantLanguage');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke(new MobilePriceListController(), $message));
    }

    public function scriptLanguageExamples(): array
    {
        return [
            'Bengali' => ["\u{0985}", 'Bengali'],
            'Tamil' => ["\u{0B85}", 'Tamil'],
            'Arabic script' => ["\u{0627}", 'Urdu or Arabic'],
            'Hebrew' => ["\u{05D0}", 'Hebrew'],
            'Khmer' => ["\u{1780}", 'Khmer'],
        ];
    }

    public function test_temporary_question_interrupts_but_product_command_does_not(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantTemporaryQuestion');
        $method->setAccessible(true);
        $flow = ['stage' => 'await_quantity', 'product' => ['name' => 'Real Mango Juice']];

        $this->assertTrue($method->invoke(new MobilePriceListController(), 'delivery kab milegi?', $flow));
        $this->assertTrue($method->invoke(new MobilePriceListController(), 'aaj weather kaisa hai', $flow));
        $this->assertTrue($method->invoke(new MobilePriceListController(), 'Zonik mein kya kya milta hai?', $flow));
        $this->assertFalse($method->invoke(new MobilePriceListController(), '2 packet Real juice add karo', $flow));
        $this->assertFalse($method->invoke(new MobilePriceListController(), 'customer care ko call karo', $flow));
        $this->assertFalse($method->invoke(new MobilePriceListController(), 'order confirm karo', $flow));
    }

    public function test_resume_prompt_returns_to_exact_pending_step(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantResumePrompt');
        $method->setAccessible(true);

        $reply = $method->invoke(new MobilePriceListController(), [
            'stage' => 'await_quantity',
            'product' => ['name' => 'Real Mango Juice'],
        ]);

        $this->assertStringContainsString('Real Mango Juice', $reply);
        $this->assertStringContainsString('quantity', $reply);
    }

    public function test_it_detects_common_roman_marathi_words(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'detectAssistantLanguage');
        $method->setAccessible(true);

        $this->assertSame('Marathi', $method->invoke(new MobilePriceListController(), 'Zonik madhe kay kay milta ahe?'));
        $this->assertSame('Hinglish', $method->invoke(new MobilePriceListController(), 'aaj mai fried rice banaunga usme kya use hoga'));
    }

    public function test_it_detects_full_marathi_sentences_and_marathi_order_confirmation(): void
    {
        $controller = new MobilePriceListController();
        $language = new ReflectionMethod($controller, 'detectAssistantLanguage');
        $confirmation = new ReflectionMethod($controller, 'isAssistantExplicitOrderConfirmation');
        $genericConfirmation = new ReflectionMethod($controller, 'isAssistantGenericConfirmation');
        $finish = new ReflectionMethod($controller, 'isAssistantFinishShoppingMessage');
        $language->setAccessible(true);
        $confirmation->setAccessible(true);
        $genericConfirmation->setAccessible(true);
        $finish->setAccessible(true);

        $this->assertSame('Marathi', $language->invoke($controller, 'माझ्या ऑर्डरमध्ये आणखी काही नको, कृपया पुढची प्रक्रिया सांगा.'));
        $this->assertTrue($confirmation->invoke($controller, 'हो, माझी ऑर्डर निश्चित करा.'));
        $this->assertTrue($genericConfirmation->invoke($controller, 'हो ऑर्डर निश्चित करा'));
        $this->assertTrue($finish->invoke($controller, 'मला आणखी काही नाही, बस झाले.'));
        $this->assertTrue($finish->invoke($controller, 'nahi mujhe aur kuch bhi nahi chahiye'));
        $this->assertTrue($finish->invoke($controller, 'mujhe or kuch nahi chahiye'));
    }

    public function test_it_normalizes_common_regional_product_names_before_catalogue_matching(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'normalizeAssistantSearchText');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertStringContainsString('sugar', $method->invoke($controller, 'mala sakhar pahije'));
        $this->assertStringContainsString('rice', $method->invoke($controller, 'मला तांदूळ पाहिजे'));
        $this->assertStringContainsString('rice', $method->invoke($controller, 'బియ్యం కావాలి'));
        $this->assertStringContainsString('milk', $method->invoke($controller, 'আমার দুধ চাই'));
    }

    public function test_product_range_question_is_not_mistaken_for_a_location_question(): void
    {
        $controller = new MobilePriceListController();
        $discovery = new ReflectionMethod($controller, 'isAssistantProductDiscoveryRequest');
        $discovery->setAccessible(true);
        $normalize = new ReflectionMethod($controller, 'normalizeAssistantSearchText');
        $normalize->setAccessible(true);

        $message = 'zonik me or kon konse chawal milta hai';
        $this->assertTrue($discovery->invoke($controller, $message));
        $this->assertStringContainsString('rice', $normalize->invoke($controller, $message));
    }

    public function test_customer_care_decline_wins_over_the_continue_command_and_resumes_the_order(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'continueAssistantOrderFlow');
        $method->setAccessible(true);

        $response = $method->invoke(new MobilePriceListController(), 'nahi yahin continue karo', [
            'stage' => 'customer_care_offer',
            'resume_state' => [
                'stage' => 'await_quantity',
                'product' => ['name' => 'Real Mango Juice'],
            ],
        ], null, null);

        $this->assertSame('await_quantity', $response['workflow']['stage']);
        $this->assertSame('await_quantity', $response['state']['stage']);
        $this->assertSame('Real Mango Juice', $response['state']['product']['name']);
    }

    public function test_customer_care_decline_has_a_persisted_fallback_when_there_is_no_order_to_resume(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'continueAssistantOrderFlow');
        $method->setAccessible(true);

        $response = $method->invoke(new MobilePriceListController(), 'nahi, call nahi chahiye', [
            'stage' => 'customer_care_offer',
            'resume_state' => [],
        ], null, null);

        $this->assertSame('anything_else', $response['workflow']['stage']);
        $this->assertSame(['stage' => 'anything_else'], $response['state']);
    }

    public function test_customer_care_affirmatives_return_an_immediate_normalized_dial_workflow(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'continueAssistantOrderFlow');
        $method->setAccessible(true);
        $flow = [
            'stage' => 'customer_care_offer',
            'resume_state' => [
                'stage' => 'await_quantity',
                'product' => ['name' => 'Real Mango Juice'],
            ],
        ];

        foreach (['haan', "\u{0939}\u{093E}\u{0901}", "\u{091C}\u{0940}, \u{092C}\u{093E}\u{0924} \u{0915}\u{0930}\u{093E}\u{0913}"] as $message) {
            $response = $method->invoke(new MobilePriceListController(), $message, $flow, null, null);
            $workflow = $response['workflow'];

            $this->assertSame('call_customer_care', $workflow['stage']);
            $this->assertMatchesRegularExpression('/^\+\d{10,15}$/', $workflow['phone']);
            $this->assertSame('tel:' . $workflow['phone'], $workflow['dial_url']);
            // Do not leave the next request trapped on the call-consent step.
            $this->assertSame('await_quantity', $response['state']['stage']);
        }
    }

    public function test_customer_care_offer_exposes_the_same_safe_dial_target_before_confirmation(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantCustomerCareOfferWorkflow');
        $method->setAccessible(true);

        $workflow = $method->invoke(new MobilePriceListController());

        $this->assertSame('customer_care_offer', $workflow['stage']);
        $this->assertMatchesRegularExpression('/^\+\d{10,15}$/', $workflow['phone']);
        $this->assertSame('tel:' . $workflow['phone'], $workflow['dial_url']);
    }

    public function test_customer_care_phone_normalization_and_direct_call_detection_are_safe(): void
    {
        $controller = new MobilePriceListController();
        $phone = new ReflectionMethod($controller, 'assistantCustomerCarePhone');
        $phone->setAccessible(true);
        $directCall = new ReflectionMethod($controller, 'isAssistantDirectCustomerCareCallRequest');
        $directCall->setAccessible(true);

        $this->assertSame('+918850268043', $phone->invoke($controller, '88502 68043'));
        $this->assertSame('+918850268043', $phone->invoke($controller, 'tel:+91-88502-68043'));
        $this->assertSame('+918850268043', $phone->invoke($controller, 'not a phone number'));
        $this->assertTrue($directCall->invoke($controller, 'customer care ko call karo'));
        $this->assertTrue($directCall->invoke($controller, 'customer care se baat karo'));
        $this->assertTrue($directCall->invoke($controller, 'call karo'));
        $this->assertTrue($directCall->invoke($controller, 'call laga do'));
        $this->assertTrue($directCall->invoke($controller, 'phone laga do'));
        $this->assertTrue($directCall->invoke($controller, 'customer care se phone connect karo'));
        $this->assertTrue($directCall->invoke($controller, 'live agent se connect karo'));
        $this->assertTrue($directCall->invoke($controller, 'customer care executive se baat karao'));
        $this->assertTrue($directCall->invoke($controller, "\u{0915}\u{0949}\u{0932} \u{0915}\u{0930}\u{094B}"));
        $this->assertTrue($directCall->invoke($controller, "\u{0915}\u{0938}\u{094D}\u{091F}\u{092E}\u{0930} \u{0915}\u{0947}\u{092F}\u{0930} \u{0938}\u{0947} \u{092C}\u{093E}\u{0924} \u{0915}\u{0930}\u{093E}\u{0913}"));
        $this->assertFalse($directCall->invoke($controller, 'call kaise karu?'));
        $this->assertFalse($directCall->invoke($controller, 'customer care nahi chahiye'));
    }

    public function test_onboarding_non_choice_messages_are_handed_to_the_normal_chat_once(): void
    {
        $controller = new MobilePriceListController();
        $shouldForward = new ReflectionMethod($controller, 'shouldAssistantForwardOnboardingMessage');
        $shouldForward->setAccessible(true);
        $handoff = new ReflectionMethod($controller, 'assistantOnboardingChatHandoff');
        $handoff->setAccessible(true);

        foreach (['customer care ko call karo', 'Zonik mein delivery slot kaise milega?', 'Real juice 2 pack add karo'] as $message) {
            $this->assertTrue($shouldForward->invoke($controller, $message));
        }

        $response = $handoff->invoke($controller, 'delivery slot kaise milega?', 'order_choice');
        $this->assertSame('forward_to_chat', $response['choice']);
        $this->assertTrue($response['forward_to_chat']);
        $this->assertTrue($response['resume_order_choice']);
        $this->assertSame('order_choice', $response['onboarding_stage']);
        $this->assertNotSame('', $response['fallback_reply']);
    }

    public function test_onboarding_question_words_do_not_trigger_new_or_previous_order_action(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantOnboardingQuestion');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(new MobilePriceListController(), 'new order kaise karu'));
        $this->assertTrue($method->invoke(new MobilePriceListController(), 'previous order kaise de sakta hu?'));
    }

    public function test_normal_questions_are_allowed_to_reach_the_general_agent(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantZonikScopedMessage');
        $method->setAccessible(true);

        // False here now means "general agent question", not "reject it".
        // assistantConversationReply intentionally sends this to the model.
        $this->assertFalse($method->invoke(new MobilePriceListController(), 'What is the weather in Mumbai?'));
        $this->assertTrue($method->invoke(new MobilePriceListController(), 'Meri price list dikhao'));
    }

    public function test_customer_care_offer_releases_unrelated_messages_for_normal_analysis(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'continueAssistantOrderFlow');
        $method->setAccessible(true);

        $response = $method->invoke(new MobilePriceListController(), 'Amul butter 2 packet chahiye', [
            'stage' => 'customer_care_offer',
            'resume_state' => [],
        ], null, null);

        $this->assertTrue($response['continue_normal']);
        $this->assertSame('anything_else', $response['workflow']['stage']);
        $this->assertSame(['stage' => 'anything_else'], $response['state']);
    }

    public function test_finish_shopping_after_cancelled_customer_care_call_shows_summary(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'continueAssistantOrderFlow');
        $method->setAccessible(true);

        $response = $method->invoke(new MobilePriceListController(), 'mujhe or kuch nhi chahiye', [
            'stage' => 'customer_care_offer',
            'resume_state' => ['stage' => 'anything_else'],
        ], null, null);

        $this->assertSame('confirm_order', $response['workflow']['stage']);
        $this->assertTrue($response['workflow']['show_cart']);
        $this->assertSame(['stage' => 'confirm_order'], $response['state']);
    }

    public function test_assistant_voice_is_polite_and_feminine(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'enforceAssistantFemaleVoice');
        $method->setAccessible(true);

        $reply = $method->invoke(new MobilePriceListController(), 'Arrre bhai, main check kar raha hoon aur bataunga.');

        $this->assertStringNotContainsString('Arrre', $reply);
        $this->assertStringContainsString('kar rahi hoon', $reply);
        $this->assertStringContainsString('bataungi', $reply);
    }

    public function test_checkout_ready_yields_to_a_fresh_product_change(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantFlowShouldYieldToFreshProductRequest');
        $method->setAccessible(true);

        $result = $method->invoke(new MobilePriceListController(), 'Amul butter 2 packet add karo', 'checkout_ready', [
            'stage' => 'checkout_ready',
        ], [
            'message_type' => 'fresh_product_request',
            'has_product_reference' => true,
        ]);

        $this->assertTrue($result);
    }

    public function test_product_command_after_customer_care_offer_is_not_mistaken_for_call_consent(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'continueAssistantOrderFlow');
        $method->setAccessible(true);

        $response = $method->invoke(new MobilePriceListController(), 'Amul butter add karo', [
            'stage' => 'customer_care_offer',
            'resume_state' => ['stage' => 'anything_else'],
        ], null, null);

        $this->assertTrue($response['continue_normal']);
        $this->assertSame('anything_else', $response['workflow']['stage']);
        $this->assertSame(['stage' => 'anything_else'], $response['state']);
    }

    public function test_cart_display_uses_real_legacy_quantity_instead_of_defaulting_to_one(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantResolvedCartQuantity');
        $method->setAccessible(true);
        $cart = new Cart(['quantity' => 1, 'count_value' => 3, 'total_qty' => 3]);

        $this->assertSame(3, $method->invoke(new MobilePriceListController(), $cart));
    }

    public function test_spoken_remove_and_quantity_change_are_detected_as_cart_commands(): void
    {
        $controller = new MobilePriceListController();
        $remove = new ReflectionMethod($controller, 'isAssistantCartRemoveRequest');
        $update = new ReflectionMethod($controller, 'isAssistantCartQuantityUpdateRequest');
        $remove->setAccessible(true);
        $update->setAccessible(true);

        $this->assertTrue($remove->invoke($controller, 'Real juice remove kar do'));
        $this->assertTrue($remove->invoke($controller, 'रियल एप्पल जूस को रिमूव करो'));
        $this->assertTrue($update->invoke($controller, 'Real juice quantity 4 kar do'));
        $this->assertTrue($update->invoke($controller, 'rice ko 2 kar do'));
        $this->assertTrue($update->invoke($controller, 'Amul Butter 5 rakh do'));
        $this->assertTrue($update->invoke($controller, 'अरे वह पहले पांच थी अब 2 करो'));
        $this->assertTrue($update->invoke($controller, 'अमूल बटर क को 10 कर दो 500 के जगह'));

        $correctedQuantity = new ReflectionMethod($controller, 'assistantCorrectedCartQuantity');
        $correctedQuantity->setAccessible(true);
        $this->assertSame(1.0, $correctedQuantity->invoke($controller, '500 nahi 1 chahiye 1 kardo', 500.0));
    }

    public function test_unverified_ai_text_cannot_claim_a_cart_mutation(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantNaturalFlowReply');
        $method->setAccessible(true);

        $reply = $method->invoke(new MobilePriceListController(), ['assistant_reply' => 'Haan ji, product cart mein add kar diya.'], 'Valid product aur quantity bataiye.');

        $this->assertSame('Valid product aur quantity bataiye.', $reply);
        $reply = $method->invoke(new MobilePriceListController(), ['assistant_reply' => 'Amul Butter ki quantity update karke 10 kar di hai.'], 'Product update execute nahi hui.');
        $this->assertSame('Product update execute nahi hui.', $reply);
    }

    public function test_explicit_order_confirmation_is_distinguished_from_just_finishing_items(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantExplicitOrderConfirmation');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertTrue($method->invoke($controller, 'order confirm kar do'));
        $this->assertTrue($method->invoke($controller, 'shopping complete karo'));
        $this->assertTrue($method->invoke($controller, 'ऑर्डर कन्फर्म करो'));
        $this->assertFalse($method->invoke($controller, 'bas itna hi'));
    }

    public function test_generic_confirmation_is_understood_for_a_waiting_order(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantGenericConfirmation');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertTrue($method->invoke($controller, 'yes confirm karo'));
        $this->assertTrue($method->invoke($controller, "\u{092F}\u{0947}\u{0938} \u{0915}\u{0902}\u{092B}\u{0930}\u{094D}\u{092E} \u{0915}\u{0930}\u{094B}"));
        $this->assertFalse($method->invoke($controller, 'apple wala add karo'));
    }

    public function test_structured_response_parser_recovers_fenced_and_wrapped_json(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantDecodeJsonObject');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertSame(['intent' => 'other'], $method->invoke($controller, "```json\n{\"intent\":\"other\"}\n```"));
        $this->assertSame(['intent' => 'other'], $method->invoke($controller, 'Result: {"intent":"other"}'));
        $this->assertNull($method->invoke($controller, 'not valid JSON'));
    }

    public function test_fresh_named_product_releases_a_soft_flow_without_releasing_a_generic_rejection(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantFlowShouldYieldToFreshProductRequest');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();
        $flow = ['stage' => 'confirm_product', 'product' => ['id' => 1, 'name' => 'Real Mango Juice', 'brand' => 'Real']];

        $this->assertTrue($method->invoke($controller, 'Amul Butter add karo', 'confirm_product', $flow, [
            'message_type' => 'fresh_product_request', 'has_product_reference' => true,
        ]));
        $this->assertFalse($method->invoke($controller, 'nahi, doosra dikhao', 'confirm_product', $flow, [
            'message_type' => 'fresh_product_request', 'has_product_reference' => false,
        ]));
        $this->assertFalse($method->invoke($controller, 'Real Mango Juice 2 pack add karo', 'confirm_product', $flow, [
            'message_type' => 'fresh_product_request', 'has_product_reference' => true,
        ]));
    }

    public function test_zonik_fallback_keeps_common_questions_useful_without_claiming_unverified_actions(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantZonikFallbackReply');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertStringContainsString('orders section', $method->invoke($controller, 'Where can I track my order?'));
        $this->assertStringContainsString('customer care', $method->invoke($controller, 'What is the replacement policy?'));
        $this->assertStringNotContainsString('completed', $method->invoke($controller, 'Where can I track my order?'));
    }

    /**
     * @dataProvider freshOrderSpeechExamples
     */
    public function test_clear_spoken_new_order_phrases_do_not_need_gemini(string $message): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'isAssistantNewOrderIntent');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(new MobilePriceListController(), $message));
    }

    public function freshOrderSpeechExamples(): array
    {
        return [
            'requested Hinglish phrase' => ['mai new order karru ga'],
            'main new order karunga' => ['main new order karunga'],
            'naya order karunga' => ['naya order karunga'],
            'reversed wording' => ['order new karna hai'],
            'fresh order' => ['fresh order dena hai'],
            'Devanagari speech transcript' => ["\u{092E}\u{0948}\u{0902} \u{0928}\u{092F}\u{093E} \u{0911}\u{0930}\u{094D}\u{0921}\u{0930} \u{0915}\u{0930}\u{0942}\u{0902}\u{0917}\u{093E}"],
        ];
    }

    public function test_variant_voice_action_distinguishes_cart_and_enquiry(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantClarificationProductAction');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $this->assertSame('enquiry', $method->invoke($controller, 'mango flavour ki enquiry bhejo'));
        $this->assertSame('enquiry', $method->invoke($controller, 'dusre wale ka price request bhejo'));
        $this->assertSame('cart', $method->invoke($controller, 'orange wala cart mein add karo'));
        $this->assertSame('choose', $method->invoke($controller, 'mango flavour wala'));
    }

    public function test_enquiry_requires_explicit_request_or_confirmation(): void
    {
        $controller = new MobilePriceListController();
        $explicit = new ReflectionMethod(MobilePriceListController::class, 'assistantExplicitEnquiryRequested');
        $explicit->setAccessible(true);
        $consent = new ReflectionMethod(MobilePriceListController::class, 'assistantEnquiryConsentReply');
        $consent->setAccessible(true);

        $this->assertTrue($explicit->invoke($controller, 'Real apple juice ki enquiry bhejo'));
        $this->assertTrue($explicit->invoke($controller, 'price request create karo'));
        $this->assertFalse($explicit->invoke($controller, 'Real apple juice chahiye'));
        $this->assertFalse($explicit->invoke($controller, 'catalogue dikhao'));
        $this->assertSame('yes', $consent->invoke($controller, 'haan'));
        $this->assertSame('no', $consent->invoke($controller, 'nahi'));
        $this->assertSame('unknown', $consent->invoke($controller, 'orange wala'));
    }

    public function test_only_a_positive_numeric_approved_price_is_accepted(): void
    {
        $validator = new OrderableProductValidator();

        $this->assertTrue($validator->isApprovedPrice('475.50'));
        $this->assertFalse($validator->isApprovedPrice(null));
        $this->assertFalse($validator->isApprovedPrice(0));
        $this->assertFalse($validator->isApprovedPrice(-1));
        $this->assertFalse($validator->isApprovedPrice('catalogue-price'));
    }

    public function test_candidate_references_only_match_the_active_candidate_set(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantCandidateSetMatches');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();
        $active = ['stage' => 'clarify_product', 'candidate_set_id' => 'CS_CURRENT'];

        $this->assertTrue($method->invoke($controller, $active, 'CS_CURRENT'));
        $this->assertFalse($method->invoke($controller, $active, 'CS_OLD'));
        $this->assertFalse($method->invoke($controller, $active, null));
        $this->assertTrue($method->invoke($controller, ['stage' => 'anything_else'], null));
    }

    public function test_product_resolution_confidence_drives_safe_next_action(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantResolutionConfidence');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();
        $approved = [['id' => 1, 'available_in_outlet' => true]];

        $this->assertSame('HIGH_CONFIDENCE', $method->invoke($controller, 'product_search', $approved, false, false, 2));
        $this->assertSame('LOW_CONFIDENCE', $method->invoke($controller, 'product_search', $approved, false, false, 0));
        $this->assertSame('MEDIUM_CONFIDENCE', $method->invoke($controller, 'product_search', [$approved[0], ['id' => 2]], false, false, 2));
        $this->assertSame('MEDIUM_CONFIDENCE', $method->invoke($controller, 'product_search', $approved, false, true, 2));
        $this->assertSame('NOT_APPROVED', $method->invoke($controller, 'product_search', $approved, true, false, 2));
        $this->assertSame('NOT_FOUND', $method->invoke($controller, 'product_search', [], false, false, 2));
    }

    public function test_combined_order_sentence_preserves_checkout_preferences(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'assistantExtractCheckoutPreferences');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $preferences = $method->invoke($controller, '5 kilo rice kal Office address pe morning bhejna, UPI se karunga');
        $this->assertArrayHasKey('address_query', $preferences);
        $this->assertArrayHasKey('slot_query', $preferences);
        $this->assertArrayHasKey('payment_query', $preferences);

        $productOnly = $method->invoke($controller, '5 kilo rice add karo');
        $this->assertSame([], $productOnly);
    }

    public function test_voice_units_are_expanded_for_natural_pronunciation(): void
    {
        $method = new ReflectionMethod(MobilePriceListController::class, 'normalizeAssistantSpeechText');
        $method->setAccessible(true);
        $controller = new MobilePriceListController();

        $spoken = $method->invoke(
            $controller,
            'Juice 1 LTR, water 2 L, milk 500 ml, rice 2 kg, butter 250 gm, 3 pcs. Total ₹1,275 and GST 5%.'
        );

        $this->assertSame(
            'Juice 1 litre, water 2 litres, milk 500 millilitres, rice 2 kilograms, butter 250 grams, 3 pieces. Total 1,275 rupees and GST 5 percent.',
            $spoken
        );
    }

}
