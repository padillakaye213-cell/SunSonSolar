# Sun Son Solar — PHP / CSS / SQL website

Requires PHP 8.1+ (PDO MySQL) and MySQL/MariaDB.

## Install
1. Import the database: `mysql -u root -p < schema.sql`
2. Edit `config.php` (DB credentials; set `BASE_URL` if the site is in a sub-folder, e.g. `/sunson`).
3. Serve the folder (Apache/XAMPP/Laragon, or `php -S localhost:8000` inside this folder).
4. Open `/setup.php` **once** to load demo products, posts, FAQs and starter accounts, then **delete `setup.php`**.
5. Make sure `uploads/` is writable by the web server.

## Starter accounts (created by setup.php — change immediately)
| Role | Username | Password |
|---|---|---|
| Admin | admin | Admin#2026 |
| Dispatcher | dispatcher | Dispatch#2026 |
| HR | hr | HumanR#2026 |
| Technician | technician | Tech#2026 |
| Customer | customer | Customer#2026 |

## Structure
- `index.php, products.php, product.php, services.php, about.php, faq.php, blog.php, contact.php, privacy.php, terms.php` — public site
- `login.php, register.php, forgot.php, reset.php, logout.php` — authentication
- `customer/` — dashboard (schedule, notifications, history, support, profile), payments, receipts, monitoring, feedback
- `technician/` — mobile-first check-in, jobs, announcements, problem report
- `admin/` — overview, users, schedule, invoices, reports, notifications, inventory, problems, attendance, settings, audit log
- `includes/` — helpers, layout, shared user form
- `assets/css/style.css` — palette: #2f4156 · #567C8D · #C8D9E6 · #F5EFEB · #FFFFFF
- `assets/js/app.js` — small helpers (show/hide password, strength meter, GPS check-in, live validation)

## Before going live
- Delete `setup.php`; set `DEV_SHOW_RESET_LINK` to `false` in `config.php`; use HTTPS.
- Replace placeholder phone/email/social links (Admin → Settings and `includes/layout.php`).
- Payments are recorded as "pending verification" — connect a real gateway (GCash/Maya/card) before accepting money.
- Have the Privacy Policy and Terms reviewed by counsel.
