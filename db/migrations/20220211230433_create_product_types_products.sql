-- migrate:up

CREATE TABLE product_types_products (
	id	INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  product_type_id INTEGER NOT NULL,
  product_id INTEGER NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (product_type_id) REFERENCES product_types(id) ON DELETE CASCADE
);

-- migrate:down

DROP TABLE IF EXISTS product_types_products;