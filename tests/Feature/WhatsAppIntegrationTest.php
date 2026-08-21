<?php

namespace Tests\Feature;

use Tests\TestCase;

class WhatsAppIntegrationTest extends TestCase
{
    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_landing_page_uses_approved_feedback_copy(): void
    {
        $response = $this->get('/')->assertOk();

        $response->assertSee('BBIP helps individuals understand how they think, learn and perform');
        $response->assertSee('What are my real strengths, gaps and blind spots?');
        $response->assertSee('Discover the right development pathway');
        $response->assertSee('360° Capability Profile');
        $response->assertSee('Personalised Development Pathway');
        $response->assertSee('24/7 On-Demand Access');
        $response->assertSee('Start your peak performance journey.');
        $response->assertSee('Quick onboarding');
        $response->assertSee('Personalised pathway');
        $response->assertSee('bg-amber-400', escape: false);
        $response->assertSee('program-btn', escape: false);
        $response->assertDontSee('Discover the right program for your child');
        $response->assertDontSee('Tell us what you need.');
    }

    public function test_whatsapp_configuration_exists(): void
    {
        $config = config('services.whatsapp');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('number', $config);
        $this->assertArrayHasKey('default_message', $config);
    }

    public function test_whatsapp_button_component_renders_with_configured_number(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertSee('Chat on WhatsApp');
        $view->assertSee('https://wa.me/971501234567');
        $view->assertSee('Chat with us on WhatsApp', escape: false);
    }

    public function test_whatsapp_button_component_does_not_render_without_number(): void
    {
        config(['services.whatsapp' => [
            'number' => null,
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertDontSee('Chat on WhatsApp');
        $view->assertDontSee('wa.me');
    }

    public function test_whatsapp_button_uses_custom_message(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $customMessage = 'Hello, I have a question about registration.';
        $view = $this->blade(
            '<x-whatsapp-button message="' . $customMessage . '" />'
        );

        $expectedUrl = 'https://wa.me/971501234567?text=' . urlencode($customMessage);
        $view->assertSee($expectedUrl, escape: false);
    }

    public function test_whatsapp_button_component_accepts_custom_text(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button>Talk to us on WhatsApp</x-whatsapp-button>'
        );

        $view->assertSee('Talk to us on WhatsApp');
    }

    public function test_whatsapp_button_component_supports_variants(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button variant="whatsapp" />'
        );

        $view->assertSee('btn-whatsapp', escape: false);
    }

    public function test_whatsapp_button_has_accessibility_attributes(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertSee('aria-label="Chat with us on WhatsApp"', escape: false);
    }

    public function test_whatsapp_button_has_analytics_attributes(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button location="hero" />'
        );

        $view->assertSee('data-cta="whatsapp"', escape: false);
        $view->assertSee('data-location="hero"', escape: false);
    }

    public function test_whatsapp_button_opens_in_new_tab(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertSee('target="_blank"', escape: false);
        $view->assertSee('rel="noopener noreferrer"', escape: false);
    }

    public function test_floating_whatsapp_button_renders_with_configured_number(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        $view->assertSee('https://wa.me/971501234567');
        $view->assertSee('Chat with us on WhatsApp', escape: false);
        $view->assertSee('fixed', escape: false);
        $view->assertSee('bottom-6', escape: false);
        $view->assertSee('right-6', escape: false);
    }

    public function test_floating_whatsapp_button_does_not_render_without_number(): void
    {
        config(['services.whatsapp' => [
            'number' => null,
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        $view->assertDontSee('wa.me');
        $view->assertDontSee('floating', escape: false);
    }

    public function test_floating_whatsapp_button_has_responsive_sizing(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        $view->assertSee('w-14', escape: false);
        $view->assertSee('h-14', escape: false);
        $view->assertSee('md:w-16', escape: false);
        $view->assertSee('md:h-16', escape: false);
    }

    public function test_floating_whatsapp_button_has_accessibility_attributes(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        $view->assertSee('aria-label="Chat with us on WhatsApp"', escape: false);
        $view->assertSee('title="Chat with us on WhatsApp"', escape: false);
    }

    public function test_floating_whatsapp_button_has_analytics_attributes(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        $view->assertSee('data-cta="whatsapp"', escape: false);
        $view->assertSee('data-location="floating"', escape: false);
    }

    public function test_whatsapp_url_removes_non_digits_from_number(): void
    {
        config(['services.whatsapp' => [
            'number' => '+971 (50) 123-4567',
            'default_message' => 'Hello',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertSee('https://wa.me/971501234567', escape: false);
        $view->assertDontSee('+971');
        $view->assertDontSee('(50)');
    }

    public function test_whatsapp_url_encodes_message_correctly(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $encodedMessage = urlencode('Hello, I would like to know more.');
        $view->assertSee("https://wa.me/971501234567?text={$encodedMessage}", escape: false);
    }

    public function test_whatsapp_url_encodes_special_characters(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $specialMessage = 'Hello! How are you? I need help & support.';
        $view = $this->blade(
            '<x-whatsapp-button message="' . $specialMessage . '" />'
        );

        $encodedMessage = urlencode($specialMessage);
        $view->assertSee("https://wa.me/971501234567?text={$encodedMessage}", escape: false);
    }

    public function test_whatsapp_url_encodes_arabic_text(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $arabicMessage = 'مرحبا، أود أن أعرف المزيد';
        $view = $this->blade(
            '<x-whatsapp-button message="' . $arabicMessage . '" />'
        );

        $encodedMessage = urlencode($arabicMessage);
        $view->assertSee("https://wa.me/971501234567?text={$encodedMessage}", escape: false);
    }

    public function test_landing_page_includes_whatsapp_buttons(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $response = $this->get('/');

        $response->assertSee('whatsapp-link', escape: false);
        $response->assertSee('Chat on WhatsApp');
    }

    public function test_landing_page_whatsapp_links_never_use_placeholder_urls(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello',
        ]]);

        $html = $this->get('/')->assertOk()->getContent();

        preg_match_all('/<a\b[^>]*class="[^"]*whatsapp-link[^"]*"[^>]*>/i', $html, $links);

        $this->assertNotEmpty($links[0]);

        foreach ($links[0] as $link) {
            $this->assertStringContainsString('href="https://wa.me/971501234567?text=Hello"', $link);
            $this->assertStringNotContainsString('href="#"', $link);
        }
    }

    public function test_mobile_navigation_is_collapsed_by_default(): void
    {
        config(['services.whatsapp.number' => '971501234567']);

        $response = $this->get('/')->assertOk();

        $response->assertSee('x-data="{ open: false }"', escape: false);
        $response->assertSee('id="mobile-menu"', escape: false);
        $response->assertSee('x-cloak', escape: false);
        $response->assertSee('x-show="open"', escape: false);
        $response->assertSee('aria-controls="mobile-menu"', escape: false);
    }

    public function test_whatsapp_message_is_safely_encoded_in_the_url(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => '<script>alert("unsafe")</script>',
        ]]);

        $view = $this->blade('<x-whatsapp-button />');

        $view->assertSee(urlencode('<script>alert("unsafe")</script>'), escape: false);
        $view->assertDontSee('<script>', escape: false);
    }

    public function test_landing_page_includes_floating_button(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $response = $this->get('/');

        $response->assertSee('data-location="floating"', escape: false);
    }

    public function test_default_message_from_configuration(): void
    {
        $defaultMessage = 'Hello, I am interested in your services.';
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => $defaultMessage,
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $encodedMessage = urlencode($defaultMessage);
        $view->assertSee("?text={$encodedMessage}", escape: false);
    }

    public function test_custom_message_overrides_default(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Default message',
        ]]);

        $customMessage = 'Custom message';
        $view = $this->blade(
            '<x-whatsapp-button message="' . $customMessage . '" />'
        );

        $encodedCustom = urlencode($customMessage);
        $view->assertSee("?text={$encodedCustom}", escape: false);
        $view->assertDontSee(urlencode('Default message'));
    }

    public function test_whatsapp_button_component_renders_correct_icon_class(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertSee('wa-dot', escape: false);
    }

    public function test_floating_button_includes_svg_icon(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        $view->assertSee('<svg', escape: false);
        $view->assertSee('viewBox="0 0 16 16"', escape: false);
    }

    public function test_whatsapp_button_z_index_does_not_block_content(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $view = $this->blade(
            '<x-floating-whatsapp />'
        );

        // z-40 is appropriate for floating elements
        $view->assertSee('z-40', escape: false);
    }

    public function test_landing_page_does_not_show_whatsapp_when_number_not_configured(): void
    {
        config(['services.whatsapp' => [
            'number' => null,
            'default_message' => 'Hello, I would like to know more.',
        ]]);

        $response = $this->get('/');

        $response->assertDontSee('wa.me');
    }

    public function test_both_button_components_can_coexist_on_same_page(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Default message',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button location="hero" message="Hero message" />
             <x-floating-whatsapp />'
        );

        $view->assertSee('data-location="hero"', escape: false);
        $view->assertSee('data-location="floating"', escape: false);
        $view->assertSee('https://wa.me/971501234567', escape: false);
    }

    public function test_whatsapp_number_accepts_country_code_without_plus_sign(): void
    {
        config(['services.whatsapp' => [
            'number' => '971501234567',
            'default_message' => 'Hello',
        ]]);

        $view = $this->blade(
            '<x-whatsapp-button />'
        );

        $view->assertSee('https://wa.me/971501234567', escape: false);
    }

    public function test_whatsapp_configuration_values_are_valid(): void
    {
        $number = config('services.whatsapp.number');
        $message = config('services.whatsapp.default_message');

        if ($number !== null && $number !== '') {
            $this->assertIsString($number);
            $this->assertMatchesRegularExpression('/^\d{10,15}$/', $number);
        }

        $this->assertIsString($message);
        $this->assertNotEmpty($message);
    }
}
