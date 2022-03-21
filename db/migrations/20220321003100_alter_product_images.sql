-- migrate:up

ALTER TABLE product_images  
ADD color VARCHAR(6) NOT NULL;  

-- migrate:down

ALTER TABLE product_images  
DROP COLUMN color VARCHAR(6) NOT NULL;  