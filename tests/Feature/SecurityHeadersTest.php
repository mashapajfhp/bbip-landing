<?php

namespace Tests\Feature;

use App\Services\GoogleSheetsService;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(
            GoogleSheetsService::class,
            $this->createMock(GoogleSheetsService::class)
        );
    }

    public function test_landing_page_has_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_api_response_has_security_headers(): void
    {
        $response = $this->postJson(route('leads.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'Test challenge',
            'consent' => '1',
        ]);

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_response_content_type_is_json_for_api(): void
    {
        $response = $this->postJson(route('leads.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'Test challenge',
            'consent' => '1',
        ]);

        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_response_content_type_is_html_for_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $this->assertStringContainsString(
            'text/html',
            (string) $response->headers->get('Content-Type')
        );
    }
}
