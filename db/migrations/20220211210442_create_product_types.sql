-- migrate:up

CREATE TABLE product_types (
	id	INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name	VARCHAR(25) NOT NULL
);

-- migrate:down

DROP TABLE IF EXISTS product_types;