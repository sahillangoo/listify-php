-- Date: 2021-03-28 17:00:00
-- Listify DB Schema
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    phone BIGINT(12) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user' NOT NULL,
    user_since TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(100) NOT NULL,
    expiration_time TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE listings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    address VARCHAR(255),
    city VARCHAR(100),
    category VARCHAR(100),
    whatsapp_url VARCHAR(255),
    instagram_id VARCHAR(255),
    phone_number VARCHAR(20),
    email VARCHAR(255),
    business_hours TEXT,
    display_image VARCHAR(255),
    gallery_images TEXT,
    reviews_count INT DEFAULT 0,
    longitude FLOAT,
    latitude FLOAT
);

CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rating FLOAT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    listing_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (listing_id) REFERENCES listings(id)
);

-- insert data into users table
INSERT INTO users (username, email, phone, password, profile_image, role, user_since) VALUES
('JohnDoe', 'john@example.com', '123456789', 'hashed_password', 'profile.jpg', 'user', NULL),
('JaneSmith', 'jane@example.com', '9876543210', 'hashed_password', 'profile.jpg', 'user', NULL);