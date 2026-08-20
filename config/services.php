<?php

return [
    'google_sheets' => [
        'spreadsheet_id' => env('GOOGLE_SHEETS_SPREADSHEET_ID'),
        'sheet_name' => env('GOOGLE_SHEETS_SHEET_NAME', 'Leads'),
        'credentials_path' => env('GOOGLE_SERVICE_ACCOUNT_JSON_PATH'),
    ],
];
