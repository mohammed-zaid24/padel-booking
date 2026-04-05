CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO users (name, email, password_hash, role) VALUES
('Admin Demo', 'admin@padel.local', '$2y$10$R13DT3Wzn7lIUQbnq44meOawSQCOS4z4uW7zzP3jAKtIrY6SAllGm', 'admin'),
('User Demo', 'user@padel.local', '$2y$10$dn3BpfE.nwirGWkHmamSAe5hEzkL.S00AXk8A94LhC5iTF9gt7MFG', 'user');

CREATE TABLE IF NOT EXISTS courts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS timeslots (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  court_id INT UNSIGNED NOT NULL,
  slot_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_timeslots_court_date (court_id, slot_date),
  UNIQUE KEY uq_timeslot_per_day (court_id, slot_date, start_time, end_time),
  CONSTRAINT fk_timeslots_court
    FOREIGN KEY (court_id) REFERENCES courts(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bookings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  court_id INT UNSIGNED NOT NULL,
  date DATE NOT NULL,
  timeslot_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY idx_bookings_user_id (user_id),
  KEY idx_bookings_court_date (court_id, date),
  KEY idx_bookings_timeslot_id (timeslot_id),
  UNIQUE KEY uq_booking_slot (court_id, date, timeslot_id),
  CONSTRAINT fk_bookings_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_bookings_court
    FOREIGN KEY (court_id) REFERENCES courts(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_bookings_timeslot
    FOREIGN KEY (timeslot_id) REFERENCES timeslots(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO courts (name, location)
SELECT v.name, v.location
FROM (
  SELECT 'Court 1' AS name, 'Main Hall' AS location
  UNION ALL
  SELECT 'Court 2', 'Main Hall'
) AS v
WHERE NOT EXISTS (
  SELECT 1
  FROM courts c
  WHERE c.name = v.name AND c.location = v.location
);

INSERT INTO timeslots (court_id, slot_date, start_time, end_time)
SELECT v.court_id, v.slot_date, v.start_time, v.end_time
FROM (
  SELECT 1 AS court_id, CURDATE() AS slot_date, '09:00:00' AS start_time, '10:00:00' AS end_time
  UNION ALL SELECT 1, CURDATE(), '10:00:00', '11:00:00'
  UNION ALL SELECT 1, CURDATE(), '11:00:00', '12:00:00'
  UNION ALL SELECT 2, CURDATE(), '09:00:00', '10:00:00'
  UNION ALL SELECT 2, CURDATE(), '10:00:00', '11:00:00'
  UNION ALL SELECT 2, CURDATE(), '11:00:00', '12:00:00'
) AS v
WHERE NOT EXISTS (
  SELECT 1
  FROM timeslots t
  WHERE t.court_id = v.court_id
    AND t.slot_date = v.slot_date
    AND t.start_time = v.start_time
    AND t.end_time = v.end_time
);

-- Safe upgrade block for existing databases that still have the old timeslots schema.
-- On a fresh database this block will detect the column/indexes and skip changes.

SET @slot_date_exists := (
  SELECT COUNT(*)
  FROM information_schema.columns
  WHERE table_schema = DATABASE()
    AND table_name = 'timeslots'
    AND column_name = 'slot_date'
);

SET @sql_stmt := IF(
  @slot_date_exists = 0,
  'ALTER TABLE timeslots ADD COLUMN slot_date DATE NULL AFTER court_id',
  'SELECT 1'
);
PREPARE stmt FROM @sql_stmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE timeslots
SET slot_date = CURDATE()
WHERE slot_date IS NULL;

ALTER TABLE timeslots
  MODIFY slot_date DATE NOT NULL;

SET @court_date_index_exists := (
  SELECT COUNT(*)
  FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'timeslots'
    AND index_name = 'idx_timeslots_court_date'
);

SET @sql_stmt := IF(
  @court_date_index_exists = 0,
  'CREATE INDEX idx_timeslots_court_date ON timeslots (court_id, slot_date)',
  'SELECT 1'
);
PREPARE stmt FROM @sql_stmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @timeslot_unique_exists := (
  SELECT COUNT(*)
  FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'timeslots'
    AND index_name = 'uq_timeslot_per_day'
);

SET @sql_stmt := IF(
  @timeslot_unique_exists = 0,
  'ALTER TABLE timeslots ADD UNIQUE KEY uq_timeslot_per_day (court_id, slot_date, start_time, end_time)',
  'SELECT 1'
);
PREPARE stmt FROM @sql_stmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
