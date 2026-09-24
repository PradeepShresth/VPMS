-- VPMS database
-- Open the database in phpMyAdmin first, then Import this file.
-- It drops the tables and builds them again, so any data you entered is lost.

DROP TABLE IF EXISTS discussion_reply;
DROP TABLE IF EXISTS discussion;
DROP TABLE IF EXISTS announcement;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS thread;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS event_volunteer;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS application;
DROP TABLE IF EXISTS opportunity;
DROP TABLE IF EXISTS partnership;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS organisation;
DROP TABLE IF EXISTS role;


CREATE TABLE role (
  role_id     INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(50) NOT NULL,
  description VARCHAR(255) NOT NULL
);

INSERT INTO role (role_id, name, description) VALUES
  (1, 'Volunteer',               'Browse and apply for community opportunities'),
  (2, 'NGO Coordinator',         'Manage projects and recruit volunteers'),
  (3, 'Corporate CSR Manager',   'Lead employee volunteering programs'),
  (4, 'Community Field Officer', 'Validate attendance and verify hours'),
  (5, 'Sponsor / Donor',         'Track funded projects and outcomes'),
  (6, 'System Administrator',    'Approve accounts and manage the platform');


CREATE TABLE organisation (
  organisation_id INT AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(150) NOT NULL,
  type            VARCHAR(60) NOT NULL,
  registration_no VARCHAR(60),
  country         VARCHAR(80),
  state           VARCHAR(80),
  city            VARCHAR(80),
  address         VARCHAR(255),
  website         VARCHAR(255),
  description     TEXT,
  status          VARCHAR(20) NOT NULL DEFAULT 'pending',   -- pending, verified or rejected
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- organisation_id is only set when the account came from an organisation
-- registration. Everyone else just types a name at sign-up.
CREATE TABLE `user` (
  user_id           INT AUTO_INCREMENT PRIMARY KEY,
  full_name         VARCHAR(120) NOT NULL,
  email             VARCHAR(190) NOT NULL UNIQUE,
  password_hash     CHAR(60) NOT NULL,
  phone             VARCHAR(30),
  role_id           INT NOT NULL,
  organisation_id   INT,
  organisation_name VARCHAR(150),
  designation       VARCHAR(80),
  skills            VARCHAR(255),
  bio               TEXT,
  status            VARCHAR(20) NOT NULL DEFAULT 'pending',   -- pending, active or suspended
  reset_code        VARCHAR(40),
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES role(role_id),
  FOREIGN KEY (organisation_id) REFERENCES organisation(organisation_id)
);


CREATE TABLE partnership (
  partnership_id  INT AUTO_INCREMENT PRIMARY KEY,
  organisation_id INT,
  partner_id      INT,
  type            VARCHAR(60),
  start_date      DATE,
  end_date        DATE,
  sdg_goals       VARCHAR(60),
  objectives      TEXT,
  expected_impact TEXT,
  reported_impact VARCHAR(255),
  status          VARCHAR(20) NOT NULL DEFAULT 'pending',   -- pending, active, expired or rejected
  requested_by    INT NOT NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (organisation_id) REFERENCES organisation(organisation_id),
  FOREIGN KEY (partner_id) REFERENCES organisation(organisation_id),
  FOREIGN KEY (requested_by) REFERENCES `user`(user_id)
);


CREATE TABLE opportunity (
  opportunity_id   INT AUTO_INCREMENT PRIMARY KEY,
  title            VARCHAR(150) NOT NULL,
  organisation_id  INT,
  partnership_id   INT,                                      -- set when it runs under an agreement
  created_by       INT NOT NULL,
  location         VARCHAR(150),
  opportunity_date DATE,
  hours_required   INT NOT NULL DEFAULT 0,
  spots            INT NOT NULL DEFAULT 0,
  category         VARCHAR(60),
  skills           VARCHAR(255),
  sdg_goals        VARCHAR(60),                              -- like 13,15,17
  description      TEXT,
  status           VARCHAR(20) NOT NULL DEFAULT 'open',      -- open or closed
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (organisation_id) REFERENCES organisation(organisation_id),
  FOREIGN KEY (partnership_id) REFERENCES partnership(partnership_id),
  FOREIGN KEY (created_by) REFERENCES `user`(user_id)
);


CREATE TABLE application (
  application_id    INT AUTO_INCREMENT PRIMARY KEY,
  opportunity_id    INT NOT NULL,
  user_id           INT NOT NULL,
  why               TEXT,
  skills            VARCHAR(255),
  availability      VARCHAR(80),
  emergency_contact VARCHAR(120),
  status            VARCHAR(20) NOT NULL DEFAULT 'pending',  -- pending, accepted or rejected
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (opportunity_id) REFERENCES opportunity(opportunity_id),
  FOREIGN KEY (user_id) REFERENCES `user`(user_id)
);


CREATE TABLE event (
  event_id          INT AUTO_INCREMENT PRIMARY KEY,
  title             VARCHAR(150) NOT NULL,
  opportunity_id    INT,
  organisation_id   INT,
  created_by        INT NOT NULL,
  location          VARCHAR(150),
  event_date        DATE,
  event_time        TIME,
  volunteers_needed INT NOT NULL DEFAULT 0,
  category          VARCHAR(60),
  status            VARCHAR(20) NOT NULL DEFAULT 'upcoming',
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (opportunity_id) REFERENCES opportunity(opportunity_id),
  FOREIGN KEY (organisation_id) REFERENCES organisation(organisation_id),
  FOREIGN KEY (created_by) REFERENCES `user`(user_id)
);


-- the roster for an event, plus the attendance ticked off on the day
CREATE TABLE event_volunteer (
  event_volunteer_id INT AUTO_INCREMENT PRIMARY KEY,
  event_id           INT NOT NULL,
  user_id            INT NOT NULL,
  role_in_event      VARCHAR(60) NOT NULL DEFAULT 'Volunteer',
  status             VARCHAR(20) NOT NULL DEFAULT 'confirmed', -- confirmed or waitlist
  attended           INT NOT NULL DEFAULT 0,
  hours_logged       INT NOT NULL DEFAULT 0,
  created_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (event_id) REFERENCES event(event_id),
  FOREIGN KEY (user_id) REFERENCES `user`(user_id)
);


CREATE TABLE report (
  report_id    INT AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(150) NOT NULL,
  report_type  VARCHAR(60) NOT NULL,
  period       VARCHAR(60),
  generated_by INT NOT NULL,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (generated_by) REFERENCES `user`(user_id)
);


-- a thread is one conversation between two people, messages hang off it
CREATE TABLE thread (
  thread_id  INT AUTO_INCREMENT PRIMARY KEY,
  subject    VARCHAR(190) NOT NULL,
  user_one   INT NOT NULL,
  user_two   INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_one) REFERENCES `user`(user_id),
  FOREIGN KEY (user_two) REFERENCES `user`(user_id)
);

CREATE TABLE message (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  thread_id  INT NOT NULL,
  sender_id  INT NOT NULL,
  body       TEXT NOT NULL,
  is_read    INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (thread_id) REFERENCES thread(thread_id),
  FOREIGN KEY (sender_id) REFERENCES `user`(user_id)
);


CREATE TABLE announcement (
  announcement_id INT AUTO_INCREMENT PRIMARY KEY,
  title           VARCHAR(190) NOT NULL,
  body            TEXT NOT NULL,
  audience        VARCHAR(80) NOT NULL DEFAULT 'Everyone on the platform',
  pinned          INT NOT NULL DEFAULT 0,
  posted_by       INT NOT NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (posted_by) REFERENCES `user`(user_id)
);


CREATE TABLE discussion (
  discussion_id INT AUTO_INCREMENT PRIMARY KEY,
  topic         VARCHAR(80) NOT NULL,
  title         VARCHAR(190) NOT NULL,
  body          TEXT NOT NULL,
  started_by    INT NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (started_by) REFERENCES `user`(user_id)
);

CREATE TABLE discussion_reply (
  reply_id      INT AUTO_INCREMENT PRIMARY KEY,
  discussion_id INT NOT NULL,
  user_id       INT NOT NULL,
  body          TEXT NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (discussion_id) REFERENCES discussion(discussion_id),
  FOREIGN KEY (user_id) REFERENCES `user`(user_id)
);


-- admin@vpms.org, password admin123
INSERT INTO `user` (full_name, email, password_hash, role_id, status) VALUES
  ('Pradeep Shrestha', 'admin@vpms.org',
   '$2y$10$XV4xB5DEsRUw2batNdx2xuEh.mvKqnFYOs7p0e3phrTqJ6SBooIiW', 6, 'active');
