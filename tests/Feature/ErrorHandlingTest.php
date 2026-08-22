<?php

namespace Tests\Feature;

use App\Services\GoogleSheetsService;
use Tests\TestCase;

class ErrorHandlingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(
            GoogleSheetsService::class,
            $this->createMock(GoogleSheetsService::class)
        );
    }

    public function test_not_found_page_returns_404(): void
    {
        $response = $this->get('/non-existent-page');

        $response->assertNotFound();
    }

    public function test_invalid_route_returns_404(): void
    {
        $response = $this->get('/invalid/deeply/nested/route');

        $response->assertNotFound();
    }

    public function test_method_not_allowed_returns_405(): void
    {
        $response = $this->get(route('leads.store'));

        $response->assertMethodNotAllowed();
    }

    public function test_lead_submission_returns_proper_error_json(): void
    {
        $response = $this->postJson(route('leads.store'), [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'whatsapp' => '+971501234567',
            'challenge' => 'Test challenge',
            'consent' => '1',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    public function test_invalid_json_returns_proper_error(): void
    {
        $response = $this->postJson(route('leads.store'), [], [
            'Accept' => 'application/json',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure(['message', 'errors']);
    }

    public function test_server_error_includes_error_json_response(): void
    {
        $service = $this->createMock(GoogleSheetsService::class);
        $service->method('appendLead')
            ->willThrowException(new \RuntimeException('Google Sheets unavailable.'));
        $this->app->instance(GoogleSheetsService::class, $service);

        $response = $this->postJson(route('leads.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'Test challenge',
            'consent' => '1',
        ]);

        $response->assertServerError()
            ->assertJsonStructure(['success', 'message']);
    }
}
