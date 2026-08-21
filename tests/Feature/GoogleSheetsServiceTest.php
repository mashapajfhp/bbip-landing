<?php

namespace Tests\Feature;

use App\Services\GoogleSheetsService;
use Google\Service\Sheets\ValueRange;
use Tests\TestCase;

class GoogleSheetsServiceTest extends TestCase
{
    public function test_lead_values_are_appended_as_raw_data_in_column_order(): void
    {
        config([
            'services.google_sheets.spreadsheet_id' => 'spreadsheet-id',
            'services.google_sheets.sheet_name' => 'Leads',
        ]);

        $service = new class extends GoogleSheetsService
        {
            public array $appendCall = [];

            public function __construct()
            {
                // Avoid external credentials; this test captures the API payload.
            }

            protected function appendValues(
                string $spreadsheetId,
                string $range,
                ValueRange $values,
                array $options
            ): void {
                $this->appendCall = compact('spreadsheetId', 'range', 'values', 'options');
            }
        };

        $service->appendLead([
            'name' => 'Karly Dodson',
            'email' => 'karly@example.com',
            'whatsapp' => '+971501234567',
            'challenge' => 'Academic coaching',
        ]);

        $this->assertSame('spreadsheet-id', $service->appendCall['spreadsheetId']);
        $this->assertSame("'Leads'!A:F", $service->appendCall['range']);
        $this->assertSame(['valueInputOption' => 'RAW'], $service->appendCall['options']);

        $row = $service->appendCall['values']->getValues()[0];

        $this->assertCount(6, $row);
        $this->assertNotEmpty($row[0]);
        $this->assertSame([
            'Karly Dodson',
            'karly@example.com',
            '+971501234567',
            'Academic coaching',
            'landing_page',
        ], array_slice($row, 1));
    }
}
