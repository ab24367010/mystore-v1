-- ========================================
-- MYSTORE DATABASE SCHEMA
-- Version: 2.0 (Updated: 2025-10-16)
-- Description: Complete e-commerce platform for selling website templates
-- ========================================

-- Drop existing database (Development only!)
-- DROP DATABASE IF EXISTS mystore;
-- CREATE DATABASE mystore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mystore;

-- ========================================
-- 1. USERS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Bcrypt hashed password',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 2. TEMPLATES TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    thumbnail VARCHAR(255) COMMENT 'Main preview image',
    demo_url VARCHAR(255) COMMENT 'Live demo URL',
    file_path VARCHAR(255) COMMENT 'Downloadable ZIP file path',
    category VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 3. TEMPLATE_SCREENSHOTS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS template_screenshots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    template_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE CASCADE,
    INDEX idx_template_order (template_id, display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 4. ORDERS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    template_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL COMMENT 'Format: ORD-YYYYMMDD-XXXXXX',
    status ENUM('pending', 'paid', 'delivered', 'cancelled') DEFAULT 'pending',
    download_token VARCHAR(255) COMMENT 'Secure download token (64 chars)',
    token_expires DATETIME COMMENT 'Download link expiration (default 30 days)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE RESTRICT,

    INDEX idx_user_id (user_id),
    INDEX idx_template_id (template_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 5. ADMINS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Bcrypt hashed password',
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- 6. PASSWORD_RESET_CODES TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS password_reset_codes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    code VARCHAR(6) NOT NULL COMMENT '6-digit verification code',
    expires_at DATETIME NOT NULL COMMENT 'Code expiration (default 15 minutes)',
    verified TINYINT(1) DEFAULT 0 COMMENT '0 = not verified, 1 = verified',
    attempts INT DEFAULT 0 COMMENT 'Failed verification attempts',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_user_code (user_id, code),
    INDEX idx_expires (expires_at),
    INDEX idx_verified (verified)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- INITIAL DATA
-- ========================================

-- Default admin account
-- Username: admin
-- Password: admin123 (CHANGE THIS IN PRODUCTION!)
INSERT INTO admins (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mystore.com')
ON DUPLICATE KEY UPDATE username = username;

-- Sample templates (Optional - for testing)
INSERT INTO templates (name, description, price, category, status) VALUES
('E-commerce Store Template', 'Professional online shop with shopping cart, checkout, and payment integration. Perfect for small to medium businesses.', 49.99, 'E-commerce', 'active'),
('Creative Portfolio', 'Stunning portfolio template for designers, photographers, and creative professionals. Includes gallery and contact form.', 29.99, 'Portfolio', 'active'),
('Landing Page Pro', 'High-converting landing page template with call-to-action sections, testimonials, and email signup integration.', 19.99, 'Landing Page', 'active'),
('Corporate Business', 'Clean and professional business template with services, team, and about sections. Ideal for corporate websites.', 39.99, 'Business', 'active'),
('Blog & Magazine', 'Modern blog template with post categories, search, and social media integration. Great for content creators.', 24.99, 'Blog', 'active'),
('Restaurant & Cafe', 'Beautiful restaurant template with menu display, reservation system, and location map. Perfect for food businesses.', 34.99, 'Restaurant', 'active')
ON DUPLICATE KEY UPDATE name = name;

-- ========================================
-- MAINTENANCE QUERIES
-- ========================================

-- Clean up expired password reset codes (Run daily via cron job)
-- DELETE FROM password_reset_codes WHERE expires_at < NOW() OR verified = 1;

-- Clean up old pending orders (Optional - older than 7 days)
-- UPDATE orders SET status = 'cancelled' WHERE status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

-- ========================================
-- USEFUL QUERIES FOR MONITORING
-- ========================================

-- Total revenue by month
-- SELECT
--     DATE_FORMAT(o.created_at, '%Y-%m') as month,
--     COUNT(*) as total_orders,
--     SUM(t.price) as revenue
-- FROM orders o
-- JOIN templates t ON o.template_id = t.id
-- WHERE o.status IN ('paid', 'delivered')
-- GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
-- ORDER BY month DESC;

-- Top selling templates
-- SELECT
--     t.name,
--     COUNT(o.id) as total_sales,
--     SUM(t.price) as total_revenue
-- FROM templates t
-- LEFT JOIN orders o ON t.id = o.template_id AND o.status IN ('paid', 'delivered')
-- GROUP BY t.id
-- ORDER BY total_sales DESC;

-- User purchase history
-- SELECT
--     u.name,
--     u.email,
--     COUNT(o.id) as total_purchases,
--     SUM(t.price) as total_spent
-- FROM users u
-- LEFT JOIN orders o ON u.id = o.user_id AND o.status IN ('paid', 'delivered')
-- LEFT JOIN templates t ON o.template_id = t.id
-- GROUP BY u.id
-- ORDER BY total_spent DESC;

-- ========================================
-- SECURITY NOTES
-- ========================================
-- 1. Change default admin password immediately after setup
-- 2. Use prepared statements for all queries (prevent SQL injection)
-- 3. Hash all passwords with password_hash() (bcrypt)
-- 4. Implement CSRF tokens on all forms
-- 5. Use rate limiting for login/register attempts
-- 6. Enable HTTPS in production
-- 7. Regularly backup database
-- 8. Monitor error logs for suspicious activity
