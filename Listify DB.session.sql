-- Date: 2021-03-28 17:00:00
-- Listify DB Schema
--@block
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
--@block
CREATE TABLE listings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    business_name VARCHAR(50) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    address VARCHAR(255),
    city VARCHAR(100),
    whatsapp BIGINT(10),
    instagram_id VARCHAR(50),
    phone_number BIGINT(10),
    email VARCHAR(50),
    display_image VARCHAR(255),
    reviews_count INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
--@block
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
--@block
-- insert data into users table
INSERT INTO users (username, email, phone, password, profile_image, role, user_since) VALUES
('sahillangoo', 'sahilahmed3066@gmail.com', 7006588022, '$argon2id$v=19$m=2048,t=4,p=1$SGg2elliQ2JZbUVYeEpVeA$MtwHADtrs/912gP60PvgfZL+PKExiyN3sCaLF518qpU', 'https://api.dicebear.com/6.x/micah/svg?seed=sahillangoo&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6', 'admin', '2023-08-07 16:16:11');
