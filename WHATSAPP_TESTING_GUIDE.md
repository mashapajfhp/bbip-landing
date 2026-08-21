# WhatsApp Integration Testing Guide

Quick reference for testing the WhatsApp Business integration during development and QA.

## Quick Start

### Run All Tests
```bash
php artisan test tests/Feature/WhatsAppIntegrationTest.php
```

### Run Specific Test Category
```bash
# Component rendering tests
php artisan test tests/Feature/WhatsAppIntegrationTest.php --filter "component_renders"

# URL generation tests
php artisan test tests/Feature/WhatsAppIntegrationTest.php --filter "url"

# Accessibility tests
php artisan test tests/Feature/WhatsAppIntegrationTest.php --filter "accessibility"
```

## Manual Testing Checklist

### 1. Configuration Setup
- [ ] `.env` has `WHATSAPP_NUMBER` set (e.g., `971501234567`)
- [ ] `.env` has `WHATSAPP_DEFAULT_MESSAGE` set
- [ ] `config/services.php` has WhatsApp configuration
- [ ] No hardcoded numbers in Blade templates

### 2. Landing Page
- [ ] Page loads without errors
- [ ] Floating WhatsApp button visible (bottom-right corner)
- [ ] Button responsive (check mobile size)
- [ ] Button does NOT cover important content
- [ ] Hover effect works (color change)

### 3. Navbar Buttons
**Desktop:**
- [ ] Navbar shows "Chat on WhatsApp" button
- [ ] Button is clickable
- [ ] Opens WhatsApp in new tab

**Mobile:**
- [ ] Mobile menu shows WhatsApp button
- [ ] Button is full width
- [ ] Proper spacing with "Get Started" button

### 4. Hero Section
- [ ] "Chat on WhatsApp" button visible
- [ ] Button next to "Explore Programs"
- [ ] Proper styling (white with emerald dot)
- [ ] Clickable and opens new tab

### 5. Lead Form Section
- [ ] "Prefer WhatsApp? Chat now" link visible
- [ ] "Send via WhatsApp" button in form
- [ ] Form validation works before WhatsApp open
- [ ] Pre-filled message includes form data

### 6. WhatsApp Link Verification

**Click buttons and verify:**
1. Opens WhatsApp (app on mobile, web on desktop)
2. Correct phone number is pre-populated
3. Default/custom message appears in text field
4. Message is readable (proper encoding)

**Test URLs directly:**
```
# Standard button (default message)
https://wa.me/971501234567?text=Hello%2C%20I%20would%20like%20to%20know%20more.

# Custom message
https://wa.me/971501234567?text=Hello%2C%20I%20have%20a%20question%20about%20registration.
```

### 7. Accessibility Testing

#### Keyboard Navigation
- [ ] Tab to WhatsApp button works
- [ ] Enter key activates button
- [ ] Focus outline visible

#### Screen Reader (NVDA/JAWS/VoiceOver)
- [ ] Button labeled as "Chat with us on WhatsApp"
- [ ] Link purpose clear
- [ ] Icon has proper context

#### Browser Tools
```bash
# Check accessibility in Chrome DevTools
# Right-click button → Inspect → Accessibility panel
# Verify: Name, Role, State
```

### 8. Device Testing

#### Mobile (iOS)
- [ ] WhatsApp app opens (if installed)
- [ ] Falls back to web.whatsapp.com (if not)
- [ ] Floating button visible, not covering content
- [ ] Touch target size adequate (44x44px minimum)

#### Mobile (Android)
- [ ] WhatsApp app opens
- [ ] Message pre-filled correctly
- [ ] Keyboard doesn't hide button

#### Desktop
- [ ] Opens WhatsApp Web
- [ ] New tab opens correctly
- [ ] No popup blockers interfere

### 9. Browser Compatibility

Test in:
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)

### 10. Analytics Tracking

**Check HTML attributes:**
```bash
# Open browser DevTools → Elements
# Find WhatsApp button
# Verify these attributes:

# On floating button:
data-cta="whatsapp"
data-location="floating"

# On hero button:
data-cta="whatsapp"
data-location="hero"
```

### 11. Configuration Edge Cases

#### Test: No Number Configured
1. Set `WHATSAPP_NUMBER=` (empty)
2. Reload page
3. Verify:
   - [ ] No WhatsApp buttons visible
   - [ ] No broken `wa.me` links
   - [ ] No JavaScript errors

#### Test: Number with Special Characters
1. Set `WHATSAPP_NUMBER=+971 (50) 123-4567`
2. Click button
3. Verify URL shows: `971501234567` (normalized)

#### Test: Different Numbers
1. Try different formats:
   - `971501234567` ✓
   - `+971501234567` ✓
   - `+971 50 123 4567` ✓
   - `971-50-123-4567` ✓
   - All should normalize to `971501234567`

### 12. Message Encoding

Test special characters in messages:
- [ ] Default message: "Hello, I would like to know more." ✓
- [ ] Punctuation: "Hello! How are you?" ✓
- [ ] Ampersand: "Help & support" ✓
- [ ] Arabic: "مرحبا، أود أن أعرف المزيد" ✓
- [ ] Emoji: "Hi 👋 from the landing page" ✓

### 13. Form Integration

**Test "Send via WhatsApp" button:**
1. [ ] Fill form with data
2. [ ] Click "Send via WhatsApp"
3. [ ] Verify WhatsApp opens with:
   - [ ] Your name from form
   - [ ] Email from form
   - [ ] WhatsApp from form
   - [ ] Challenge/message from form
4. [ ] Verify regular "Submit" still works for Google Sheets

### 14. Visual QA

#### Desktop View (1920px+)
- [ ] Floating button: 64x64px (w-16 h-16)
- [ ] Bottom: 24px (bottom-6)
- [ ] Right: 24px (right-6)
- [ ] Color: Emerald-500
- [ ] Shadow visible
- [ ] Icon centered

#### Tablet View (768px - 1023px)
- [ ] Floating button: 56x56px (w-14 h-14)
- [ ] Responsive size change working
- [ ] Still visible and accessible

#### Mobile View (375px)
- [ ] Floating button: 56x56px (w-14 h-14)
- [ ] Does NOT cover bottom navigation
- [ ] Safe area respected
- [ ] Touch area 44x44px minimum

### 15. Performance

```bash
# Check in Chrome DevTools → Network
# WhatsApp button should:
- [ ] Load inline (no external images/CSS)
- [ ] No render blocking
- [ ] No layout shift (CLS = 0)
- [ ] SVG icon embedded (<1KB)
```

### 16. Security Verification

```bash
# Check no secrets are exposed:
grep -r "WHATSAPP_NUMBER" resources/views/
# Should only find references to config(), not raw env()

grep -r "wa.me" resources/views/
# Should be in components only, not hardcoded elsewhere
```

### 17. Regression Testing

After making changes, verify:
- [ ] Google Sheets form still works
- [ ] Form data still sent correctly
- [ ] Navbar layout unchanged
- [ ] Hero section responsive
- [ ] No console errors
- [ ] No CSS conflicts
- [ ] No JavaScript errors

## Automated Testing

Run all feature tests:
```bash
php artisan test tests/Feature/WhatsAppIntegrationTest.php --no-coverage
```

Expected output:
```
✓ 30 tests passed (52ms)
```

## Common Issues & Solutions

### Issue: Button not visible
**Solution:**
1. Check `.env` has `WHATSAPP_NUMBER` configured
2. Verify `config/services.php` loaded
3. Run `php artisan config:clear`

### Issue: Wrong URL format
**Solution:**
1. Check number format (no +, spaces, etc.)
2. Verify message is URL encoded
3. Test URL directly: `https://wa.me/[number]?text=[encoded_message]`

### Issue: Form data not in WhatsApp message
**Solution:**
1. Check form JavaScript in lead-form.blade.php
2. Verify form values captured correctly
3. Check URL encoding of message

### Issue: Button styles wrong
**Solution:**
1. Check Tailwind CSS included
2. Verify CSS classes correct (btn-whatsapp, wa-dot)
3. Check color scheme (emerald-500)

## Testing Timeline

- **Before Commit:** Run all automated tests
- **Before PR:** Manual testing on mobile + desktop
- **Before Deploy:** Full QA checklist
- **After Deploy:** Spot check on production

## Debugging Tips

### View Component Rendering
```php
// In tinker or test
php artisan tinker
>>> view('components.whatsapp-button', ['message' => 'Test'])->render();
```

### Check Configuration
```php
>>> config('services.whatsapp')
=> [
    "number" => "971501234567",
    "default_message" => "Hello, I would like to know more.",
]
```

### Test URL Generation
```php
$number = '971501234567';
$message = 'Hello';
$url = 'https://wa.me/' . preg_replace('/\D/', '', $number) . '?text=' . urlencode($message);
// Open in browser to test
```

## Sign-Off Checklist

- [ ] All automated tests pass
- [ ] Manual testing completed on mobile + desktop
- [ ] No console errors
- [ ] No security issues
- [ ] Accessibility verified
- [ ] Performance acceptable
- [ ] Documentation updated
- [ ] Ready for production

## Contact

For issues or questions:
1. Check WHATSAPP_TESTS.md for detailed test info
2. Review component code in resources/views/components/
3. Check configuration in config/services.php
