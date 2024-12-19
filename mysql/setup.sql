DROP DATABASE IF EXISTS passwords;
CREATE DATABASE passwords;
USE passwords;

-- Drop existing tables
DROP TABLE IF EXISTS passwords;
DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
);

-- Create accounts table
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    website_name VARCHAR(100) NOT NULL,
    url VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create passwords table
CREATE TABLE passwords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    password VARBINARY(255) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE
);

-- Insert sample users
INSERT INTO users (first_name, last_name, username, email) VALUES
('Alice', 'Smith', 'asmith', 'alice.smith@example.com'),
('Bob', 'Johnson', 'bjohnson', 'bob.johnson@example.com'),
('Charlie', 'Brown', 'cbrown', 'charlie.brown@example.com');

-- Insert sample accounts
INSERT INTO accounts (user_id, website_name, url) VALUES
(1, 'GitHub', 'https://github.com'),
(1, 'Google', 'https://google.com'),
(2, 'Twitter', 'https://twitter.com'),
(3, 'Facebook', 'https://facebook.com');

-- Insert sample passwords
INSERT INTO passwords (account_id, password, comment) VALUES
(1, AES_ENCRYPT('password123', 'secret_key'), 'GitHub personal account'),
(2, AES_ENCRYPT('mygooglepass', 'secret_key'), 'Google work account'),
(3, AES_ENCRYPT('tw33tb0b', 'secret_key'), 'Twitter account'),
(4, AES_ENCRYPT('fb_secure123', 'secret_key'), 'Facebook for social media');
