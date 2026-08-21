<?php

namespace Tests\Feature;

use App\Services\GoogleSheetsService;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LeadSubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $mock = $this->createMock(GoogleSheetsService::class);
        $mock->method('appendLead');
        $this->app->instance(GoogleSheetsService::class, $mock);
    }

    public function test_can_submit_valid_lead(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => 'Ahmed Mohamed',
            'email' => 'ahmed@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'I need help with business growth',
            'consent' => '1',
        ]);

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Your enquiry has been submitted successfully. The BBIP team will contact you shortly.')
            );
    }

    public function test_rejects_invalid_email(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => 'Ahmed Mohamed',
            'email' => 'invalid-email',
            'whatsapp' => '+971501234567',
            'challenge' => 'I need help',
            'consent' => '1',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('email');
    }

    public function test_rejects_missing_required_fields(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => 'Ahmed Mohamed',
            'email' => 'ahmed@example.com',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid(['whatsapp', 'challenge', 'consent']);
    }

    public function test_rejects_missing_consent(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => 'Ahmed Mohamed',
            'email' => 'ahmed@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'I need help',
        ]);

        $response->assertUnprocessable()
            ->assertInvalid('consent');
    }

    public function test_trims_whitespace(): void
    {
        $mock = $this->createMock(GoogleSheetsService::class);
        $mock->expects($this->once())
            ->method('appendLead')
            ->with($this->callback(function (array $data): bool {
                return $data['name'] === 'Ahmed Mohamed'
                    && $data['email'] === 'ahmed@example.com';
            }));
        $this->app->instance(GoogleSheetsService::class, $mock);

        $this->post(route('leads.store'), [
            'name' => '  Ahmed Mohamed  ',
            'email' => '  ahmed@example.com  ',
            'whatsapp' => '+971501234567',
            'challenge' => 'I need help',
            'consent' => '1',
        ]);
    }

    public function test_handles_google_sheets_failure(): void
    {
        $mock = $this->createMock(GoogleSheetsService::class);
        $mock->expects($this->once())
            ->method('appendLead')
            ->willThrowException(new \RuntimeException('Failed to append lead to Google Sheets.'));
        $this->app->instance(GoogleSheetsService::class, $mock);

        $response = $this->post(route('leads.store'), [
            'name' => 'Ahmed Mohamed',
            'email' => 'ahmed@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'I need help',
            'consent' => '1',
        ]);

        $response->assertServerError()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'We couldn\'t submit your request right now. Please try again.')
            );
    }

    public function test_respects_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('leads.store'), [
                'name' => 'Ahmed Mohamed',
                'email' => "ahmed{$i}@example.com",
                'whatsapp' => '+971501234567',
                'challenge' => 'I need help',
                'consent' => '1',
            ])->assertOk();
        }

        $response = $this->post(route('leads.store'), [
            'name' => 'Ahmed Mohamed',
            'email' => 'ahmed6@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'I need help',
            'consent' => '1',
        ]);

        $response->assertTooManyRequests();
    }
}
