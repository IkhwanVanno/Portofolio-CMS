-- Portfolio Database Setup
-- Run this once to initialize the database
-- Default login: admin@portfolio.com / admin123

CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS site_setup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) DEFAULT 'My Portfolio',
    footer VARCHAR(255) DEFAULT '',
    sub_one VARCHAR(100) DEFAULT '',
    sub_one_desc TEXT,
    sub_two VARCHAR(100) DEFAULT '',
    sub_two_desc TEXT,
    sub_three VARCHAR(100) DEFAULT '',
    sub_three_desc TEXT,
    title_pengalaman VARCHAR(255) DEFAULT '',
    desc_pengalaman TEXT,
    title_perkerjaan VARCHAR(255) DEFAULT '',
    desc_perkerjaan TEXT,
    title_sertifikat VARCHAR(255) DEFAULT '',
    desc_sertifikat TEXT,
    title_galeri VARCHAR(255) DEFAULT '',
    desc_galeri TEXT,
    title_kontak VARCHAR(255) DEFAULT '',
    desc_kontak TEXT,
);

CREATE TABLE IF NOT EXISTS about_me (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) DEFAULT '',
    description TEXT,
    profile VARCHAR(255) DEFAULT '',
    intro TEXT,
    bg_intro VARCHAR(255) DEFAULT '',
    whatsapp VARCHAR(100) DEFAULT '',
    whatsapp_link VARCHAR(255) DEFAULT '',
    instagram VARCHAR(100) DEFAULT '',
    instagram_link VARCHAR(255) DEFAULT '',
    email VARCHAR(255) DEFAULT '',
    github VARCHAR(255) DEFAULT '',
    linkedin VARCHAR(255) DEFAULT ''
);

CREATE TABLE IF NOT EXISTS galery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pengalaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS perkerjaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    link VARCHAR(500) DEFAULT '',
    image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sertifikat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: admin@portfolio.com / admin123
INSERT IGNORE INTO users (email, password, username) VALUES
('admin@portfolio.com', '$2y$10$TKh8H1.PfunDqIlnVsOIuuFMVnMnGoqIGbUG4fCcPfkHE9v.BaJua', 'Admin');

INSERT IGNORE INTO site_setup (id, title, footer) VALUES (1, 'My Portfolio', 'All Rights Reserved');
INSERT IGNORE INTO about_me (id, title, description, intro) VALUES (1, 'About Me', 'Hello!', 'Hello, World');
