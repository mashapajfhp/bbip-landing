# Google Sheets Integration Setup

This document explains how to configure the Google Sheets integration for the BBIP landing page form.

## Prerequisites

- A Google Cloud Project
- Admin access to Google Cloud Console
- A Google Sheet to store leads
- Docker running (for the Laravel application)

## Step 1: Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click the project dropdown at the top
3. Click "New Project"
4. Name it "BBIP Landing Page" and click "Create"
5. Wait for the project to be created

## Step 2: Enable Google Sheets API

1. In the Google Cloud Console, search for "Google Sheets API"
2. Click on "Google Sheets API"
3. Click "Enable"

## Step 3: Create a Service Account

1. In the left sidebar, go to **APIs & Services** → **Credentials**
2. Click **Create Credentials** → **Service Account**
3. Fill in the form:
   - **Service account name**: `bbip-landing`
   - **Service account ID**: (auto-filled)
   - Click **Create and Continue**
4. On the next page, click **Continue** (no need to grant roles yet)
5. On the final page, click **Done**

## Step 4: Generate Service Account JSON Key

1. In **APIs & Services** → **Credentials**, find the service account you just created
2. Click on the email address of the service account
3. Go to the **Keys** tab
4. Click **Add Key** → **Create new key**
5. Choose **JSON** and click **Create**
6. A JSON file will download automatically - **save this securely**

## Step 5: Prepare the Service Account Credentials

The credentials file will look something like this:

```json
{
  "type": "service_account",
  "project_id": "your-project-id",
  "private_key_id": "...",
  "private_key": "...",
  "client_email": "bbip-landing@your-project-id.iam.gserviceaccount.com",
  "client_id": "...",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "..."
}
```

### For Docker Deployment:

1. Copy this JSON file to your server's secure location, e.g.:
   ```
   /etc/bbip/google-service-account.json
   ```

2. Ensure proper permissions:
   ```bash
   chmod 600 /etc/bbip/google-service-account.json
   chown www-data:www-data /etc/bbip/google-service-account.json
   ```

3. Mount it in your Docker container by updating your docker-compose.yml or Docker run command:
   ```yaml
   volumes:
     - /etc/bbip/google-service-account.json:/var/www/html/storage/app/google/service-account.json:ro
   ```

### For Local Development:

1. Place the JSON file in:
   ```
   storage/app/google/service-account.json
   ```

2. Add this to `.gitignore` (if not already there):
   ```
   storage/app/google/
   ```

## Step 6: Create or Prepare Your Google Sheet

1. Go to [Google Sheets](https://sheets.google.com/)
2. Create a new spreadsheet, or open an existing one
3. Rename the first sheet to **"Leads"** (or note the name for configuration)
4. Add column headers in the first row:
   ```
   Timestamp | Name | Email | WhatsApp | Challenge | Source
   ```
   - Column A: `Timestamp`
   - Column B: `Name`
   - Column C: `Email`
   - Column D: `WhatsApp`
   - Column E: `Challenge`
   - Column F: `Source`

5. Get the **Spreadsheet ID** from the URL:
   ```
   https://docs.google.com/spreadsheets/d/{SPREADSHEET_ID}/edit
   ```

## Step 7: Share the Google Sheet with the Service Account

1. Copy the **service account email** from the JSON file (e.g., `bbip-landing@your-project-id.iam.gserviceaccount.com`)
2. Open your Google Sheet
3. Click **Share** (top right)
4. Paste the service account email
5. Select **Editor** permissions
6. Uncheck "Notify people" and click **Share**

## Step 8: Configure Environment Variables

Update your `.env` file with the values from the Google Sheet and service account:

```env
# Google Sheets Integration
GOOGLE_SHEETS_SPREADSHEET_ID=your-spreadsheet-id-here
GOOGLE_SHEETS_SHEET_NAME=Leads
GOOGLE_SERVICE_ACCOUNT_JSON_PATH=/var/www/html/storage/app/google/service-account.json
```

- **GOOGLE_SHEETS_SPREADSHEET_ID**: Copy from step 6
- **GOOGLE_SHEETS_SHEET_NAME**: The name of the sheet tab (default: "Leads")
- **GOOGLE_SERVICE_ACCOUNT_JSON_PATH**: Path to the credentials file inside the container

## Step 9: Install Dependencies

Run composer install to fetch the Google API client:

```bash
composer install
```

Or in Docker:

```bash
docker-compose exec app composer install
```

## Step 10: Test the Integration

Submit a form on the landing page and verify:

1. The form submission succeeds (no error message shown)
2. A new row appears in your Google Sheet within a few seconds
3. The timestamp and all fields are correctly populated

## Troubleshooting

### "Credentials file not found"

- Verify the file path in `GOOGLE_SERVICE_ACCOUNT_JSON_PATH`
- For Docker, ensure the volume mount is correct
- Check file permissions: `ls -la /path/to/file`

### "Failed to append lead to Google Sheets"

- Check that the service account email has **Editor** access to the sheet
- Verify the spreadsheet ID is correct
- Check the Laravel logs: `docker-compose logs app`
- Ensure the Google Sheets API is enabled in Google Cloud Console

### "Sheet not found"

- Verify the `GOOGLE_SHEETS_SHEET_NAME` matches exactly (case-sensitive)
- Check that the sheet tab exists in your spreadsheet

## Security Notes

- **Never** commit the service account JSON file to Git
- **Never** paste credentials in `.env.example` or other tracked files
- Store the JSON file on your server with restricted permissions
- Use environment variables to pass the file path to the application
- In production, use a secrets manager (e.g., Docker Secrets, AWS Secrets Manager)

## Rotating Credentials

If you need to rotate the service account key:

1. In Google Cloud Console, delete the old key from the service account
2. Create a new key and download the JSON
3. Replace the file on your server
4. Restart the Laravel application

---

For more information, see:
- [Google Sheets API Documentation](https://developers.google.com/sheets/api)
- [Service Accounts in Google Cloud](https://cloud.google.com/iam/docs/service-accounts)
