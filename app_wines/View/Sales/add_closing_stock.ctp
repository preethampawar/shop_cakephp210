<?php $this->start('stock_reports_menu'); ?>
<?php echo $this->element('stock_menu'); ?>
<?php echo $this->element('stock_report_menu'); ?>
<?php $this->end(); ?>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/sales/viewClosingStock">Closing Stock</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Closing Stock </li>
        </ol>
    </nav>

    <h1>Add Closing Stock</h1>

<?php
if ($productsInfo) {
    ?>
    <script>
        var unitSellingPrice = [];
        var availableQty = [];
        <?php
        foreach($productsInfo as $productId => $row) {
        ?>
        unitSellingPrice['<?php echo $productId;?>'] = '<?php echo $row['unit_selling_price'];?>';
        availableQty['<?php echo $productId;?>'] = '<?php echo $row['balance_qty'];?>';
        <?php
        }
        ?>

        function setDefaultProductPrice() {
            var productID = $('#SaleProductId').val();
            var unitPrice = parseFloat((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
            $('#SaleUnitPrice').val(unitPrice);
        }

        function setTotalPrice() {
            var productID = $('#SaleProductId').val();
            var productName = $('#SaleProductId option:selected').text();
            var iClosingQty = parseInt(($('#SaleClosingStockQty').val() > 0) ? $('#SaleClosingStockQty').val() : 0);
            var iAvailableQty = parseInt((availableQty[productID] > 0) ? availableQty[productID] : 0);
            var oUnitPrice = parseInt((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
            var oTotalPrice = 0;
            var iTotalUnits = parseInt(iAvailableQty - iClosingQty);

            if (iTotalUnits <= 0) {
                //alert('Product is out of stock');
            } else {
                var oTotalPrice = ((iTotalUnits * oUnitPrice) > 0) ? (iTotalUnits * oUnitPrice).toFixed(2) : 0;
            }


            if (iAvailableQty <= 0) {
                $('#SubmitForm').attr('title', 'Product is out of stock');
            } else {
                if (iTotalUnits <= 0) {
                    $('#SubmitForm').attr('title', 'Closing Quantity should be less than ' + iAvailableQty);
                } else {
                    if (oTotalPrice <= 0) {
                        $('#SubmitForm').attr('title', 'Total amount should be greater than 0');
                    } else {
                        $('#SubmitForm').attr('title', '');
                    }
                }
            }

            // set hidden variables
            $('#SaleTotalUnits').val(iTotalUnits);
            $('#SaleUnitPrice').val(oUnitPrice);
            $('#SaleTotalAmount').val(oTotalPrice);

            // set output
            $('#productName').text(productName);
            $('#oAvailableQty').text(iAvailableQty);
            $('#oSaleQty').text(iTotalUnits);
            $('#oClosingQty').text(iClosingQty);
            $('#oUnitPrice').text(oUnitPrice);
            $('#oTotalPrice').text(oTotalPrice);
        }

        function submitButtonMsg() {
            setTotalPrice();
            if (parseInt($('#SubmitForm').attr('title').length) > 0) {
                alert($('#SubmitForm').attr('title'));
                return false;
            }
            return true;
        }
    </script>


    <div id="AddSaleProductDiv" class="col-lg-6">
        <?= $this->Form->create() ?>

        <div>
            <?php
            echo $this->Form->input('total_units', ['type' => 'hidden']);
            echo $this->Form->input('unit_price', ['type' => 'hidden']);
            echo $this->Form->input('total_amount', ['type' => 'hidden']);
            echo $this->Form->input('reference', ['type' => 'hidden', 'value' => '#ClosingStock']);
            ?>
        </div>
        <div class="mt-3">
            <label for="SaleSaleDate">Sale Date</label>
            <input
                type="date"
                id="SaleSaleDate"
                name="data[Sale][sale_date]"
                value="<?= $this->data['Sale']['sale_date'] ?? date('Y-m-d') ?>"
                class="form-control form-control-sm"
                required
            >
        </div>
        <div class="mt-3">
            <label for="SaleProductId">Product [available qty]</label>
            <?=
            $this->Form->input(
                'product_id',
                [
                    'id' => 'SaleProductId',
                    'empty' => false,
                    'label' => false,
                    'required' => true,
                    'type' => 'select',
                    'options' => $productsList,
                    'onchange' => 'setDefaultProductPrice(); setTotalPrice();',
                    'autofocus' => true,
                    'escape' => false,
                    'class' => 'autoSuggest form-control form-control-sm'
                ]
            )
            ?>
        </div>
        <div class="mt-3">
            <label for="SaleClosingStockQty">Closing Quantity</label>
            <?=
            $this->Form->input(
                'closing_stock_qty',
                [
                    'type' => 'number',
                    'min' => '0',
                    'max' => '99999',
                    'label' => false,
                    'required' => true,
                    'oninput' => 'setTotalPrice()',
                    'title' => 'Values should be between 0 to 99999',
                    'class' => 'form-control form-control-sm',
                ]
            );
            ?>
        </div>
        <div class="mt-3">
            <table class="table table-sm small table-striped">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Closing Quantity</th>
                    <th>Sale Quantity</th>
                    <th>Unit Selling Price (MRP)</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span id="productName"></span></td>
                    <td><span id="oClosingQty">0</span></td>
                    <td><span id="oSaleQty">0</span></td>
                    <td><span id="oUnitPrice">0</span></td>
                    <td><span id="oTotalPrice" style="">0</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-sm" onclick="return submitButtonMsg()">Add Product</button>
        </div>

        <?= $this->Form->end() ?>

        <script>
            <?php
            if(!(isset($this->data)) or ($this->Session->check('selectedProductID')))
            {
            ?>
            setDefaultProductPrice();
            <?php
            }
            ?>
            setTotalPrice();
        </script>
    </div>

    <br>
    <hr>
    <div class="mt-3">
        <h5>5 recently Added Products</h5>
    </div>
    <?php
    if ($saleProducts) {
        ?>
        <table class='table table-sm table-striped small mt-3'>
            <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Category</th>
                <?php
                if ($this->Session->read('Store.show_brands_in_products')) {
                    ?>
                    <th>Brand</th>
                    <?php
                }
                ?>
                <th>Product</th>
                <th>Closing Qty</th>
                <th>Sale Units</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($saleProducts as $row) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date'])); ?></td>
                    <td><?php echo $row['Sale']['category_name']; ?></td>
                    <?php
                    if ($this->Session->read('Store.show_brands_in_products')) {
                        ?>
                        <td><?php echo $row['Product']['Brand'] ? $row['Product']['Brand']['name'] : '-'; ?></td>
                        <?php
                    }
                    ?>
                    <td><?php echo $row['Sale']['product_name']; ?></td>
                    <td><?php echo $row['Sale']['closing_stock_qty']; ?></td>
                    <td><?php echo $row['Sale']['total_units']; ?></td>
                    <td><?php echo $row['Sale']['unit_price']; ?></td>
                    <td><?php echo $row['Sale']['total_amount']; ?></td>
                    <td class="text-center">
                        <form method="post" style="" name="sales_<?php echo $row['Sale']['id']; ?>" id="sales_<?php echo $row['Sale']['id']; ?>" action="<?php echo $this->Html->url("/sales/removeProduct/" . $row['Sale']['id']); ?>">
                            <a
                                href="#"
                                name="Remove"
                                onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name']; ?> from the list?')) { $('#sales_<?php echo $row['Sale']['id']; ?>').submit(); } event.returnValue = false; return false;"
                                class="text-danger"
                            >
                                <span class="fa fa-trash-alt" aria-hidden="true"></span>
                            </a>
                        </form>
                        <?php //echo $this->Form->postLink('Remove', array('controller'=>'sales', 'action'=>'removeProduct', $row['Sale']['id']), array('title'=>'Remove product from sale - '.$row['Sale']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Sale']['product_name'].'"?');?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

    <?php } else { ?>
        <p>No sales found</p>
    <?php } ?>

    <?php
} else {
    echo 'No products found. You need to add products to continue.';
}
?>