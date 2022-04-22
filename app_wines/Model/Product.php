<?php
App::uses('AppModel', 'Model');

class Product extends AppModel
{
    public $name = 'Product';

    var $belongsTo = array('ProductCategory', 'Brand');

    var $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'required' => false,
                'message' => 'Product name is a required field'
            ),
            'between' => array(
                'rule' => array('between', 2, 100),
                'message' => 'Product name should be minimum of 2 characters and maximum of 100 characters'
            )
        )
    );

    public function getProductStockReport($storeId)
    {
        $data = $this->query("CALL product_stock_report($storeId)");
        $result = [];
        if ($data) {
            foreach ($data as $row) {
                $result[$row['p']['product_id']]['product_id'] = $row['p']['product_id'];
                $result[$row['p']['product_id']]['product_name'] = $row['p']['product_name'];
                $result[$row['p']['product_id']]['unit_selling_price'] = $row['p']['unit_selling_price'];
                $result[$row['p']['product_id']]['box_buying_price'] = $row['p']['box_buying_price'];
                $result[$row['p']['product_id']]['box_qty'] = $row['p']['box_qty'];
                // $result[$row['p']['product_id']]['store_id'] = $row['p']['store_id'];
                $result[$row['p']['product_id']]['category_id'] = $row['c']['category_id'];
                $result[$row['p']['product_id']]['category_name'] = $row['c']['category_name'];
                $result[$row['p']['product_id']]['brand_id'] = $row['p']['brand_id'];
                $result[$row['p']['product_id']]['brand_name'] = $row['br']['brand_name'];
                $result[$row['p']['product_id']]['purchase_qty'] = $row[0]['purchase_qty'];
                $result[$row['p']['product_id']]['purchase_amount'] = $row[0]['purchase_amount'];
                $result[$row['p']['product_id']]['sale_qty'] = $row[0]['sale_qty'];
                $result[$row['p']['product_id']]['sale_amount'] = $row[0]['sale_amount'];
                $result[$row['p']['product_id']]['breakage_qty'] = $row[0]['breakage_qty'];
                $result[$row['p']['product_id']]['breakage_amount'] = $row[0]['breakage_amount'];
                $result[$row['p']['product_id']]['balance_qty'] = $row[0]['balance_qty'];
                $result[$row['p']['product_id']]['profit_amount'] = $row[0]['profit_amount'];
            }
        }
        return $result;
    }

    public function getDatewiseProductStockReport($storeId, $fromDate, $toDate)
    {
        $data = $this->query("CALL datewise_product_stock_report($storeId, '$fromDate', '$toDate')");
        $result = [];
        if ($data) {
            foreach ($data as $row) {
                $result[$row['p']['product_id']]['product_id'] = $row['p']['product_id'];
                $result[$row['p']['product_id']]['product_name'] = $row['p']['product_name'];
                $result[$row['p']['product_id']]['unit_selling_price'] = $row['p']['unit_selling_price'];
                $result[$row['p']['product_id']]['box_buying_price'] = $row['p']['box_buying_price'];
                $result[$row['p']['product_id']]['box_qty'] = $row['p']['box_qty'];
                // $result[$row['p']['product_id']]['store_id'] = $row['p']['store_id'];
                $result[$row['p']['product_id']]['category_id'] = $row['c']['category_id'];
                $result[$row['p']['product_id']]['category_name'] = $row['c']['category_name'];
                $result[$row['p']['product_id']]['brand_id'] = $row['p']['brand_id'];
                $result[$row['p']['product_id']]['brand_name'] = $row['br']['brand_name'];
                $result[$row['p']['product_id']]['purchase_qty'] = $row[0]['purchase_qty'];
                $result[$row['p']['product_id']]['purchase_amount'] = $row[0]['purchase_amount'];
                $result[$row['p']['product_id']]['sale_qty'] = $row[0]['sale_qty'];
                $result[$row['p']['product_id']]['sale_amount'] = $row[0]['sale_amount'];
                $result[$row['p']['product_id']]['breakage_qty'] = $row[0]['breakage_qty'];
                $result[$row['p']['product_id']]['breakage_amount'] = $row[0]['breakage_amount'];
                $result[$row['p']['product_id']]['balance_qty'] = $row[0]['balance_qty'];
                $result[$row['p']['product_id']]['profit_amount'] = $row[0]['profit_amount'];
            }
        }
        return $result;
    }
}