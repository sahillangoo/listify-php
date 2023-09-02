-- Date: 2021-03-28 17:00:00
-- Listify DB Schema
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(20) NOT NULL UNIQUE,
    phone BIGINT(12) UNSIGNED NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(50) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active' NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user' NOT NULL,
    user_since TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS sessions (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    session_token VARCHAR(32) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS listings (
    id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) UNSIGNED NOT NULL,
    businessName VARCHAR(50) NOT NULL DEFAULT 'No business name provided',
    description TEXT NOT NULL DEFAULT 'No description provided',
    category VARCHAR(20) NOT NULL DEFAULT 'other',
    featured BOOLEAN NOT NULL DEFAULT 0,
    active BOOLEAN NOT NULL DEFAULT 1,
    latitude FLOAT NOT NULL DEFAULT 0,
    longitude FLOAT NOT NULL DEFAULT 0,
    address VARCHAR(50) NOT NULL DEFAULT 'No address provided',
    city VARCHAR(20) NOT NULL DEFAULT 'No city provided',
    pincode INT(6) UNSIGNED NOT NULL DEFAULT 0,
    phoneNumber BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
    email VARCHAR(20) NOT NULL DEFAULT 'No email provided',
    whatsapp BIGINT(10) UNSIGNED,
    facebookId VARCHAR(20),
    instagramId VARCHAR(20),
    website VARCHAR(20),
    displayImage VARCHAR(20) NOT NULL DEFAULT 'default.jpg',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS reviews (
    id INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    rating FLOAT NOT NULL DEFAULT 0,
    review TEXT(200) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT(11) UNSIGNED NOT NULL,
    listing_id INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (listing_id) REFERENCES listings(id)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
--@block
INSERT INTO users (
        username,
        email,
        phone,
        password,
        profile_image,
        status,
        role,
        user_since
    )
VALUES (
        'sahillangoo',
        'sahilahmed3066@gmail.com',
        7006588022,
        '$argon2id$v=19$m=2048,t=4,p=1$SGg2elliQ2JZbUVYeEpVeA$MtwHADtrs/912gP60PvgfZL+PKExiyN3sCaLF518qpU',
        'https://api.dicebear.com/6.x/micah/svg?seed=sahillangoo&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6',
        'active',
        'admin',
        '2023-08-07 16:16:11'
    );
--@block
INSERT INTO listings (
        user_id,
        businessName,
        description,
        category,
        featured,
        active,
        latitude,
        longitude,
        address,
        city,
        pincode,
        phoneNumber,
        email,
        whatsapp,
        facebookId,
        instagramId,
        website,
        displayImage,
        createdAt,
        updatedAt
    )
VALUES (
        1,
        'Winterfell Cafe',
        'Best Place to Enjoy your time with friends and family in Srinagar Kashmir with a beautiful view of Dal Lake and Zabarwan Hills. We serve the best food in town. We have a wide range of food items to choose from.  ',
        'restaurant',
        1,
        1,
        74.8765,
        34.8765,
        'Boulevard Road Dal lake',
        'srinagar',
        190001,
        9876543210,
        'demo@demo.com',
        9876543210,
        'winterfell',
        'winterfell',
        'https://winterfell.com',
        'Winterfell.jpg',
        '2023-08-15 13:31:37',
        '2023-08-15 13:31:37'
    ),
    (
        1,
        'J&K Bank',
        'J&K Bank functions as a universal bank in Jammu & Kashmir and as a specialised bank in the rest of the country. It is also the only private sector bank designated as RBI’s agent for banking business',
        'bank',
        1,
        1,
        74.8765,
        34.8765,
        'Residency Road',
        'srinagar',
        190001,
        9876543210,
        'jkbank@demo.com',
        9876543210,
        'jkbank',
        'jkbank',
        'https://jkbank.com',
        'default.jpg',
        '2023-08-15 13:31:37',
        '2023-08-15 13:31:37'
    );
INSERT INTO reviews (
        rating,
        review,
        user_id,
        listing_id
    )
VALUES (
        4,
        'This is a good place to hangout with friends and family. The food is good and the service is also good. The view is amazing. I would recommend this place to everyone.',
        1,
        1
    );
--@block
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS listings;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
