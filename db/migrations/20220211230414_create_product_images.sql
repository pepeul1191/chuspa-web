-- migrate:up

CREATE TABLE product_images (
	id	INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  description	VARCHAR(150) NOT NULL,
  url	VARCHAR(60) NOT NULL,
  product_id INTEGER NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- migrate:down

DROP TABLE IF EXISTS product_images;