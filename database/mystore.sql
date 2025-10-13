-- 1. users хүснэгт
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. templates хүснэгт
CREATE TABLE templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    thumbnail VARCHAR(255),
    demo_url VARCHAR(255),
    file_path VARCHAR(255),
    category VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. template_screenshots хүснэгт
CREATE TABLE template_screenshots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    template_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE CASCADE
);

-- 4. orders хүснэгт
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    template_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('pending', 'paid', 'delivered', 'cancelled') DEFAULT 'pending',
    download_token VARCHAR(255),
    token_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (template_id) REFERENCES templates(id)
);

-- 5. admins хүснэгт
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Анхны админ үүсгэх (username: admin, password: admin123)
INSERT INTO admins (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@yoursite.com');

-- Test template-үүд нэмэх
INSERT INTO templates (name, description, price, category, status) VALUES
('E-commerce Template', 'Professional online shop template', 49.00, 'E-commerce', 'active'),
('Portfolio Template', 'Creative portfolio for designers', 29.00, 'Portfolio', 'active'),
('Landing Page', 'High converting landing page', 19.00, 'Landing Page', 'active');
