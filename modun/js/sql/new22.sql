CREATE DATABASE IF NOT EXISTS tintuc;
USE tintuc;

-- Bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(100),
    email VARCHAR(100)
);

-- Bảng tin tức
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tieude VARCHAR(255),
    noidung TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users ADD role ENUM('admin', 'user') DEFAULT 'user';
INSERT INTO users (username, password, email, role) 
VALUES ('admin', MD5('admin123'), 'admin@example.com', 'admin');

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT,
    username VARCHAR(50),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (news_id) REFERENCES news(id)
);

ALTER TABLE news ADD image VARCHAR(255);

ALTER TABLE news ADD views INT DEFAULT 0;

CREATE TABLE chuyenmuc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(255) NOT NULL,
    mota TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cập nhật bảng news để liên kết với chuyên mục
ALTER TABLE news ADD COLUMN chuyenmuc_id INT NOT NULL;
ALTER TABLE news ADD CONSTRAINT fk_chuyenmuc FOREIGN KEY (chuyenmuc_id) REFERENCES chuyenmuc(id);

-- Thêm 6 chuyên mục mặc định
INSERT INTO chuyenmuc (ten) VALUES
('Thời sự'),
('Chính trị'),
('Y tế'),
('Giáo dục'),
('Khoa học'),
('Giải trí');
-- Thêm trường tên tác giả vào bảng news
ALTER TABLE news ADD author VARCHAR(100);
