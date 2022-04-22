<?php
class Report extends AppModel {
	public $useTable = false;

	public function getProductWiseOpeningStock($storeId, $date) {
		$sql = "
			SELECT pr.id, pr.name, pr.box_qty, pr.box_buying_price, pr.unit_selling_price,
				COALESCE(purchases.total_units, 0) AS purchase_units,
				COALESCE(sales.total_units, 0) AS sale_units,
				COALESCE(breakages.total_units, 0) AS breakage_units,
				(COALESCE(purchases.total_units, 0)-COALESCE(sales.total_units, 0)-COALESCE(breakages.total_units, 0)) AS closing_units,
				COALESCE(purchases.total_amount, 0) as purchase_amount,
				COALESCE(sales.total_amount, 0) as sale_amount,
				COALESCE(breakages.total_amount, 0) as breakage_amount,
				((COALESCE(purchases.total_units, 0)-COALESCE(sales.total_units, 0)-COALESCE(breakages.total_units, 0)) * pr.box_buying_price / pr.box_qty) AS closing_amount_as_per_invoice,
				(COALESCE(purchases.total_units, 0)-COALESCE(sales.total_units, 0)-COALESCE(breakages.total_units, 0)) * pr.unit_selling_price AS closing_amount_as_per_mrp

			FROM products pr
				LEFT JOIN
					(
						SELECT
							product_id, SUM(total_units) total_units, sum(total_amount) total_amount
						FROM purchases
						WHERE
							store_id = '$storeId'
							AND purchase_date < '$date'
						GROUP BY product_id
					) AS purchases ON pr.id = purchases.product_id

				LEFT JOIN
					(
						SELECT
							product_id, SUM(total_units) total_units, sum(total_amount) total_amount
						FROM sales
						WHERE
							store_id = '$storeId'
							AND sale_date < '$date'
						GROUP BY product_id
					) AS sales ON pr.id = sales.product_id

				LEFT JOIN
					(
						SELECT
							product_id, SUM(total_units) total_units, sum(total_amount) total_amount
						FROM breakages
						WHERE
							store_id = '$storeId'
							AND breakage_date < '$date'
						GROUP BY product_id
					) AS breakages ON pr.id = breakages.product_id

			WHERE pr.store_id = '$storeId'
		";

		return $this->query($sql);
	}

	public function getProductWiseClosingStock($storeId, $fromDate, $toDate) {
		$productWiseOpeningStock = $this->getProductWiseOpeningStock($storeId, $fromDate);

		$openingStockProducts = [];
		if ($productWiseOpeningStock) {
			foreach($productWiseOpeningStock as $row) {
				$openingStockProducts[$row['pr']['id']] = [
//					'product_id' => $row['pr']['name'],
//					'product_name' => $row['pr']['name'],
					'opening_units' => $row[0]['closing_units'],
					'opening_amount_as_per_invoice' => $row[0]['closing_amount_as_per_invoice'],
					'opening_amount_as_per_mrp' => $row[0]['closing_amount_as_per_mrp'],
				];
			}
		}

		$sql = "
			SELECT pr.id, pr.name, pr.box_qty, pr.box_buying_price, pr.unit_selling_price,
				COALESCE(purchases.total_units, 0) AS purchase_units,
				COALESCE(sales.total_units, 0) AS sale_units,
				COALESCE(breakages.total_units, 0) AS breakage_units,
				COALESCE(purchases.total_amount, 0) as purchase_amount,
				COALESCE(sales.total_amount, 0) as sale_amount,
				COALESCE(breakages.total_amount, 0) as breakage_amount

			FROM products pr
				LEFT JOIN
					(
						SELECT
							product_id, SUM(total_units) total_units, sum(total_amount) total_amount
						FROM purchases
						WHERE
							store_id = '$storeId'
							AND purchase_date BETWEEN '$fromDate' and '$toDate'
						GROUP BY product_id
					) AS purchases ON pr.id = purchases.product_id

				LEFT JOIN
					(
						SELECT
							product_id, SUM(total_units) total_units, sum(total_amount) total_amount
						FROM sales
						WHERE
							store_id = '$storeId'
							AND sale_date BETWEEN '$fromDate' and '$toDate'
						GROUP BY product_id
					) AS sales ON pr.id = sales.product_id

				LEFT JOIN
					(
						SELECT
							product_id, SUM(total_units) total_units, sum(total_amount) total_amount
						FROM breakages
						WHERE
							store_id = '$storeId'
							AND breakage_date BETWEEN '$fromDate' and '$toDate'
						GROUP BY product_id
					) AS breakages ON pr.id = breakages.product_id

			WHERE pr.store_id = '$storeId'
		";

		$productWiseClosingStock = $this->query($sql);

		if ($productWiseClosingStock) {
			foreach($productWiseClosingStock as &$row) {
				$productId = $row['pr']['id'];
				$productBoxQty = (int)($row['pr']['box_qty'] > 0 ? $row['pr']['box_qty'] : 1);
				$productBoxBuyingPrice = (float)$row['pr']['box_buying_price'];
				$productUnitSellingPrice = (float)$row['pr']['unit_selling_price'];
				$unitProductPurchasePrice = $productBoxBuyingPrice / $productBoxQty;

				$purchaseUnits = (int)$row[0]['purchase_units'];
				$saleUnits = (int)$row[0]['sale_units'];
				$breakageUnits = (int)$row[0]['breakage_units'];
				$openingUnits = (int)$openingStockProducts[$productId]['opening_units'];
				$closingUnits = $openingUnits + $purchaseUnits - $saleUnits - $breakageUnits;
				$closingAmountAsPerInvoice = $closingUnits * $unitProductPurchasePrice;
				$closingAmountAsPerMrp = $closingUnits * $productUnitSellingPrice;

				$row[0]['opening_units'] = $openingUnits;
				$row[0]['opening_amount_as_per_invoice'] = $openingStockProducts[$productId]['opening_amount_as_per_invoice'];
				$row[0]['opening_amount_as_per_mrp'] = $openingStockProducts[$productId]['opening_amount_as_per_mrp'];
				$row['pr']['unit_purchase_price'] = $unitProductPurchasePrice;
				$row[0]['closing_units'] = $closingUnits;
				$row[0]['closing_amount_as_per_invoice'] = $closingAmountAsPerInvoice;
				$row[0]['closing_amount_as_per_mrp'] = $closingAmountAsPerMrp;
			}
		}

		return $productWiseClosingStock;
	}

	public function getOpeningStockValue($storeId, $date)
	{
		$closingAmountAsPerInvoice = 0;
		$closingAmountAsPerMrp = 0;

		$result = [
			'openingStockValueAsPerInvoice' => $closingAmountAsPerInvoice,
			'openingStockValueAsPerMrp' => $closingAmountAsPerMrp,
		];

		$productWiseOpeningStock = $this->getProductWiseOpeningStock($storeId, $date);

		if(empty($productWiseOpeningStock)) {
			return $result;
		}

		foreach($productWiseOpeningStock as $row) {
			$closingAmountAsPerInvoice += (float)$row[0]['closing_amount_as_per_invoice'];
			$closingAmountAsPerMrp += (float)$row[0]['closing_amount_as_per_mrp'];
		}

		$result = [
			'openingStockValueAsPerInvoice' => $closingAmountAsPerInvoice,
			'openingStockValueAsPerMrp' => $closingAmountAsPerMrp,
		];

		return $result;
	}

	public function getClosingStockValue($storeId, $fromDate, $toDate)
	{
		$closingAmountAsPerInvoice = 0;
		$closingAmountAsPerMrp = 0;

		$result = [
			'openingStockValueAsPerInvoice' => $closingAmountAsPerInvoice,
			'openingStockValueAsPerMrp' => $closingAmountAsPerMrp,
		];

		$productWiseClosingStock = $this->getProductWiseClosingStock($storeId, $fromDate, $toDate);

		if(empty($productWiseClosingStock)) {
			return $result;
		}

		foreach($productWiseClosingStock as $row) {
			$closingAmountAsPerInvoice += (float)$row[0]['closing_amount_as_per_invoice'];
			$closingAmountAsPerMrp += (float)$row[0]['closing_amount_as_per_mrp'];
		}

		$result = [
			'closingStockValueAsPerInvoice' => $closingAmountAsPerInvoice,
			'closingStockValueAsPerMrp' => $closingAmountAsPerMrp,
		];

		return $result;
	}



}
