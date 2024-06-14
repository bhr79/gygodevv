CREATE DATABASE odev;
USE odev;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email varchar(255) not null,
    do_tar date not null,
    role ENUM('admin', 'editor', 'viewer') NOT NULL
);



CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (role_name) VALUES ('admin'), ('editor'), ('viewer');
INSERT INTO users (username, password,email,do_tar, role) VALUES 
('admin', 'adminpass','admin@admin.com','1990-01-01', 'admin'),
('editor', 'editorpass','editor@editor.com', '1990-01-02','editor'),
('viewer', 'viewerpass','viewer@viewer.com', '1990-01-03','viewer');


CREATE TABLE role_counters (
    role ENUM('admin', 'editor') NOT NULL,
    count INT NOT NULL DEFAULT 0,
    PRIMARY KEY (role)
);

INSERT INTO role_counters (role, count) VALUES ('admin', 0), ('editor', 0);

CREATE TABLE kitaplar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kitap_adi VARCHAR(255) NOT NULL,
    yazar VARCHAR(255) NOT NULL,
    fiyat DECIMAL(10, 2) NOT NULL,
    turu VARCHAR(50) Not Null
);

INSERT INTO kitaplar (kitap_adi, yazar,turu, fiyat) VALUES
('Dünyanın Merkezine Yolculuk', 'Jules Verne','Macera,Bilim Kurgu', 25.99),
('Harry Potter Felsefe Taşı', 'J.K Rowling','Fantastik,Macera', 19.99),
('Ay Düğümü', 'Gamze Çelik','Fantastik', 29.99);


ALTER TABLE kitaplar ADD COLUMN turu VARCHAR(50);
INSERT INTO kitaplar(kitap_adi, turu) VALUES 
('Dünyanın Merkezine Yolculuk', 'Macera,Bilim Kurgu'),
('Harry Potter Felsefe Taşı', 'Fantastik,Macera'),
('Ay Düğümü', 'Fantastik');


CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);








select * from users;


