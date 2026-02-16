# 🏭 نظام إدارة مصنع الألبان
## Dairy Factory Management System

نظام إدارة متكامل لمصانع الألبان الصغيرة، مبني بـ Laravel 11 مع واجهة عربية متجاوبة.

---

## 📋 المتطلبات | Requirements

| المتطلب | الإصدار |
|---------|---------|
| PHP     | ^8.2    |
| MySQL   | ^8.0    |
| Composer| ^2.0    |
| Node.js | ^18 (optional) |

---

## 🚀 التثبيت السريع | Quick Install

### الخطوة 1: نسخ المشروع
```bash
git clone https://github.com/your-repo/dairy-system.git
cd dairy-system
```

### الخطوة 2: تثبيت الحزم
```bash
composer install
```

### الخطوة 3: إعداد ملف البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### الخطوة 4: إعداد قاعدة البيانات
```bash
# في .env، عدّل:
DB_DATABASE=dairy_system
DB_USERNAME=root
DB_PASSWORD=your_password

# إنشاء الجداول:
php artisan migrate

# إضافة المستخدم الافتراضي:
php artisan db:seed
```

### الخطوة 5: تشغيل المشروع
```bash
php artisan serve
```

ثم افتح المتصفح على: **http://localhost:8000**



> ⚠️ **مهم:** غيّر كلمة المرور فور تسجيل الدخول الأول!

---

## 🗄️ هيكل قاعدة البيانات | Database Schema

```
users                    → المستخدمون
├── id, name, email, password, role

milk_collections         → جمع الحليب من المزارعين
├── id, farmer_name, driver_name, vehicle_number
├── quantity_liters, price_per_liter, total_amount
├── collection_date, notes, user_id

productions              → الإنتاج (تحويل الحليب)
├── id, product_type, product_name
├── quantity, unit, production_date
├── notes, user_id

distributions            → التوزيع للمحلات
├── id, shop_name, driver_name, vehicle_number
├── total_value, delivery_date, notes, user_id

distribution_items       → تفاصيل منتجات التوزيع
├── id, distribution_id, product_name
├── quantity, unit, unit_price, subtotal

debts                    → الديون
├── id, debtor_name, reason, total_amount
├── paid_amount, status, debt_date, notes, user_id

debt_payments            → دفعات سداد الديون
├── id, debt_id, amount, payment_date, notes, user_id

expenses                 → المصروفات
├── id, amount, category, expense_date, notes, user_id
```

---

## 📱 الميزات | Features

### العمليات الأساسية:
- ✅ **جمع الحليب** - تسجيل كميات الحليب من المزارعين مع حساب تلقائي للإجمالي
- ✅ **الإنتاج** - تتبع تحويل الحليب لمنتجات (حليب، زبادي، زبدة، جبن، قشدة)
- ✅ **التوزيع** - تسجيل توصيل المنتجات للمحلات مع تفاصيل كل منتج
- ✅ **الديون** - متابعة ديون المحلات والأشخاص مع سجل الدفعات
- ✅ **المصروفات** - تسجيل المصروفات اليومية (وقود، صيانة، رواتب، متفرقات)

### التقارير:
- 📊 تقرير اليوم
- 📊 تقرير آخر 7 أيام
- 📊 تقرير آخر 30 يوم
- 📊 تقرير بتاريخ محدد
- 📄 تصدير PDF
- 🖨️ طباعة مباشرة

---

## 🎨 التصميم | Design

- **اللغة**: عربية كاملة (RTL)
- **الإطار**: Bootstrap 5 RTL
- **الخط**: Noto Sans Arabic (Google Fonts)
- **متجاوب**: يعمل على موبايل، تابلت، وكمبيوتر
- **Bottom Nav**: شريط تنقل سفلي للموبايل
- **ألوان**: أخضر طبيعي يناسب قطاع الألبان

---

## 📦 حزم Laravel المستخدمة | Packages

| الحزمة | الغرض |
|--------|-------|
| `khaled.alshamaa/ar-php and mpdf/mpdf` | إنشاء ملفات PDF |
| `laravel/sanctum` | المصادقة |

---

## 🏗️ هيكل المشروع | Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── MilkCollectionController.php
│   │   ├── ProductionController.php
│   │   ├── DistributionController.php
│   │   ├── DebtController.php
│   │   ├── ExpenseController.php
│   │   └── ReportController.php
│   └── Middleware/
│       └── AuthenticateUser.php
├── Models/
│   ├── User.php
│   ├── MilkCollection.php
│   ├── Production.php
│   ├── Distribution.php
│   ├── DistributionItem.php
│   ├── Debt.php
│   ├── DebtPayment.php
│   └── Expense.php
database/
├── migrations/
│   ├── 2024_01_01_000001_create_users_table.php
│   ├── 2024_01_01_000002_create_milk_collections_table.php
│   ├── 2024_01_01_000003_create_productions_table.php
│   ├── 2024_01_01_000004_create_distributions_table.php
│   ├── 2024_01_01_000005_create_debts_table.php
│   └── 2024_01_01_000006_create_expenses_table.php
└── seeders/
    └── DatabaseSeeder.php
resources/views/
├── layouts/app.blade.php
├── auth/login.blade.php
├── dashboard/index.blade.php
├── milk/          (index, create, edit)
├── production/    (index, create, edit)
├── distribution/  (index, create, show)
├── debts/         (index, create, edit, show)
├── expenses/      (index, create, edit)
└── reports/       (index, show, pdf)
routes/web.php
```

---

## 🔧 إعداد dompdf للعربية

في `config/dompdf.php`:
```php
'options' => [
    'defaultFont' => 'dejavusans',
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,
    'chroot' => public_path(),
]
```

---

## 📞 الدعم | Support

للمساعدة في التثبيت أو التطوير، تواصل معي 01004860997.

---

## 📄 الترخيص | License

MIT License - مفتوح المصدر للاستخدام التجاري والشخصي
