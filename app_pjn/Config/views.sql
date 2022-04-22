/* Get total purchase quantity and amount of all the products */
DROP VIEW product_purchase_report;
CREATE VIEW product_purchase_report AS
SELECT Pr.id, Pr.name product_name, Pr.product_category_id, Pr.store_id, COALESCE(SUM(Pu.total_units),0) purchase_qty, COALESCE(SUM(Pu.total_amount),0) purchase_amount FROM products Pr
  LEFT JOIN purchases Pu ON Pr.id=Pu.product_id GROUP BY Pr.id;

/* Get total sale quantity and amount of all the products */  
DROP VIEW product_sale_report;  
CREATE VIEW product_sale_report AS
SELECT Pr.id, Pr.name product_name, Pr.product_category_id, Pr.store_id, COALESCE(SUM(S.total_units),0) sale_qty, COALESCE(SUM(S.total_amount),0) sale_amount FROM products Pr
  LEFT OUTER JOIN sales S ON Pr.id=S.product_id GROUP BY Pr.id;
  
/* Get balance stock(purchase_stock-sale_stock) and balance amount(sale_amount-purchase_amount) for all the products */
DROP VIEW product_stock_report;
CREATE VIEW product_stock_report AS
SELECT p.id product_id, p.product_name, c.id category_id, c.name category_name, p.store_id, COALESCE(p.purchase_qty,0) purchase_qty, COALESCE(p.purchase_amount, 0) purchase_amount,
		COALESCE(s.sale_qty,  0) sale_qty, COALESCE(s.sale_amount, 0) sale_amount, 
		(COALESCE(p.purchase_qty,0)-COALESCE(s.sale_qty,0)) balance_qty,
		(COALESCE(s.sale_amount,0)-COALESCE(p.purchase_amount,0)) profit_amount
  FROM product_purchase_report p 
  LEFT JOIN product_sale_report s ON s.id=p.id
  LEFT JOIN product_categories c ON c.id=p.product_category_id;