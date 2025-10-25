# 🏥 Hospital Information System

A web-based Hospital Information System built with **Laravel**.  
This system focuses on managing **outpatient services**, including **pharmacy operations**, **patient registration**, and more.

## 🚀 Features

- Outpatient registration and patient management  
- Pharmacy module (dispensing, inventory, prescriptions)  
- Role-based authentication and access control (via spatie's laravel-permission)  
- Real-time notifications (Laravel Echo + Reverb)  
- Queue and background job support  
- Printable documents via QZ Tray  
- BPJS Integration
- Stripe Integration

## ⚙️ Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/Ikhwanu-Robik/Hospital-Information-System.git
cd Hospital-Information-System
```
---

### 2. Install Dependencies

#### 🧩 PHP & Laravel dependencies

```bash
composer install
```

#### 🧩 Front-end dependencies

```bash
npm install
```

(Optional, for production build)

```bash
npm run build
```

---

### 3. Configure Environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Then, open `.env` and set your environment variables:

```dotenv
APP_NAME="Hospital Information System"
APP_URL=https://chirper.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=his
DB_USERNAME=root
DB_PASSWORD=

# Reverb / WebSocket (for real-time features)
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080

# Stripe Integration
STRIPE_SECRET_KEY="sk_thestripesecretkey"
STRIPE_CURRENCY="DEFAULT CURRENCY (3 Letters Code)"
STRIPE_WEBHOOK_SECRET="whsec_stripewebhooksecret"

# BPJS Integration
BPJS_CONS_ID="bpjs-cons-id"
BPJS_API_URL="https://api.bpjs.co.id"
BPJS_EMAIL="bpjs@menkes.co.id"

# QZ Tray Message Signing and Certificate
QZ_TRAY_PRIVATE_KEY_PATH=/env/www/qztray/private.pem
QZ_TRAY_CERTIFICATE_PATH=/env/www/qztray/certificate.txt
```

Then generate the application key:

```bash
php artisan key:generate
```

---

### 4. Database Setup

Run migrations and seeders:

```bash
php artisan migrate --seed
```

This will create all necessary tables and insert initial data (e.g., admin user, roles).

---

### 5. Install QZ Tray (for Printing)

QZ Tray is used to print receipts, prescriptions, and labels directly to a local printer.

#### Steps:

1. Download and install QZ Tray:
   👉 [https://qz.io/download/](https://qz.io/download/)
2. Launch QZ Tray — it should run in your system tray.
3. Ensure the HIS web app can connect to QZ Tray (the browser may ask for permission).
4. Get a certificate and private key or sign it yourself.

---

### 5.5 Install BPJS Dummy

1. [Install Mockoon](https://mockoon.com/download/)
2. Import the *HIS-BPJS-Dummy.json* into Mockoon
3. Run it with Mockoon

### 6. Run the Application

#### 🖥️ Start the development server

```bash
php artisan serve
```

#### 🔄 Start the WebSocket server (Reverb)

```bash
php artisan reverb:start
```

#### ⚙️ Start the queue worker (for background jobs)

```bash
php artisan queue:work
```

---

## 🧰 Optional Commands

### Clear and optimize cache

```bash
php artisan optimize:clear
```

### Rebuild front-end assets during development

```bash
npm run dev
```

---

## 🧑‍💻 Default Login (after seeding)

| Role  | Email                                         | Password |
| ----- | --------------------------------------------- | -------- |
| super admin | [superadmin@mail.com](mailto:superadmin@mail.com) | 12345678 |

---
## ❗Manual Setting

1. **Doctor Ping Interval**
The application determines whether a doctor is online or not with a ping. If the doctor's last ping is greater than 2 times the ping interval, the doctor is deemed offline and not ready to receive patient. **THE PING INTERVAL MUST BE SET BY THE SUPER ADMIN.**

2. **Thermal Printer**
The application also prints to a default **THERMAL PRINTER SET BY THE SUPER ADMIN**. QZ Tray must be running before selecting the default printer, since the printer data are received from QZ Tray. The thermal printer also needs to support ESC/POS.
