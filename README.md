# Aloe Credit — website & client portal

Full front-end + back-end site for Aloe Credit: marketing pages, an SEO-oriented
blog, an interactive loan calculator, and a PHP/MySQL backend with a secure
client login (email 2FA) and an admin dashboard for reviewing applications.

Built as plain PHP + MySQL with no build step, to run on standard shared
hosting (developed with Xneelo in mind).

## Stack

- PHP 8.1+ (no framework, no Composer dependency required)
- MySQL 5.7+/8
- Vanilla HTML/CSS/JS front end — no Node build step

## Local development

```
php -S localhost:8000
```

Pretty URLs (`/about` instead of `/about.php`) and security headers are
applied by `.htaccess` on Apache; PHP's built-in server ignores `.htaccess`,
so during local dev use the `.php` filenames directly (e.g. `/about.php`).

## Deploying to Xneelo (or any shared cPanel host)

1. **Create the database.** In cPanel → MySQL Databases, create a database
   and user, then import the schema:
   ```
   mysql -u <user> -p <database> < backend/db/schema.sql
   ```
2. **Set environment config.** Copy `.env.example` to `.env` and fill in real
   values (DB credentials, `APP_URL`, mail settings). Place it **one
   directory above** `public_html` if your plan allows — `backend/env.php`
   checks there first — otherwise leave it in the project root; `.htaccess`
   blocks direct HTTP access to any dotfile either way.
3. **Upload the files** to `public_html` (or a subfolder, then point the
   domain there).
4. **Create an admin account.** Registration through the website only ever
   creates `client` role accounts — this is deliberate, so nobody can grant
   themselves admin through the public form. To create the first admin:
   1. Register a normal account through `/register`.
   2. In phpMyAdmin, run:
      ```sql
      UPDATE users SET role = 'admin' WHERE email = 'you@aloecredit.co.za';
      ```
   Admin pages under `/admin/` re-check the role from the database on every
   single request — never from a cookie or anything client-supplied.
5. **Mail.** Outgoing mail uses PHP's built-in `mail()`, which works out of
   the box on Xneelo. For better deliverability, fill in the `SMTP_*`
   variables in `.env` and swap the body of `aloe_send_mail()` in
   `backend/includes/mailer.php` for an SMTP client.

## Before this goes live

This scaffold is functionally complete but ships with clearly-marked
placeholders that must be replaced — grep for them:

- **NCR registration number** — `partials/footer.php`, `responsible-lending.php`.
  Aloe Credit must be registered with the National Credit Regulator before
  offering credit; do not remove these disclosures.
- **POPIA Information Officer contact** — `privacy-policy.php`. Must be
  registered with the Information Regulator.
- **Real photography** — hero and section images currently use Unsplash
  stock URLs; replace with licensed or brand photography.
- **`assets/img/og-cover.svg`** — social-share preview image is an SVG
  placeholder. Most platforms (Facebook, LinkedIn) require a raster image
  for `og:image` — replace it with a real 1200×630 PNG/JPG.
- **Contact details** — phone/email/address in `contact.php` and the footer.

## Security notes (read before changing auth code)

- **No token is ever stored in localStorage/sessionStorage.** Login is a
  server-rendered form; the only thing the browser holds is an HttpOnly,
  Secure, SameSite=Strict session cookie (`backend/includes/session.php`).
  This means a successful XSS still can't read or forge a session — there's
  nothing in JS-accessible storage to steal.
- **Admin checks are server-side only.** `require_admin()`
  (`backend/includes/auth.php`) re-queries the user's role from the database
  on every request. Nothing about admin access is inferred from anything the
  client sends.
- **2FA:** every login (and registration) requires a 6-digit, single-use,
  10-minute email OTP (`issue_login_otp()` / `verify_login_otp()`), on top of
  the password.
- **Rate limiting:** login, OTP verification/resend, password reset,
  registration, contact, and the application form are all rate-limited by
  IP and by IP+account (`backend/includes/rate_limit.php`,
  `rate_limit_policy()`), per common FinTech guidance of layering an
  account-level limit with a coarser IP-level limit.
- **Passwords:** minimum 12 characters with mixed case/number/symbol,
  checked against a common-password list and against the Have I Been Pwned
  breach corpus via k-anonymity (`backend/includes/validators.php`) — the
  real password never leaves the server, only a 5-character hash prefix.
- **CSRF:** every state-changing form carries a per-session token, verified
  server-side (`backend/includes/csrf.php`).
- All database queries use parameterised PDO statements; all output is
  escaped with `e()` (`htmlspecialchars`).

## Structure

```
/                    Marketing pages (index, about, services, apply, ...)
/blog/               SEO articles
/admin/              Admin dashboard (require_admin() on every file)
/partials/           head/header/footer includes, not web-accessible
/assets/             CSS, JS, images
/backend/            Config, DB, session/auth/CSRF/rate-limit/mail includes,
                     not web-accessible (see backend/.htaccess)
/backend/db/schema.sql   Full database schema
```
