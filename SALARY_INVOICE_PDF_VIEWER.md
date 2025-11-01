# Salary Invoice PDF/HTML Viewer Implementation

## Overview
Added functionality to view and download salary invoice PDFs and HTML files directly from the Voyager admin panel's Salary Invoice Logs table.

## Features

### 1. **View PDF Button** (Green)
- Opens the PDF invoice in a new browser tab
- Allows inline viewing in the browser
- Icon: File text icon

### 2. **Download PDF Button** (Blue)
- Downloads the PDF invoice to the user's computer
- File is saved with the original filename (e.g., SAL-2-202510.pdf)
- Icon: Download icon

### 3. **View HTML Button** (Info/Cyan)
- Opens the HTML invoice in a new browser tab
- Useful for quick preview or printing
- Icon: World/Globe icon

## Files Created/Modified

### New Files

**1. Controller: `app/Http/Controllers/SalaryInvoiceController.php`**
- `viewPdf()` - Serves PDF files from storage
- `viewHtml()` - Serves HTML files from storage
- Security features:
  - Validates path doesn't contain `..` (directory traversal prevention)
  - Ensures path starts with `invoices/`
  - Checks file existence before serving

**2. Voyager Actions:**
- `app/Actions/ViewSalaryInvoicePdfAction.php`
- `app/Actions/DownloadSalaryInvoicePdfAction.php`
- `app/Actions/ViewSalaryInvoiceHtmlAction.php`

### Modified Files

**1. `routes/web.php`**
Added routes:
```php
Route::get('salary-invoice/pdf', [SalaryInvoiceController::class, 'viewPdf'])
    ->name('salary-invoice.pdf');
Route::get('salary-invoice/html', [SalaryInvoiceController::class, 'viewHtml'])
    ->name('salary-invoice.html');
```

**2. `app/Providers/AppServiceProvider.php`**
Registered Voyager actions:
```php
Voyager::addAction(ViewSalaryInvoicePdfAction::class);
Voyager::addAction(DownloadSalaryInvoicePdfAction::class);
Voyager::addAction(ViewSalaryInvoiceHtmlAction::class);
```

## How It Works

### URL Structure

**View PDF:**
```
https://portal.webpenter.com/admin/salary-invoice/pdf?path=invoices/2025-10/SAL-2-202510.pdf
```

**Download PDF:**
```
https://portal.webpenter.com/admin/salary-invoice/pdf?path=invoices/2025-10/SAL-2-202510.pdf&download=true
```

**View HTML:**
```
https://portal.webpenter.com/admin/salary-invoice/html?path=invoices/2025-10/SAL-2-202510.html
```

### Security Features

1. **Path Validation:**
   - Rejects paths containing `..` (prevents directory traversal attacks)
   - Only allows paths starting with `invoices/`
   - Returns 403 Forbidden for invalid paths

2. **File Existence Check:**
   - Verifies file exists in storage before serving
   - Returns 404 Not Found if file doesn't exist

3. **Laravel Storage:**
   - Uses Laravel's Storage facade
   - Files stored in `storage/app/invoices/`
   - Protected from direct web access

### Button Display Logic

Buttons only appear when:
1. User is viewing the `salary-invoice-logs` table in Voyager
2. The record has a valid `pdf_path` or `invoice_path`
3. User has `read` permission for the resource

### Visual Appearance

In the Voyager admin panel, each salary invoice log record will show three buttons:

```
[View PDF] [Download PDF] [View HTML]
  Green       Blue          Cyan
```

All buttons have appropriate icons and open in new tabs (except download).

## Database Schema

The `salary_invoice_logs` table contains:
- `pdf_path` - Relative path to PDF (e.g., `invoices/2025-10/SAL-2-202510.pdf`)
- `invoice_path` - Relative path to HTML (e.g., `invoices/2025-10/SAL-2-202510.html`)

## Usage for Admins

1. Navigate to **Admin Panel → Salary Invoice Logs**
2. Find the salary record you want to view
3. Click one of the buttons:
   - **View PDF** - View in browser
   - **Download PDF** - Save to computer
   - **View HTML** - View HTML version

## Usage for Developers

### Adding More Actions

To add custom actions to other Voyager tables:

1. Create action class in `app/Actions/`
2. Extend `TCG\Voyager\Actions\AbstractAction`
3. Implement required methods
4. Register in `AppServiceProvider.php`

### Serving Other File Types

Modify `SalaryInvoiceController.php` to add methods for other file types:

```php
public function viewExcel(Request $request)
{
    // Similar to viewPdf but with Excel MIME type
    return response($fileContents, 200)
        ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
}
```

## Testing

### Manual Testing Steps

1. **View PDF:**
   - Click "View PDF" button
   - PDF should open in new tab
   - Should display correctly

2. **Download PDF:**
   - Click "Download PDF" button
   - File should download
   - Check filename is correct

3. **View HTML:**
   - Click "View HTML" button
   - HTML should open in new tab
   - Should display invoice correctly

### Security Testing

1. Try accessing with invalid path:
   ```
   /admin/salary-invoice/pdf?path=../../../etc/passwd
   ```
   Expected: 403 Forbidden

2. Try accessing non-invoice file:
   ```
   /admin/salary-invoice/pdf?path=logs/laravel.log
   ```
   Expected: 403 Forbidden

3. Try accessing non-existent file:
   ```
   /admin/salary-invoice/pdf?path=invoices/2025-10/fake.pdf
   ```
   Expected: 404 Not Found

## Troubleshooting

### Buttons Not Showing

**Check:**
1. Cache cleared: `php artisan config:clear && php artisan cache:clear`
2. Actions registered in `AppServiceProvider.php`
3. Database has `pdf_path` and `invoice_path` values
4. Voyager table slug is `salary-invoice-logs`

### PDF Not Loading

**Check:**
1. File exists in `storage/app/invoices/`
2. Laravel has read permissions
3. Path is correct in database
4. Storage link configured: `php artisan storage:link`

### 403 or 404 Errors

**Check:**
1. User logged into admin panel
2. User has read permission
3. Path format is correct
4. File exists in storage

## Future Enhancements

Possible improvements:
1. Add email sending from Voyager panel
2. Add regenerate invoice button
3. Add batch download for multiple invoices
4. Add invoice preview thumbnails
5. Add filtering by month/user
6. Add invoice statistics dashboard

---
**Created:** November 1, 2025
**Files Added:** 4
**Files Modified:** 2
**Security Level:** High
