# 💧 AquaFarm — Water Usage Optimization Dashboard

A complete PHP MVC web application for agricultural water management.

---

## 🚀 HOW TO RUN (XAMPP)

### Step 1 — Copy Project
Place the `water-dashboard` folder inside:
```
C:\xampp\htdocs\water-dashboard
```

### Step 2 — Start XAMPP
- Start **Apache** and **MySQL** in XAMPP Control Panel

### Step 3 — Import Database
1. Open browser → `http://localhost/phpmyadmin`
2. Click **New** → create database named `water_dashboard`
3. Click **Import** → choose `database/schema.sql` → click Go

### Step 4 — Configure DB (if needed)
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'water_dashboard');
define('DB_USER', 'root');
define('DB_PASS', '');   // add password if you have one
```

### Step 5 — Enable mod_rewrite
In `C:\xampp\apache\conf\httpd.conf`, find and uncomment:
```
LoadModule rewrite_module modules/mod_rewrite.so
```
Also find `AllowOverride None` (for htdocs) and change to `AllowOverride All`

### Step 6 — Open in Browser
```
http://localhost/water-dashboard/public
```

---

## 🔐 LOGIN CREDENTIALS

| Role   | Email                  | Password  |
|--------|------------------------|-----------|
| Admin  | admin@waterdash.com    | password  |
| Farmer | rajesh@farm.com        | password  |
| Farmer | priya@farm.com         | password  |
| Farmer | mohan@farm.com         | password  |
| Farmer | sunita@farm.com        | password  |
| Farmer | vikram@farm.com        | password  |

---

## 📁 PROJECT STRUCTURE

```
water-dashboard/
├── app/
│   ├── controllers/       AuthController, DashboardController, AdminController,
│   │                      WaterController, CropController, IrrigationController
│   ├── core/              App, Router, Controller, Model, Database, Helper
│   ├── models/            UserModel, FarmerModel, WaterUsageModel, CropModel, IrrigationModel
│   └── views/
│       ├── auth/          login.php, register.php
│       ├── dashboard/     farmer.php, admin.php
│       ├── farmers/       list, view, crops, profile, addcrop, editcrop
│       ├── water/         index, add, edit, irrigation
│       ├── reports/       index.php
│       └── layouts/       main.php, auth.php, 404.php
├── config/                config.php
├── public/
│   ├── css/               style.css
│   ├── js/                main.js
│   └── index.php          ← Entry point
├── database/              schema.sql
└── .htaccess
```

---

## ✅ FEATURES

### Admin
- Dashboard with 6 KPI cards
- All farmers list with CRUD
- Global water usage charts (monthly, crop-wise, method-wise, farmer comparison)
- Water usage records (all farmers)
- Reports & analytics with print support
- Farmer activate/deactivate/delete

### Farmer
- Personal dashboard with 4 stat cards
- Daily/Monthly/Crop-wise/Method-wise charts (Chart.js)
- Log water usage (CRUD)
- Manage crops (CRUD)
- Irrigation schedule (add/complete/delete)
- Water saving suggestions (smart tips)
- Profile management

### Security
- PDO prepared statements (SQL injection proof)
- password_hash() / password_verify()
- Session management with role-based access
- XSS protection with htmlspecialchars / e()
- CSRF token on forms

---

## 🛠 TECH STACK

- **Backend:** PHP 8+ (Core, no framework)
- **Database:** MySQL via PDO
- **Frontend:** Bootstrap 5, Chart.js, Font Awesome, Space Grotesk font
- **Architecture:** MVC (Model-View-Controller)
- **Server:** XAMPP (Apache + MySQL)

---

## 🎨 UI FEATURES

- Dark + Green agricultural theme
- Responsive sidebar navigation
- Animated stat cards
- Interactive Chart.js graphs
- Dark/Light mode toggle
- Mobile friendly
- Print-ready reports
