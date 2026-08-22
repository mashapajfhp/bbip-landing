<?php

namespace Tests\Unit;

use App\Http\Requests\StoreLeadRequest;
use Tests\TestCase;

class StoreLeadRequestTest extends TestCase
{
    public function test_request_has_required_rules(): void
    {
        $request = new StoreLeadRequest();

        $rules = $request->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('whatsapp', $rules);
        $this->assertArrayHasKey('challenge', $rules);
        $this->assertArrayHasKey('consent', $rules);
    }

    public function test_name_field_validation(): void
    {
        $request = new StoreLeadRequest();
        $rules = $request->rules();

        $this->assertStringContainsString('required', $rules['name']);
        $this->assertStringContainsString('string', $rules['name']);
    }

    public function test_email_field_validation(): void
    {
        $request = new StoreLeadRequest();
        $rules = $request->rules();

        $this->assertStringContainsString('required', $rules['email']);
        $this->assertStringContainsString('email', $rules['email']);
    }

    public function test_whatsapp_field_validation(): void
    {
        $request = new StoreLeadRequest();
        $rules = $request->rules();

        $this->assertStringContainsString('required', $rules['whatsapp']);
    }

    public function test_challenge_field_validation(): void
    {
        $request = new StoreLeadRequest();
        $rules = $request->rules();

        $this->assertStringContainsString('required', $rules['challenge']);
        $this->assertStringContainsString('string', $rules['challenge']);
    }

    public function test_consent_field_validation(): void
    {
        $request = new StoreLeadRequest();
        $rules = $request->rules();

        $this->assertStringContainsString('required', $rules['consent']);
    }
}
