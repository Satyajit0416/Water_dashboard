-- ============================================================
-- Water Usage Optimization Dashboard - Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS water_dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE water_dashboard;

-- ============================================================
-- TABLE: users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'farmer') NOT NULL DEFAULT 'farmer',
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: farmers
-- ============================================================
CREATE TABLE IF NOT EXISTS farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    farm_name VARCHAR(150) NOT NULL,
    location VARCHAR(200) NOT NULL,
    farm_size DECIMAL(10,2) NOT NULL COMMENT 'in acres',
    soil_type ENUM('clay', 'sandy', 'loamy', 'silty', 'peaty', 'chalky') DEFAULT 'loamy',
    water_source ENUM('borewell', 'canal', 'rainwater', 'river', 'tank') DEFAULT 'borewell',
    phone VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: crops
-- ============================================================
CREATE TABLE IF NOT EXISTS crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    crop_name VARCHAR(100) NOT NULL,
    crop_type ENUM('cereal', 'vegetable', 'fruit', 'pulse', 'oilseed', 'cash_crop') NOT NULL,
    area_planted DECIMAL(10,2) NOT NULL COMMENT 'in acres',
    planting_date DATE NOT NULL,
    expected_harvest DATE NOT NULL,
    water_requirement DECIMAL(10,2) NOT NULL COMMENT 'liters per day per acre',
    growth_stage ENUM('seedling', 'vegetative', 'flowering', 'fruiting', 'harvest') DEFAULT 'seedling',
    status ENUM('active', 'harvested', 'failed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: water_usage
-- ============================================================
CREATE TABLE IF NOT EXISTS water_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    crop_id INT DEFAULT NULL,
    usage_date DATE NOT NULL,
    amount_used DECIMAL(10,2) NOT NULL COMMENT 'in liters',
    irrigation_method ENUM('drip', 'sprinkler', 'flood', 'furrow', 'subsurface') NOT NULL,
    duration_minutes INT NOT NULL,
    pump_power DECIMAL(5,2) DEFAULT NULL COMMENT 'in HP',
    area_irrigated DECIMAL(10,2) DEFAULT NULL COMMENT 'in acres',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE CASCADE,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: irrigation_schedule
-- ============================================================
CREATE TABLE IF NOT EXISTS irrigation_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    crop_id INT DEFAULT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NOT NULL,
    duration_minutes INT NOT NULL,
    irrigation_method ENUM('drip', 'sprinkler', 'flood', 'furrow', 'subsurface') NOT NULL,
    estimated_water DECIMAL(10,2) NOT NULL COMMENT 'in liters',
    status ENUM('pending', 'completed', 'skipped', 'rescheduled') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE CASCADE,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: reports
-- ============================================================
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT DEFAULT NULL,
    report_type ENUM('daily', 'weekly', 'monthly', 'annual', 'custom') NOT NULL,
    report_title VARCHAR(200) NOT NULL,
    report_data JSON DEFAULT NULL,
    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    total_usage DECIMAL(12,2) DEFAULT 0,
    generated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE SET NULL,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Admin user (password: password)
INSERT INTO users (name, email, password, role) VALUES
('Super Admin', 'admin@waterdash.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Farmer users (password: password)
INSERT INTO users (name, email, password, role) VALUES
('Rajesh Kumar', 'rajesh@farm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer'),
('Priya Singh', 'priya@farm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer'),
('Mohan Das', 'mohan@farm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer'),
('Sunita Patel', 'sunita@farm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer'),
('Vikram Reddy', 'vikram@farm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer');

-- Farmers
INSERT INTO farmers (user_id, farm_name, location, farm_size, soil_type, water_source, phone) VALUES
(2, 'Green Acres Farm', 'Punjab, India', 45.50, 'loamy', 'canal', '9876543210'),
(3, 'Sunrise Agriculture', 'Haryana, India', 30.00, 'clay', 'borewell', '9876543211'),
(4, 'Mohan Fields', 'Gujarat, India', 60.75, 'sandy', 'river', '9876543212'),
(5, 'Patel Farms', 'Maharashtra, India', 25.00, 'silty', 'rainwater', '9876543213'),
(6, 'Reddy Plantations', 'Andhra Pradesh, India', 80.00, 'loamy', 'borewell', '9876543214');

-- Crops
INSERT INTO crops (farmer_id, crop_name, crop_type, area_planted, planting_date, expected_harvest, water_requirement, growth_stage, status) VALUES
(1, 'Wheat', 'cereal', 20.00, '2024-11-01', '2025-03-15', 450.00, 'vegetative', 'active'),
(1, 'Rice', 'cereal', 15.00, '2024-06-15', '2024-10-30', 1200.00, 'harvest', 'harvested'),
(1, 'Sugarcane', 'cash_crop', 10.50, '2024-01-10', '2025-01-10', 900.00, 'vegetative', 'active'),
(2, 'Tomato', 'vegetable', 12.00, '2024-10-01', '2025-01-15', 350.00, 'fruiting', 'active'),
(2, 'Onion', 'vegetable', 8.00, '2024-09-15', '2025-01-01', 250.00, 'vegetative', 'active'),
(3, 'Cotton', 'cash_crop', 35.00, '2024-06-01', '2024-12-30', 600.00, 'fruiting', 'active'),
(3, 'Groundnut', 'oilseed', 25.75, '2024-07-01', '2024-11-30', 400.00, 'harvest', 'harvested'),
(4, 'Mango', 'fruit', 15.00, '2023-03-01', '2025-05-30', 300.00, 'flowering', 'active'),
(4, 'Banana', 'fruit', 10.00, '2024-03-01', '2025-02-28', 800.00, 'vegetative', 'active'),
(5, 'Brinjal', 'vegetable', 5.00, '2024-08-15', '2024-12-15', 280.00, 'fruiting', 'active');

-- Water Usage (last 30 days)
INSERT INTO water_usage (farmer_id, crop_id, usage_date, amount_used, irrigation_method, duration_minutes, pump_power, area_irrigated) VALUES
(1, 1, CURDATE() - INTERVAL 1 DAY, 4500.00, 'drip', 120, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 2 DAY, 4800.00, 'drip', 130, 5.00, 20.00),
(1, 3, CURDATE() - INTERVAL 3 DAY, 9450.00, 'flood', 180, 7.50, 10.50),
(1, 1, CURDATE() - INTERVAL 4 DAY, 4200.00, 'drip', 110, 5.00, 20.00),
(1, 3, CURDATE() - INTERVAL 5 DAY, 9800.00, 'flood', 190, 7.50, 10.50),
(1, 1, CURDATE() - INTERVAL 6 DAY, 4600.00, 'drip', 125, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 7 DAY, 4700.00, 'drip', 128, 5.00, 20.00),
(2, 4, CURDATE() - INTERVAL 1 DAY, 4200.00, 'sprinkler', 90, 3.00, 12.00),
(2, 4, CURDATE() - INTERVAL 2 DAY, 4000.00, 'sprinkler', 85, 3.00, 12.00),
(2, 5, CURDATE() - INTERVAL 3 DAY, 2000.00, 'drip', 60, 2.00, 8.00),
(2, 4, CURDATE() - INTERVAL 4 DAY, 4100.00, 'sprinkler', 88, 3.00, 12.00),
(2, 5, CURDATE() - INTERVAL 5 DAY, 2100.00, 'drip', 62, 2.00, 8.00),
(3, 6, CURDATE() - INTERVAL 1 DAY, 21000.00, 'flood', 240, 10.00, 35.00),
(3, 6, CURDATE() - INTERVAL 3 DAY, 22000.00, 'flood', 255, 10.00, 35.00),
(3, 6, CURDATE() - INTERVAL 5 DAY, 20500.00, 'flood', 230, 10.00, 35.00),
(4, 8, CURDATE() - INTERVAL 1 DAY, 4500.00, 'drip', 100, 3.00, 15.00),
(4, 9, CURDATE() - INTERVAL 2 DAY, 8000.00, 'drip', 150, 5.00, 10.00),
(4, 8, CURDATE() - INTERVAL 3 DAY, 4200.00, 'drip', 95, 3.00, 15.00),
(5, 10, CURDATE() - INTERVAL 1 DAY, 1400.00, 'drip', 50, 2.00, 5.00),
(5, 10, CURDATE() - INTERVAL 2 DAY, 1500.00, 'drip', 55, 2.00, 5.00),
-- Previous month data
(1, 1, CURDATE() - INTERVAL 10 DAY, 4300.00, 'drip', 115, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 12 DAY, 4400.00, 'drip', 118, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 14 DAY, 4100.00, 'drip', 110, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 16 DAY, 4900.00, 'drip', 135, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 18 DAY, 4600.00, 'drip', 125, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 20 DAY, 4200.00, 'drip', 112, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 22 DAY, 4800.00, 'drip', 130, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 24 DAY, 4500.00, 'drip', 122, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 26 DAY, 4700.00, 'drip', 127, 5.00, 20.00),
(1, 1, CURDATE() - INTERVAL 28 DAY, 4350.00, 'drip', 116, 5.00, 20.00);

-- Irrigation Schedule
INSERT INTO irrigation_schedule (farmer_id, crop_id, scheduled_date, scheduled_time, duration_minutes, irrigation_method, estimated_water, status) VALUES
(1, 1, CURDATE(), '06:00:00', 120, 'drip', 4500.00, 'pending'),
(1, 3, CURDATE() + INTERVAL 1 DAY, '07:00:00', 180, 'flood', 9450.00, 'pending'),
(2, 4, CURDATE(), '05:30:00', 90, 'sprinkler', 4200.00, 'pending'),
(2, 5, CURDATE() + INTERVAL 2 DAY, '06:00:00', 60, 'drip', 2000.00, 'pending'),
(3, 6, CURDATE() + INTERVAL 1 DAY, '05:00:00', 240, 'flood', 21000.00, 'pending'),
(4, 8, CURDATE(), '06:30:00', 100, 'drip', 4500.00, 'pending'),
(4, 9, CURDATE() + INTERVAL 1 DAY, '07:00:00', 150, 'drip', 8000.00, 'pending'),
(5, 10, CURDATE(), '06:00:00', 50, 'drip', 1400.00, 'pending'),
(1, 1, CURDATE() - INTERVAL 1 DAY, '06:00:00', 120, 'drip', 4500.00, 'completed'),
(2, 4, CURDATE() - INTERVAL 1 DAY, '05:30:00', 90, 'sprinkler', 4200.00, 'completed');

-- ============================================================
-- NOTE: Default password for all users is: password
-- Use password_hash() in PHP to generate new passwords
-- Admin login: admin@waterdash.com / password
-- Farmer login: rajesh@farm.com / password
-- ============================================================
