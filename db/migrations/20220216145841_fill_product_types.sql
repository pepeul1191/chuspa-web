-- migrate:up

INSERT INTO product_types (id, name) VALUES 
  (1, "Handlebar bag"),
  (2, "Sten bag"),
  (3, "Top tube bag"),
  (4, "Frame bag"),
  (5, "Seat bag"),
  (6, "Pannier bag");


-- migrate:down

TRUNCATE product_types;