-- Sun Son Solar — database schema (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS sunson_solar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sunson_solar;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(60) NOT NULL,
  middle_name VARCHAR(60) NULL,
  last_name VARCHAR(60) NOT NULL,
  birthdate DATE NOT NULL,
  gender ENUM('Female','Male','Other','Prefer not to say') NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(255) NOT NULL,
  username VARCHAR(40) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','technician','dispatcher','hr','admin') NOT NULL DEFAULT 'customer',
  department ENUM('Administration','IT','Dispatch','Accounting','HR','Marketing','Sales','Customer Service') NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  privacy_consent TINYINT(1) NOT NULL DEFAULT 0,
  consent_at DATETIME NULL,
  pref_email TINYINT(1) NOT NULL DEFAULT 1,
  pref_sms TINYINT(1) NOT NULL DEFAULT 0,
  pref_promos TINYINT(1) NOT NULL DEFAULT 1,
  created_by INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL,
  slug VARCHAR(60) NOT NULL UNIQUE
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  brand VARCHAR(60) NULL,
  short_desc VARCHAR(255) NOT NULL,
  long_desc TEXT NULL,
  specs TEXT NULL,
  price DECIMAL(12,2) NULL,            -- NULL = "Request quote"
  image VARCHAR(255) NULL,
  stock INT NOT NULL DEFAULT 0,
  reorder_level INT NOT NULL DEFAULT 5,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(60) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  base_price DECIMAL(12,2) NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  bookable TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0
);

CREATE TABLE availability (
  id INT AUTO_INCREMENT PRIMARY KEY,
  weekday TINYINT NOT NULL,            -- 1=Mon ... 7=Sun
  open_time TIME NOT NULL,
  close_time TIME NOT NULL,
  is_open TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  service_id INT NOT NULL,
  scheduled_at DATETIME NOT NULL,
  address VARCHAR(255) NOT NULL,
  notes VARCHAR(255) NULL,
  status ENUM('pending','approved','declined','completed','cancelled') NOT NULL DEFAULT 'pending',
  technician_id INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES users(id),
  FOREIGN KEY (service_id) REFERENCES services(id),
  FOREIGN KEY (technician_id) REFERENCES users(id)
);

CREATE TABLE invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice_no VARCHAR(20) NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  booking_id INT NULL,
  description VARCHAR(255) NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  status ENUM('unpaid','pending','paid','overdue','refunded') NOT NULL DEFAULT 'unpaid',
  method VARCHAR(30) NULL,
  due_date DATE NOT NULL,
  paid_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES users(id),
  FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment VARCHAR(500) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id),
  FOREIGN KEY (customer_id) REFERENCES users(id)
);

CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,                    -- NULL = broadcast
  audience ENUM('user','customers','employees','all') NOT NULL DEFAULT 'user',
  type ENUM('reminder','schedule','promotion','system') NOT NULL DEFAULT 'system',
  title VARCHAR(120) NOT NULL,
  body VARCHAR(500) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE announcements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT NOT NULL,
  title VARCHAR(120) NOT NULL,
  body VARCHAR(500) NOT NULL,
  is_urgent TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  full_name VARCHAR(140) NOT NULL,
  checked_in_at DATETIME NOT NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE problem_reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reporter_id INT NOT NULL,
  issue_type ENUM('equipment','customer concern','other') NOT NULL,
  description TEXT NOT NULL,
  photo VARCHAR(255) NULL,
  status ENUM('open','in review','resolved') NOT NULL DEFAULT 'open',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (reporter_id) REFERENCES users(id)
);

CREATE TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  subject VARCHAR(140) NOT NULL,
  status ENUM('open','answered','closed') NOT NULL DEFAULT 'open',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES users(id)
);

CREATE TABLE ticket_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ticket_id INT NOT NULL,
  sender_id INT NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES users(id)
);

CREATE TABLE system_readings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  reading_date DATE NOT NULL,
  kwh_generated DECIMAL(8,2) NOT NULL,
  kwh_consumed DECIMAL(8,2) NOT NULL,
  battery_pct TINYINT NULL,
  FOREIGN KEY (customer_id) REFERENCES users(id)
);

CREATE TABLE faqs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  slug VARCHAR(160) NOT NULL UNIQUE,
  excerpt VARCHAR(300) NOT NULL,
  body TEXT NOT NULL,
  category ENUM('blog','news') NOT NULL DEFAULT 'blog',
  published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(80) NOT NULL,
  location VARCHAR(80) NULL,
  quote VARCHAR(500) NOT NULL,
  rating TINYINT NOT NULL DEFAULT 5
);

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NULL,
  topic ENUM('Free quote','Schedule a service','General inquiry') NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE audit_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(60) NOT NULL,
  detail VARCHAR(255) NULL,
  ip VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE settings (
  `key` VARCHAR(60) PRIMARY KEY,
  `value` VARCHAR(255) NOT NULL
);

-- Reference data (users and sample content are seeded by setup.php)
INSERT INTO categories (name, slug) VALUES
 ('Solar Panels','panels'),('Inverters','inverters'),('Batteries','batteries'),
 ('Racking & Mounting','racking'),('Wires & Cables','wires');

INSERT INTO services (name, slug, description, base_price, is_featured, bookable, sort_order) VALUES
 ('Consultation','consultation','A free site assessment: we review your roof, bills and goals, then recommend a right-sized system.',0,1,1,1),
 ('System Design','designing','Engineering layouts, single-line diagrams and production estimates tailored to your property.',NULL,0,0,2),
 ('Permitting','permitting','We prepare and file permit, net-metering and utility paperwork on your behalf.',NULL,0,0,3),
 ('Installation','installation','Certified technicians install panels, inverters, batteries and mounting with a full safety check.',NULL,1,1,4),
 ('Maintenance','maintenance','Scheduled cleaning, inspection and performance tuning to keep output at its peak.',2500,1,1,5),
 ('Repair','repair','Fast diagnosis and replacement of faulty panels, inverters, wiring and batteries.',NULL,0,0,6),
 ('Monitoring','monitoring','Live performance tracking with alerts so you always know how your system is doing.',NULL,0,0,7);

INSERT INTO availability (weekday, open_time, close_time, is_open) VALUES
 (1,'08:00','17:00',1),(2,'08:00','17:00',1),(3,'08:00','17:00',1),(4,'08:00','17:00',1),
 (5,'08:00','17:00',1),(6,'08:00','12:00',1),(7,'08:00','17:00',0);

INSERT INTO settings (`key`,`value`) VALUES
 ('company_phone','+63 2 8000 0000'),('company_email','hello@sunsonsolar.example'),
 ('company_address','Pasig City, Metro Manila, Philippines');
