# Asian Cargo — Website with Admin Panel

A complete logistics/cargo website built with **PHP + MySQL** — easy to host on any shared hosting (Hostinger, GoDaddy, cPanel, etc.) without needing special server setup.

---

## 📦 What's Included

**Public Website:**
- Home page with live shipment tracking widget
- Services page (Air, Ocean, Express, Road freight)
- Shipment tracking page (public — anyone with a tracking number can check status)
- Quote / booking request form
- Branches page
- About Us page
- Contact form
- WhatsApp floating button
- Fully responsive (mobile, tablet, desktop)

**Admin Panel** (`/admin`):
- Secure login (with brute-force protection)
- Dashboard with key stats
- Add / edit / delete shipments
- Update shipment status (creates a tracking history entry visible to customers instantly)
- View & manage quote requests
- View & manage contact messages
- Manage branches
- Manage site settings (phone, email, WhatsApp, social links, stats)
- Manage admin users (Super Admin only)

---

## 🚀 How to Deploy on Your Hosting (Step by Step)

### Step 1 — Upload the files
1. Log in to your hosting **cPanel** (or whatever control panel your host uses).
2. Open **File Manager**, go to `public_html` (or your domain's root folder).
3. Upload **all files from this project** into that folder — keep the folder structure exactly as it is.
   - If your host gives you a subfolder for a subdomain (e.g. `public_html/cargo`), upload there instead.

### Step 2 — Create the database
1. In cPanel, open **MySQL Databases**.
2. Create a new database — note its full name (hosts usually prefix it, e.g. `username_asiancargo`).
3. Create a new database user with a strong password — note the username and password.
4. Add that user to the database with **All Privileges**.

### Step 3 — Import the database structure
1. In cPanel, open **phpMyAdmin**.
2. Select your new database on the left.
3. Click the **Import** tab.
4. Choose the file `database/schema.sql` from this project → click **Go**.
5. Repeat the same Import step for `database/seed.sql` (this adds the default admin login and sample data).

### Step 4 — Connect the website to your database
1. Open the file `config/database.php` (use cPanel's File Manager "Edit" feature, or download/edit/re-upload).
2. Update these 4 lines with the details from Step 2:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'your_actual_database_name');
   define('DB_USER', 'your_actual_database_username');
   define('DB_PASS', 'your_actual_database_password');
   ```
3. Save the file.

### Step 5 — Visit your site
- Your public site is now live at your domain.
- Admin panel: `https://yourdomain.com/admin/`
- **Default login:**
  - Username: `admin`
  - Password: `Admin@123`

### Step 6 — ⚠️ IMPORTANT: Change the default password immediately
1. Log into `/admin/`
2. Go to **Admin Users** → create yourself a new account with your own username/password and Super Admin role.
3. Log out, log back in with your new account.
4. Delete or disable the default `admin` account (or at minimum change its password by creating a new admin and asking us to update it — the current version doesn't yet have self-service password change, see "Future Improvements" below).

---

## 🎨 Customizing Site Content

Once logged into `/admin/`, go to **Site Settings** to update without touching any code:
- Site name & tagline
- Phone, email, WhatsApp number
- Office hours
- Social media links
- Homepage stats (years of experience, shipments delivered, etc.)

Go to **Branches** to add/edit/remove office locations shown on the website.

---

## 🔧 Local Testing (Optional — for developers)

If you want to test this on your own computer before uploading:

1. Install [XAMPP](https://www.apachefriends.org/) or [Laragon](https://laragon.org/) (includes PHP + MySQL + phpMyAdmin).
2. Place this project folder inside `htdocs` (XAMPP) or `www` (Laragon).
3. Start Apache and MySQL from the control panel.
4. Open phpMyAdmin (`http://localhost/phpmyadmin`), create a database called `asian_cargo`, and import `database/schema.sql` then `database/seed.sql`.
5. Update `config/database.php` if your local MySQL username/password differs (XAMPP default is usually `root` with no password).
6. Visit `http://localhost/asian-cargo/` in your browser.

---

## 📁 Project Structure

```
asian-cargo/
├── index.php              Homepage
├── services.php            Services page
├── track.php                Public tracking page
├── quote.php                 Quote request form
├── branches.php               Branches page
├── about.php                   About page
├── contact.php                  Contact page
├── config/
│   ├── database.php                ← Edit this with your hosting DB details
│   ├── helpers.php
│   └── bootstrap.php
├── includes/
│   ├── header.php
│   └── footer.php
├── admin/
│   ├── login.php
│   ├── logout.php
│   ├── index.php              Dashboard
│   ├── shipments.php          Shipment list
│   ├── shipment_form.php      Add/edit shipment
│   ├── shipment_view.php      View shipment + update status
│   ├── quotes.php             Quote requests inbox
│   ├── messages.php           Contact messages inbox
│   ├── branches.php           Manage branches
│   ├── settings.php           Site settings (super admin)
│   ├── admins.php             Manage admin users (super admin)
│   └── includes/
├── public/
│   ├── css/style.css           Public site styling
│   ├── css/admin.css           Admin panel styling
│   └── images/
└── database/
    ├── schema.sql               Database structure (import first)
    └── seed.sql                 Default admin + sample data (import second)
```

---

## 🔒 Security Notes

- All forms use CSRF protection tokens.
- All database queries use prepared statements (protects against SQL injection).
- Passwords are hashed with bcrypt — never stored in plain text.
- Admin login has brute-force throttling (locks out after repeated failed attempts for 10 minutes).
- The `.htaccess` file blocks direct browser access to `/config`, `/database`, and `/includes` folders.

**Before going live, make sure to:**
1. Change the default admin password (see Step 6 above).
2. Make sure `display_errors` stays `off` in `config/bootstrap.php` (it already is, by default).
3. Use a strong, unique database password (Step 2).
4. Enable HTTPS/SSL on your domain (most hosts offer free SSL — enable it in cPanel, then uncomment the HTTPS redirect lines in `.htaccess`).

---

## 🛠️ Future Improvements You Might Want

These weren't in the original request but are common next additions:
- Self-service "change my password" page in the admin panel
- Email notifications when a new quote request or message comes in
- SMS/WhatsApp auto-notification to customers when shipment status changes
- File/photo upload for proof-of-delivery
- Multi-language support (Urdu/English toggle)
- Customer accounts (so customers can see all their past shipments, not just one at a time)

Just ask if you'd like any of these added.

---

## ❓ Support

If something doesn't work after deployment, the most common issues are:
1. **Database connection error** → double check `config/database.php` values match exactly what cPanel shows you.
2. **500 error / blank page** → your host's PHP version may be too old; this project needs **PHP 8.0 or higher**. Check/change this in cPanel under "Select PHP Version" or "MultiPHP Manager".
3. **Images/CSS not loading** → make sure you uploaded the full `public/` folder including subfolders.
