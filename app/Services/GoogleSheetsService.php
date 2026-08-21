<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    private ?Sheets $service = null;

    public function __construct()
    {
        $this->initializeClient();
    }

    private function initializeClient(): void
    {
        $credentialsPath = config('services.google_sheets.credentials_path');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            throw new \RuntimeException('Google Service Account credentials file not found.');
        }

        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope(Sheets::SPREADSHEETS);

        $this->service = new Sheets($client);
    }

    public function appendLead(array $data): void
    {
        $spreadsheetId = config('services.google_sheets.spreadsheet_id');
        $sheetName = config('services.google_sheets.sheet_name');

        if (!$spreadsheetId) {
            throw new \RuntimeException('GOOGLE_SHEETS_SPREADSHEET_ID is not configured.');
        }

        $values = new ValueRange([
            'values' => [
                [
                    now()->toDateTimeString(),
                    $data['name'] ?? '',
                    $data['email'] ?? '',
                    $data['whatsapp'] ?? '',
                    $data['challenge'] ?? '',
                    'landing_page',
                ],
            ],
        ]);

        // Store submitted values literally so phone numbers beginning with "+"
        // are not parsed by Google Sheets as formulas.
        $options = ['valueInputOption' => 'RAW'];

        try {
            $this->appendValues(
                $spreadsheetId,
                "'{$sheetName}'!A:F",
                $values,
                $options
            );
        } catch (\Exception $e) {
            Log::error('Google Sheets API error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            throw new \RuntimeException('Failed to append lead to Google Sheets.');
        }
    }

    protected function appendValues(
        string $spreadsheetId,
        string $range,
        ValueRange $values,
        array $options
    ): void {
        $this->service->spreadsheets_values->append(
            $spreadsheetId,
            $range,
            $values,
            $options
        );
    }
}
