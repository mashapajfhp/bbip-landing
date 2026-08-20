# Google Sheets Integration Implementation Guide

This document summarizes the changes made to integrate Google Sheets with the BBIP landing page form.

## Overview

The landing page form now submits leads directly to a Google Sheet via Laravel backend, providing secure server-side handling of credentials and data.

## What Changed

### 1. Backend Structure

**New Files Created:**

- `app/Http/Controllers/LeadController.php` - Handles form submissions
- `app/Http/Requests/StoreLeadRequest.php` - Form validation
- `app/Services/GoogleSheetsService.php` - Google Sheets API integration
- `config/services.php` - Configuration for Google Sheets
- `tests/Feature/LeadSubmissionTest.php` - Comprehensive tests
- `tests/TestCase.php` - Base test class
- `phpunit.xml` - PHPUnit configuration

**Modified Files:**

- `routes/web.php` - Added POST `/register-interest` route with rate limiting
- `resources/views/components/lead-form.blade.php` - Updated form to use Laravel endpoint
- `composer.json` - Added `google/apiclient` dependency
- `.env` and `.env.example` - Added Google Sheets configuration variables
- `.gitignore` - Already configured to ignore credentials

## Key Features

### Security

✅ **Credentials Never Exposed**
- Service account JSON path stored in `.env` (not committed)
- Google API calls happen server-side only
- No credentials ever reach the browser

✅ **CSRF Protection**
- Laravel CSRF token included in form
- Protected against cross-site request forgery

✅ **Input Validation**
- Server-side validation of all fields
- Email format validation
- Required field checks
- String length limits

✅ **Error Handling**
- Errors logged server-side with full details
- User-friendly error messages shown in browser
- No stack traces or sensitive data exposed to frontend

### Rate Limiting

The endpoint is rate-limited to **5 submissions per minute per IP address**:

```php
Route::post('/register-interest', ...) 
    ->middleware(['throttle:5,1']);
```

This prevents:
- Accidental duplicate submissions
- Spam and abuse
- Resource exhaustion

## Form Fields

The following fields are captured and validated:

| Field | Required | Type | Length |
|-------|----------|------|--------|
| name | Yes | String | Max 100 chars |
| email | Yes | Email | Max 255 chars |
| whatsapp | Yes | Phone | Max 30 chars |
| challenge | Yes | Text | Max 2000 chars |
| consent | Yes | Checkbox | Accepted only |

## Google Sheet Structure

The integration expects a sheet named **"Leads"** with these columns:

| Column | Value |
|--------|-------|
| A | Timestamp (server-side generated) |
| B | Name |
| C | Email |
| D | WhatsApp |
| E | Challenge |
| F | Source (always "landing_page") |

Example row:
```
2026-08-20 10:30:45 | Ahmed | ahmed@example.com | +971501234567 | Help with growth | landing_page
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
GOOGLE_SHEETS_SPREADSHEET_ID=your-spreadsheet-id
GOOGLE_SHEETS_SHEET_NAME=Leads
GOOGLE_SERVICE_ACCOUNT_JSON_PATH=/path/to/service-account.json
BBIP_WHATSAPP_NUMBER=+971XXXXXXXXX  # Optional, for WhatsApp button
```

See [GOOGLE_SHEETS_SETUP.md](./GOOGLE_SHEETS_SETUP.md) for detailed setup instructions.

### How Configuration Works

```php
// config/services.php
'google_sheets' => [
    'spreadsheet_id' => env('GOOGLE_SHEETS_SPREADSHEET_ID'),
    'sheet_name' => env('GOOGLE_SHEETS_SHEET_NAME', 'Leads'),
    'credentials_path' => env('GOOGLE_SERVICE_ACCOUNT_JSON_PATH'),
],
```

The GoogleSheetsService reads these config values and uses them to authenticate and append data.

## Installation & Setup

### 1. Install Dependencies

```bash
# Local development
composer install

# In Docker
docker-compose exec app composer install
```

### 2. Set Up Google Cloud

Follow the detailed instructions in [GOOGLE_SHEETS_SETUP.md](./GOOGLE_SHEETS_SETUP.md):

1. Create a Google Cloud Project
2. Enable Google Sheets API
3. Create a Service Account
4. Generate and store the JSON credentials
5. Create/prepare a Google Sheet
6. Share it with the service account (Editor access)

### 3. Configure Environment

Update `.env`:

```env
GOOGLE_SHEETS_SPREADSHEET_ID=<your-spreadsheet-id>
GOOGLE_SHEETS_SHEET_NAME=Leads
GOOGLE_SERVICE_ACCOUNT_JSON_PATH=/path/to/service-account.json
BBIP_WHATSAPP_NUMBER=+27XXXXXXXXX
```

For Docker, mount the credentials file:

```yaml
# docker-compose.yml
services:
  app:
    volumes:
      - /etc/bbip/google-service-account.json:/var/www/html/storage/app/google/service-account.json:ro
```

### 4. Test

```bash
# Run tests
php artisan test

# Or in Docker
docker-compose exec app php artisan test
```

## Testing

### Automated Tests

The test suite includes:

- ✅ Valid form submission
- ✅ Invalid email rejection
- ✅ Missing required fields validation
- ✅ Missing consent rejection
- ✅ Whitespace trimming
- ✅ Google Sheets API failure handling
- ✅ Rate limiting enforcement

Run tests with:

```bash
php artisan test

# Or specific test
php artisan test --filter LeadSubmissionTest
```

### Manual Testing

1. Open the landing page in a browser
2. Fill out the form with valid data
3. Click "Submit & Continue"
4. Verify:
   - Success message appears
   - Form clears
   - A new row appears in the Google Sheet
   - Timestamp is correct
   - All fields are populated correctly

### Testing Edge Cases

- Submit with invalid email → Should see validation error
- Submit without consent → Should see error
- Rapid repeated submissions → Should see rate-limit error after 5 attempts
- Submit with very long text → Should be validated and trimmed

## Form Submission Flow

```
1. User fills form and clicks Submit
2. JavaScript validates form client-side
3. Button shows "Submitting..." and is disabled
4. Fetch sends form data to /register-interest
5. Laravel validates input (StoreLeadRequest)
6. GoogleSheetsService authenticates with Google
7. Row appended to Google Sheet
8. Success response returned to browser
9. Form clears, success message shown for 5 seconds
10. Button returns to normal state
```

## Error Scenarios

### Credentials File Not Found

**Error in logs:**
```
Google Service Account credentials file not found.
```

**Fix:**
- Verify file path in `GOOGLE_SERVICE_ACCOUNT_JSON_PATH`
- For Docker, check volume mount configuration
- Ensure file has correct permissions

### Service Account No Editor Access

**Error in logs:**
```
Google Sheets API error: Permission denied
```

**Fix:**
- Open Google Sheet
- Click "Share"
- Add service account email with Editor access

### Spreadsheet ID Invalid

**Error in logs:**
```
Google Sheets API error: Spreadsheet not found
```

**Fix:**
- Verify `GOOGLE_SHEETS_SPREADSHEET_ID` is correct
- Copy from: `https://docs.google.com/spreadsheets/d/{ID}/edit`

## Docker Deployment

### Building the Image

The Dockerfile already includes:
- PHP 8.4 with necessary extensions
- Composer for dependency management
- Proper file permissions setup

Build with:

```bash
docker build -f Dockerfile.dev -t bbip-landing .
```

### Running with Docker Compose

Create or update `docker-compose.yml`:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile.dev
    ports:
      - "8000:8000"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - GOOGLE_SHEETS_SPREADSHEET_ID=${GOOGLE_SHEETS_SPREADSHEET_ID}
      - GOOGLE_SHEETS_SHEET_NAME=Leads
      - GOOGLE_SERVICE_ACCOUNT_JSON_PATH=/var/www/html/storage/app/google/service-account.json
      - BBIP_WHATSAPP_NUMBER=${BBIP_WHATSAPP_NUMBER}
    volumes:
      - /etc/bbip/google-service-account.json:/var/www/html/storage/app/google/service-account.json:ro
    restart: unless-stopped
```

### Running Locally

```bash
docker-compose up --build
```

The app will be available at `http://localhost:8000`

## Security Best Practices

✅ **Do:**
- Keep `.env` file in `.gitignore`
- Store credentials file outside the repo
- Use environment variables for configuration
- Rotate credentials periodically
- Monitor Google Sheets for suspicious activity
- Use strong CSRF tokens
- Validate all input server-side

❌ **Don't:**
- Commit `.env` or credentials to Git
- Expose credentials in error messages
- Skip CSRF protection
- Trust client-side validation only
- Store credentials in code comments

## Maintenance

### Adding New Fields

To add a new form field:

1. **Update the form** in `resources/views/components/lead-form.blade.php`
   - Add input with `name` attribute

2. **Update validation** in `app/Http/Requests/StoreLeadRequest.php`
   - Add validation rule
   - Add error message

3. **Update Google Sheets** column mapping
   - Add to `GoogleSheetsService::appendLead()`
   - Add column header in spreadsheet

4. **Update tests** in `tests/Feature/LeadSubmissionTest.php`
   - Test new field validation

### Rotating Credentials

1. Generate new service account key in Google Cloud
2. Update the JSON file on your server
3. Restart the application
4. Delete the old key from Google Cloud

### Monitoring

Check logs for issues:

```bash
# Docker
docker-compose logs app

# Local
tail -f storage/logs/laravel.log
```

Look for:
- Google API errors
- Validation failures
- Rate limit hits
- Unexpected exceptions

## Support & Troubleshooting

See [GOOGLE_SHEETS_SETUP.md](./GOOGLE_SHEETS_SETUP.md) for:
- Detailed Google Cloud setup
- Common issues and fixes
- Credential rotation procedures

## Files Reference

### Core Application Files

- `app/Http/Controllers/LeadController.php` - Request handler
- `app/Http/Requests/StoreLeadRequest.php` - Validation rules
- `app/Services/GoogleSheetsService.php` - Google API wrapper
- `config/services.php` - Configuration
- `routes/web.php` - Route definitions

### View Files

- `resources/views/components/lead-form.blade.php` - Form component
- Unchanged Blade components maintain existing design

### Test Files

- `tests/Feature/LeadSubmissionTest.php` - Feature tests
- `tests/TestCase.php` - Test base class
- `phpunit.xml` - Test configuration

### Configuration Files

- `.env` - Environment variables
- `.env.example` - Example variables
- `composer.json` - PHP dependencies
- `.gitignore` - Git exclusions

## Performance

- **Database:** No database queries (uses Google Sheets directly)
- **API Calls:** One call per submission to Google Sheets API
- **Rate Limiting:** 5 per minute per IP (configurable in routes/web.php)
- **Response Time:** ~1-2 seconds average (depends on Google API latency)

## Summary

The implementation provides:

✅ Secure server-side Google Sheets integration  
✅ Form validation and error handling  
✅ Rate limiting and spam protection  
✅ Comprehensive test coverage  
✅ Production-ready Docker support  
✅ Clear error logging  
✅ No breaking changes to existing design  

The form submission flow is simple, secure, and maintainable for a production landing page.
