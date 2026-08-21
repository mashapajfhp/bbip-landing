<?php

namespace Tests\Feature;

use Tests\TestCase;

class RuntimeConfigurationTest extends TestCase
{
    public function test_landing_page_runtime_does_not_use_database_backed_services(): void
    {
        $this->assertNotSame('database', config('cache.default'));
        $this->assertNotSame('database', config('session.driver'));
        $this->assertNotSame('database', config('queue.default'));
        $this->assertNotSame('database-uuids', config('queue.failed.driver'));
        $this->assertNotSame('database', config('app.maintenance.store'));
    }
}
