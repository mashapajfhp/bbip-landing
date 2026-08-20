# Google Sheets Integration - Quick Start

## What Was Done

Your existing BBIP landing page form has been successfully connected to Google Sheets. The form now submits leads securely to a Google Sheet via Laravel backend.

### ✅ Existing Design Preserved

- Blade templates untouched (except lead-form.blade.php form submission)
- Tailwind CSS styling maintained
- Existing Alpine.js and JavaScript patterns preserved
- No breaking changes to other components

### ✅ What's New

**Backend:**
- `LeadController` - Handles form submissions
- `StoreLeadRequest` - Validates form input
- `GoogleSheetsService` - Connects to Google Sheets API
- Rate limiting (5 submissions/minute per IP)
- Comprehensive error handling with user-friendly messages

**Frontend:**
- Form now posts to Laravel endpoint (`/register-interest`)
- CSRF protection enabled
- Real-time validation feedback
- Displays success/error messages
- "Submitting..." state on button

**Testing:**
- 8 feature tests covering all scenarios
- Mocks Google Sheets API (no live calls during tests)
- Tests validation, rate limiting, error handling

## Next Steps

### 1. Set Up Google Cloud (5-10 minutes)

Follow the detailed guide in [`GOOGLE_SHEETS_SETUP.md`](./GOOGLE_SHEETS_SETUP.md):

1. Create a Google Cloud Project
2. Enable Google Sheets API
3. Create a Service Account
4. Download JSON credentials
5. Create/prepare a Google Sheet
6. Share sheet with service account (Editor access)
7. Get the Spreadsheet ID

### 2. Configure Environment

Update `.env`:

```env
GOOGLE_SHEETS_SPREADSHEET_ID=your-spreadsheet-id-here
GOOGLE_SHEETS_SHEET_NAME=Leads
GOOGLE_SERVICE_ACCOUNT_JSON_PATH=/path/to/service-account.json
BBIP_WHATSAPP_NUMBER=+27XXXXXXXXX  # Optional
```

### 3. Install Dependencies

```bash
composer install
```

Or in Docker:

```bash
docker-compose exec app composer install
```

### 4. Run Tests

```bash
php artisan test
```

All tests should pass. If not, check your configuration.

### 5. Test Manually

1. Open `http://localhost:8000` in browser
2. Scroll to the form
3. Fill in valid data
4. Click "Submit & Continue"
5. Check that:
   - Success message appears
   - Form clears
   - New row appears in Google Sheet within seconds

## Configuration Reference

| Variable | Example | Purpose |
|----------|---------|---------|
| `GOOGLE_SHEETS_SPREADSHEET_ID` | `1q2w3e4r5t6y7u8i9o0` | Which Google Sheet to write to |
| `GOOGLE_SHEETS_SHEET_NAME` | `Leads` | Which tab in the sheet |
| `GOOGLE_SERVICE_ACCOUNT_JSON_PATH` | `/etc/bbip/google-service-account.json` | Where credentials file is stored |
| `BBIP_WHATSAPP_NUMBER` | `+971501234567` | Optional: WhatsApp button link |

## Form Fields Captured

| Field | Required | Validation |
|-------|----------|-----------|
| Full Name | Yes | Max 100 characters |
| Email | Yes | Valid email format |
| WhatsApp | Yes | Max 30 characters |
| Challenge | Yes | Max 2000 characters |
| Consent | Yes | Must be checked |

## Expected Google Sheet Format

Your sheet should have columns:

```
A: Timestamp        (auto-filled: 2026-08-20 10:30:45)
B: Name             (Ahmed Mohamed)
C: Email            (ahmed@example.com)
D: WhatsApp         (+971501234567)
E: Challenge        (I need help with...)
F: Source           (auto-filled: landing_page)
```

## Docker Deployment

Mount the credentials file:

```yaml
# docker-compose.yml
services:
  app:
    volumes:
      - /etc/bbip/google-service-account.json:/var/www/html/storage/app/google/service-account.json:ro
```

## Security Checklist

✅ Credentials never reach the browser  
✅ Credentials never committed to Git  
✅ CSRF protection enabled  
✅ Server-side validation on all fields  
✅ Rate limiting prevents abuse  
✅ Errors logged server-side, not exposed to user  
✅ Input sanitized and trimmed  

## Troubleshooting

### "Credentials file not found"

Check that `GOOGLE_SERVICE_ACCOUNT_JSON_PATH` points to correct file:

```bash
ls -la /path/to/service-account.json
```

### "Permission denied" from Google API

Service account email doesn't have Editor access to the sheet:

1. Open Google Sheet
2. Click "Share"
3. Add service account email: `bbip-landing@project-id.iam.gserviceaccount.com`
4. Give "Editor" access

### Form submits but doesn't appear in sheet

Check Laravel logs:

```bash
# Local
tail -f storage/logs/laravel.log

# Docker
docker-compose logs app
```

Look for Google API errors.

### Rate limit error

You're submitting more than 5 times per minute from same IP. This is intentional to prevent abuse. Wait 60 seconds before trying again.

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── LeadController.php          ← Form handler
│   └── Requests/
│       └── StoreLeadRequest.php        ← Validation
├── Services/
│   └── GoogleSheetsService.php         ← Google API wrapper
└── Providers/
    └── ...

config/
└── services.php                        ← Google config

routes/
└── web.php                             ← /register-interest route

resources/views/components/
└── lead-form.blade.php                 ← Updated form

tests/
├── Feature/
│   └── LeadSubmissionTest.php          ← Tests
└── TestCase.php
```

## Documentation

- **[IMPLEMENTATION_GUIDE.md](./IMPLEMENTATION_GUIDE.md)** - Detailed technical documentation
- **[GOOGLE_SHEETS_SETUP.md](./GOOGLE_SHEETS_SETUP.md)** - Step-by-step Google Cloud setup
- **[QUICK_START.md](./QUICK_START.md)** - This file

## What Remains Unchanged

- Landing page design
- Tailwind CSS styling
- Existing components (hero, features, testimonials, FAQ, etc.)
- Navigation and CTA buttons
- WhatsApp button integration
- Mobile responsiveness

## Next Phase: Optional Enhancements

Once the basic integration is working, you could add:

- Email confirmation to the visitor
- Webhook to send to CRM
- Duplicate email detection
- Custom form fields
- Lead scoring
- Integration with your CRM

But these are not necessary for the current implementation.

## Support

Refer to the detailed guides:
- Setup issues → See [GOOGLE_SHEETS_SETUP.md](./GOOGLE_SHEETS_SETUP.md)
- Implementation details → See [IMPLEMENTATION_GUIDE.md](./IMPLEMENTATION_GUIDE.md)
- Code examples → Check individual files with inline comments

---

**Ready to go!** Follow the "Next Steps" above and you'll have a working Google Sheets integration in ~15 minutes.
