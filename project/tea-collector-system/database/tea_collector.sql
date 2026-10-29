-- =====================================================
-- TEA COLLECTOR DATA MANAGEMENT SYSTEM (TCDMS)
-- Database: tea_collector_db
-- For XAMPP MySQL 8.0
-- =====================================================

DROP DATABASE IF EXISTS tea_collector_db;
CREATE DATABASE tea_collector_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tea_collector_db;

-- =====================================================
-- TABLE: users
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin','manager','collector','accountant','transport_officer','storekeeper') NOT NULL,
    collection_center_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: cooperatives
-- =====================================================
CREATE TABLE cooperatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: collection_centers
-- =====================================================
CREATE TABLE collection_centers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    location VARCHAR(255),
    manager_id INT DEFAULT NULL,
    capacity_kg DECIMAL(10,2) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: farmers
-- =====================================================
CREATE TABLE farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_code VARCHAR(20) UNIQUE NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address VARCHAR(255),
    cooperative_id INT DEFAULT NULL,
    collection_center_id INT DEFAULT NULL,
    bank_account VARCHAR(50),
    mobile_money_number VARCHAR(20),
    is_active TINYINT(1) DEFAULT 1,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cooperative_id) REFERENCES cooperatives(id) ON DELETE SET NULL,
    FOREIGN KEY (collection_center_id) REFERENCES collection_centers(id) ON DELETE SET NULL,
    INDEX idx_farmer_code (farmer_code),
    INDEX idx_national_id (national_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: price_configurations
-- =====================================================
CREATE TABLE price_configurations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quality_grade ENUM('A','B','C','D') NOT NULL,
    price_per_kg DECIMAL(10,2) NOT NULL,
    effective_from DATE NOT NULL,
    effective_to DATE DEFAULT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: tea_collections
-- =====================================================
CREATE TABLE tea_collections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_code VARCHAR(30) UNIQUE NOT NULL,
    farmer_id INT NOT NULL,
    collector_id INT NOT NULL,
    collection_center_id INT NOT NULL,
    gross_weight_kg DECIMAL(10,2) NOT NULL,
    net_weight_kg DECIMAL(10,2) NOT NULL,
    quality_grade ENUM('A','B','C','D') NOT NULL,
    moisture_content DECIMAL(5,2) DEFAULT NULL,
    collection_date DATE NOT NULL,
    collection_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending','verified','processed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE CASCADE,
    FOREIGN KEY (collector_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (collection_center_id) REFERENCES collection_centers(id) ON DELETE CASCADE,
    INDEX idx_date (collection_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: payments
-- =====================================================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_code VARCHAR(30) UNIQUE NOT NULL,
    farmer_id INT NOT NULL,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    total_weight_kg DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    net_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method ENUM('bank_transfer','mobile_money','cash') NOT NULL,
    payment_status ENUM('pending','processing','paid','failed') DEFAULT 'pending',
    paid_at DATETIME DEFAULT NULL,
    processed_by INT DEFAULT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE CASCADE,
    INDEX idx_status (payment_status)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: vehicles
-- =====================================================
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    vehicle_type VARCHAR(50),
    capacity_kg DECIMAL(10,2),
    is_available TINYINT(1) DEFAULT 1,
    last_maintenance DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: transport_schedules
-- =====================================================
CREATE TABLE transport_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_code VARCHAR(30) UNIQUE NOT NULL,
    collection_center_id INT NOT NULL,
    vehicle_id INT DEFAULT NULL,
    driver_id INT DEFAULT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NOT NULL,
    total_weight_kg DECIMAL(10,2) DEFAULT 0,
    status ENUM('scheduled','in_transit','delivered','cancelled') DEFAULT 'scheduled',
    departure_time DATETIME DEFAULT NULL,
    arrival_time DATETIME DEFAULT NULL,
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (collection_center_id) REFERENCES collection_centers(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    INDEX idx_date (scheduled_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: inventory
-- =====================================================
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    collection_center_id INT NOT NULL UNIQUE,
    current_stock_kg DECIMAL(10,2) DEFAULT 0,
    threshold_kg DECIMAL(10,2) DEFAULT 1000,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (collection_center_id) REFERENCES collection_centers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: audit_logs
-- =====================================================
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT DEFAULT NULL,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- =====================================================
-- SEED DATA
-- =====================================================

-- Admin user (password: admin123)
INSERT INTO users (username, email, password_hash, first_name, last_name, phone, role) VALUES
('admin', 'admin@sorwathe.rw', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HcRAtdSl3ZKmUWzGt4lBj5nGx5M2lS', 'System', 'Administrator', '+250788000000', 'admin');

-- Collection Centers
INSERT INTO collection_centers (name, code, location, capacity_kg) VALUES
('Kinihira Center A', 'CC-A-001', 'Kinihira, Rulindo', 5000),
('Kinihira Center B', 'CC-B-002', 'Kinihira, Rulindo', 3000),
('Rulindo Center C',  'CC-C-003', 'Rulindo District', 4000);

-- Cooperatives
INSERT INTO cooperatives (name, code, contact_person, phone) VALUES
('ASSOPTHE Cooperative', 'COOP-001', 'Jean Mugisha', '+250788111111'),
('Hillside Organic Tea Cooperative', 'COOP-002', 'Marie Uwase', '+250788222222');

-- Price Configuration
INSERT INTO price_configurations (quality_grade, price_per_kg, effective_from) VALUES
('A', 350.00, '2026-01-01'),
('B', 300.00, '2026-01-01'),
('C', 250.00, '2026-01-01'),
('D', 200.00, '2026-01-01');

-- Vehicles
INSERT INTO vehicles (plate_number, vehicle_type, capacity_kg) VALUES
('RAB 123 A', 'Truck', 5000),
('RAC 456 B', 'Truck', 3000),
('RAD 789 C', 'Pickup', 1500);

-- Initialize Inventory
INSERT INTO inventory (collection_center_id, current_stock_kg, threshold_kg) VALUES
(1, 0, 1000),
(2, 0, 1000),
(3, 0, 1000);