-- migrate:up

ALTER TABLE products  
ADD price FLOAT NOT NULL;  

-- migrate:down

ALTER TABLE products  
DROP COLUMN price FLOAT NOT NULL;  