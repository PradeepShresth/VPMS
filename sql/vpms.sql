-- VPMS database
-- Import in phpMyAdmin, or from the command line:
--   mysql -u root vpms < sql/vpms.sql

CREATE DATABASE IF NOT EXISTS vpms
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vpms;


-- The six roles. Fixed list, seeded once.
CREATE TABLE IF NOT EXISTS role (
  role_id     INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(50) NOT NULL,
  description VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO role (role_id, name, description) VALUES
  (1, 'Volunteer',               'Browse and apply for community opportunities'),
  (2, 'NGO Coordinator',         'Manage projects and recruit volunteers'),
  (3, 'Corporate CSR Manager',   'Lead employee volunteering programs'),
  (4, 'Community Field Officer', 'Validate attendance and verify hours'),
  (5, 'Sponsor / Donor',         'Track funded projects and outcomes'),
  (6, 'System Administrator',    'Approve accounts and manage the platform')
ON DUPLICATE KEY UPDATE name = VALUES(name);


-- Registered accounts.
CREATE TABLE IF NOT EXISTS `user` (
  user_id           INT AUTO_INCREMENT PRIMARY KEY,
  full_name         VARCHAR(120) NOT NULL,
  email             VARCHAR(190) NOT NULL UNIQUE,
  password_hash     CHAR(60) NOT NULL,
  phone             VARCHAR(30) DEFAULT NULL,
  role_id           INT NOT NULL,
  organisation_name VARCHAR(150) DEFAULT NULL,
  status            ENUM('pending', 'active', 'suspended') NOT NULL DEFAULT 'pending',
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES role(role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note: organisation_name is free text for now because the registration form
-- asks people to type it. It becomes organisation_id (a foreign key) once the
-- organisation table exists.
