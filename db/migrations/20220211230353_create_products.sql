-- migrate:up

CREATE TABLE products (
	id	INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name	VARCHAR(100) NOT NULL,
  description	TEXT NOT NULL,
  color	VARCHAR(6) NOT NULL,
  url	VARCHAR(60) NOT NULL
);

-- migrate:down

DROP TABLE IF EXISTS products;