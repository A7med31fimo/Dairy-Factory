# PDF Setup - mPDF for Arabic RTL Support

## Changes Made

The project has been switched from **dompdf** to **mPDF** for better Arabic and RTL support.

### Why mPDF?
- ✅ **Native RTL support** - just use `dir="rtl"` in HTML
- ✅ **Built-in Arabic text shaping** - no manual character manipulation needed
- ✅ **Better font handling** - supports DejaVu Sans with full Arabic coverage
- ✅ **Proper table alignment** for RTL layouts
- ✅ **Correct margin/padding** in RTL direction

## Installation

After extracting this project, run:

```bash
composer update
```

This will install mPDF (version ^8.2).

## How It Works

### ReportController.php
The PDF method now uses mPDF with RTL configuration:

```php
$mpdf = new Mpdf([
    'mode'              => 'utf-8',
    'format'            => 'A4',
    'default_font'      => 'dejavusans',
    'directionality'    => 'rtl',  // ← RTL support!
    'autoScriptToLang'  => true,
    'autoLangToFont'    => true,
]);
```

### PDF View (resources/views/reports/pdf.blade.php)
Clean, simple HTML with RTL:

```html
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Normal Arabic text - no reshaping needed! -->
    <h1>مصنع الألبان</h1>
    <p>{{ $data }}</p>
</body>
</html>
```

## Features

- 📄 Full RTL layout (text, tables, margins)
- 🔤 Perfect Arabic text rendering
- 📊 Properly aligned tables and data
- 🎨 Clean, maintainable HTML
- 🚀 No complex text reshaping logic needed

## Testing

Generate a PDF report from the Reports page. The output should show:
- Arabic text reading right-to-left
- Tables aligned to the right
- Proper margins and spacing
- Connected Arabic letters

---

**Previous Setup:** dompdf + manual Arabic reshaping helper
**Current Setup:** mPDF with native RTL support ✨
