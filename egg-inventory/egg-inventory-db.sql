CREATE DATABASE egg_inventory;
USE egg_inventory;

CREATE TABLE eggs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from eggs;