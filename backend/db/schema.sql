-- Aloe Credit — database schema (MySQL 5.7+/8, utf8mb4)
-- Import via Xneelo cPanel > phpMyAdmin, or: mysql -u USER -p DBNAME < schema.sql

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name         VARCHAR(150)  NOT NULL,
  email             VARCHAR(190)  NOT NULL,
  phone             VARCHAR(30)   NULL,
  password_hash     VARCHAR(255)  NOT NULL,
  role              ENUM('client','admin') NOT NULL DEFAULT 'client',
  status            ENUM('active','locked') NOT NULL DEFAULT 'active',
  email_verified_at DATETIME NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One-time codes: email OTP for 2FA at login, and account email verification.
CREATE TABLE IF NOT EXISTS otp_codes (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     BIGINT UNSIGNED NOT NULL,
  code_hash   CHAR(64) NOT NULL,      -- sha256 of the 6-digit code, code itself is never stored
  purpose     ENUM('login_2fa','email_verify') NOT NULL,
  expires_at  DATETIME NOT NULL,
  consumed_at DATETIME NULL,
  attempts    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_otp_user (user_id, purpose),
  CONSTRAINT fk_otp_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password reset tokens (random token, only the hash is stored).
CREATE TABLE IF NOT EXISTS password_resets (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     BIGINT UNSIGNED NOT NULL,
  token_hash  CHAR(64) NOT NULL,
  expires_at  DATETIME NOT NULL,
  consumed_at DATETIME NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_reset_user (user_id),
  KEY idx_reset_token (token_hash),
  CONSTRAINT fk_reset_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Generic sliding-window rate limiter shared by login, OTP, password reset, register, contact.
CREATE TABLE IF NOT EXISTS rate_limit_events (
  id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  bucket     VARCHAR(40)  NOT NULL,
  identifier VARCHAR(190) NOT NULL,   -- ip, or ip|email
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_rate_lookup (bucket, identifier, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pre-qualification leads from the public "Apply" form.
-- Deliberately does NOT collect ID numbers or bank details — full KYC happens
-- later inside the authenticated, POPIA-compliant onboarding flow, not here.
CREATE TABLE IF NOT EXISTS applications (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id           BIGINT UNSIGNED NULL,
  full_name         VARCHAR(150) NOT NULL,
  email             VARCHAR(190) NOT NULL,
  phone             VARCHAR(30)  NOT NULL,
  employment_status VARCHAR(60)  NOT NULL,
  monthly_income    VARCHAR(40)  NOT NULL,
  loan_amount       DECIMAL(10,2) NOT NULL,
  loan_term_months  SMALLINT UNSIGNED NOT NULL,
  purpose           VARCHAR(120) NULL,
  status            ENUM('new','reviewing','approved','declined') NOT NULL DEFAULT 'new',
  notes             TEXT NULL,
  ip                VARCHAR(45) NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_app_status (status),
  CONSTRAINT fk_app_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_messages (
  id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(150) NOT NULL,
  email      VARCHAR(190) NOT NULL,
  phone      VARCHAR(30)  NULL,
  subject    VARCHAR(150) NULL,
  message    TEXT NOT NULL,
  handled    TINYINT(1) NOT NULL DEFAULT 0,
  ip         VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_log (
  id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    BIGINT UNSIGNED NULL,
  event      VARCHAR(60) NOT NULL,
  detail     VARCHAR(255) NULL,
  ip         VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_audit_user (user_id),
  KEY idx_audit_event (event)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed an admin account: change the email, then set a real password with
-- backend/scripts/make_password_hash.php (never hand-write a hash here).
-- INSERT INTO users (full_name, email, password_hash, role, status, email_verified_at)
-- VALUES ('Admin', 'admin@aloecredit.co.za', '<paste hash here>', 'admin', 'active', NOW());
