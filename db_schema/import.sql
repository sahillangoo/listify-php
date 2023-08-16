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
CREATE TABLE listings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    businessName VARCHAR(50) NOT NULL UNIQUE DEFAULT 'No business name provided',
    description TEXT NOT NULL DEFAULT 'No description provided',
    category VARCHAR(50) NOT NULL DEFAULT 'other',
    address VARCHAR(255) NOT NULL DEFAULT 'No address provided',
    city VARCHAR(50) NOT NULL DEFAULT 'No city provided',
    pincode INT(6) NOT NULL DEFAULT 0,
    phoneNumber BIGINT(10) NOT NULL DEFAULT 0,
    email VARCHAR(50) NOT NULL DEFAULT 'No email provided',
    whatsapp BIGINT(10),
    facebookId VARCHAR(50),
    instagramId VARCHAR(50),
    website VARCHAR(50),
    displayImage VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
    latitude FLOAT NOT NULL DEFAULT 0,
    longitude FLOAT NOT NULL DEFAULT 0,
    reviewsCount INT DEFAULT 0,
    rating FLOAT DEFAULT 0,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO users (
        username,
        email,
        phone,
        password,
        profile_image,
        role,
        user_since
    )
VALUES (
        'sahillangoo',
        'sahilahmed3066@gmail.com',
        7006588022,
        '$argon2id$v=19$m=2048,t=4,p=1$SGg2elliQ2JZbUVYeEpVeA$MtwHADtrs/912gP60PvgfZL+PKExiyN3sCaLF518qpU',
        'https://api.dicebear.com/6.x/micah/svg?seed=sahillangoo&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6',
        'admin',
        '2023-08-07 16:16:11'
    );
--@block
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rating FLOAT,
    comment TEXT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    listing_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (listing_id) REFERENCES listings(id)
);
