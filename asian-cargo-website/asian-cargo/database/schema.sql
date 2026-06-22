-- Asian Cargo Database Schema
-- Import this file via phpMyAdmin or: mysql -u root -p asian_cargo < schema.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- Admin users (people who can log into /admin)
-- ============================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'staff') NOT NULL DEFAULT 'staff',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Shipments (the core tracking object)
-- ============================================
CREATE TABLE IF NOT EXISTS shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_number VARCHAR(30) NOT NULL UNIQUE,

    -- Sender
    sender_name VARCHAR(150) NOT NULL,
    sender_phone VARCHAR(30) NOT NULL,
    sender_address VARCHAR(255) NULL,
    origin_city VARCHAR(100) NOT NULL,
    origin_country VARCHAR(100) NOT NULL DEFAULT 'Pakistan',

    -- Receiver
    receiver_name VARCHAR(150) NOT NULL,
    receiver_phone VARCHAR(30) NOT NULL,
    receiver_address VARCHAR(255) NULL,
    destination_city VARCHAR(100) NOT NULL,
    destination_country VARCHAR(100) NOT NULL,

    -- Shipment details
    service_type ENUM('air_freight', 'ocean_freight', 'express_courier', 'road_freight') NOT NULL DEFAULT 'air_freight',
    package_description VARCHAR(255) NULL,
    weight_kg DECIMAL(10,2) NULL,
    pieces INT NOT NULL DEFAULT 1,

    -- Status
    status ENUM(
        'booked',
        'picked_up',
        'in_transit',
        'arrived_hub',
        'customs_clearance',
        'out_for_delivery',
        'delivered',
        'delayed',
        'exception'
    ) NOT NULL DEFAULT 'booked',

    estimated_delivery DATE NULL,
    created_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_tracking (tracking_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tracking history (each status change/checkpoint)
-- ============================================
CREATE TABLE IF NOT EXISTS tracking_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    status ENUM(
        'booked',
        'picked_up',
        'in_transit',
        'arrived_hub',
        'customs_clearance',
        'out_for_delivery',
        'delivered',
        'delayed',
        'exception'
    ) NOT NULL,
    location VARCHAR(150) NULL,
    remarks VARCHAR(255) NULL,
    event_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,

    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_shipment (shipment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Quote / Booking requests from the website form
-- ============================================
CREATE TABLE IF NOT EXISTS quote_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    service_type ENUM('air_freight', 'ocean_freight', 'express_courier', 'road_freight') NOT NULL,
    origin_city VARCHAR(100) NOT NULL,
    destination_city VARCHAR(100) NOT NULL,
    weight_kg DECIMAL(10,2) NULL,
    package_description TEXT NULL,
    status ENUM('new', 'contacted', 'closed') NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Branches / offices
-- ============================================
CREATE TABLE IF NOT EXISTS branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_name VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(150) NULL,
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    is_head_office TINYINT(1) NOT NULL DEFAULT 0,
    display_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Contact form messages
-- ============================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    subject VARCHAR(200) NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Site settings (editable from admin panel)
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
