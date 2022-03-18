-- migrate:up

CREATE VIEW vw_product_types_products AS
  SELECT PT.id as product_type_id, PT.name as product_type_name, P.id as product_id, P.name as product_name
    FROM products P
    JOIN product_types_products PTP ON PTP.product_id= P.id
    JOIN product_types PT ON PTP.product_type_id= PT.id
    LIMIT 2000

-- migrate:down

DROP VIEW IF EXISTS vw_product_types_products