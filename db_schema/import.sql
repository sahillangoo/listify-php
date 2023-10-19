-- Date: 2021-03-28 17:00:00
-- Listify DB Schema
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone BIGINT UNSIGNED NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image TEXT NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user' NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active' NOT NULL,
    verified BOOLEAN DEFAULT 0 NOT NULL,
    verification_token VARCHAR(255) NOT NULL,
    user_since TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS listings (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    businessName VARCHAR(50) NOT NULL DEFAULT 'No business name provided',
    description TEXT(999) NOT NULL DEFAULT 'No description provided',
    category VARCHAR(20) NOT NULL DEFAULT 'other',
    featured BOOLEAN DEFAULT 0 NOT NULL,
    active BOOLEAN DEFAULT 1 NOT NULL,
    latitude DOUBLE(10, 8) NOT NULL DEFAULT 0,
    longitude DOUBLE(10, 8) NOT NULL DEFAULT 0,
    address VARCHAR(50) NOT NULL DEFAULT 'No address provided',
    city VARCHAR(20) NOT NULL DEFAULT 'No city provided',
    pincode INT UNSIGNED NOT NULL DEFAULT 0,
    phoneNumber BIGINT UNSIGNED NOT NULL DEFAULT 0,
    email VARCHAR(255) NOT NULL DEFAULT 'No email provided',
    whatsapp BIGINT UNSIGNED,
    facebookId VARCHAR(50),
    instagramId VARCHAR(50),
    website VARCHAR(50),
    displayImage VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    rating FLOAT(2, 1) NOT NULL DEFAULT 0,
    review TEXT(200) NOT NULL,
    active BOOLEAN DEFAULT 1,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT UNSIGNED NOT NULL,
    listing_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (listing_id) REFERENCES listings(id)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
--@block
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS listings;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
--@block
ALTER TABLE listings
ADD INDEX active_idx (active);
ALTER TABLE listings
ADD INDEX id_idx (id);
CREATE UNIQUE INDEX idx_listings_business_city_phone ON listings (businessName, city, phoneNumber);
--@block
