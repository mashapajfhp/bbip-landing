<?php

namespace Tests\Feature;

use App\Services\GoogleSheetsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\MockInterface;
use Tests\TestCase;

class LeadSubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(GoogleSheetsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('appendLead')->andReturn(null);
        });
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
        $this->mock(GoogleSheetsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('appendLead')
                ->once()
                ->with(\Mockery::on(function ($data) {
                    return $data['name'] === 'Ahmed Mohamed'
                        && $data['email'] === 'ahmed@example.com';
                }));
        });

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
        $this->mock(GoogleSheetsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('appendLead')
                ->once()
                ->andThrow(new \RuntimeException('Failed to append lead to Google Sheets.'));
        });

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
