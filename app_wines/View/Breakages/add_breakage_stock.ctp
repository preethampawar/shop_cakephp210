<?php $this->start('stock_reports_menu'); ?>
<?php echo $this->element('breakage_stock_menu'); ?>
<?php echo $this->element('stock_report_menu'); ?>
<?php $this->end(); ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/breakages/viewBreakageStock">Breakage Stock</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <h1>Add Breakage Stock</h1>
<?php
if ($productsInfo) {
    ?>
    <script type="text/javascript">
        var unitSellingPrice = [];
        var availableQty = [];
        <?php
        foreach($productsInfo as $row) {
        ?>
        unitSellingPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['unit_selling_price'];?>';
        availableQty['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['ProductStockReport']['balance_qty'];?>';
        <?php
        }
        ?>

        function setDefaultProductPrice() {
            var productID = $('#BreakageProductId').val();
            var unitPrice = parseFloat((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
            $('#BreakageUnitPrice').val(unitPrice);
        }

        function setTotalPrice() {
            var productID = $('#BreakageProductId').val();
            var iBreakageQty = parseInt(($('#BreakageBreakageStockQty').val() > 0) ? $('#BreakageBreakageStockQty').val() : 0);
            var iAvailableQty = parseInt((availableQty[productID] > 0) ? availableQty[productID] : 0);
            var iTotalUnits = iBreakageQty;
            var oUnitPrice = parseInt((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
            var oTotalPrice = 0;

            if (iTotalUnits <= 0) {
                //alert('Product is out of stock');
            } else {
                var oTotalPrice = ((iTotalUnits * oUnitPrice) > 0) ? (iTotalUnits * oUnitPrice).toFixed(2) : 0;
            }

            if (iAvailableQty <= 0) {
                $('#AddBreakageProduct').attr('title', 'Product is out of stock');
            } else {
                if (iTotalUnits > iAvailableQty) {
                    $('#AddBreakageProduct').attr('title', 'Breakage Quantity should be less than or equal to ' + iAvailableQty);
                } else {
                    if (iBreakageQty <= 0) {
                        $('#AddBreakageProduct').attr('title', 'Breakage Quantity should be greater that "0"');
                    } else {
                        $('#AddBreakageProduct').attr('title', '');
                    }
                }
            }

            // set hidden variables
            $('#BreakageTotalUnits').val(iTotalUnits);
            $('#BreakageUnitPrice').val(oUnitPrice);
            $('#BreakageTotalAmount').val(oTotalPrice);

            // set output
            $('#oAvailableQty').text(iAvailableQty);
            $('#oBreakageQty').text(iTotalUnits);
            $('#oUnitPrice').text(oUnitPrice);
            $('#oTotalPrice').text(oTotalPrice);
        }

        function submitButtonMsg() {
            setTotalPrice();
            if (parseInt($('#AddBreakageProduct').attr('title').length) > 0) {
                alert($('#AddBreakageProduct').attr('title'));
                return false;
            }
            return true;
        }
    </script>


    <div id="AddBreakageProductDiv" class="mt-3 col-lg-6">
        <?php
        echo $this->Form->create();
        echo $this->Form->input('total_units', ['type' => 'hidden']);
        echo $this->Form->input('unit_price', ['type' => 'hidden']);
        echo $this->Form->input('total_amount', ['type' => 'hidden']);
        echo $this->Form->input('reference', ['type' => 'hidden', 'value' => '#BreakageStock']);
        ?>
        <div class="mt-3">
            <label for="">Product [available qty]</label>
            <?=
            $this->Form->input(
                'product_id',
                [
                    'class' => 'autoSuggest form-control form-control-sm',
                    'empty' => false,
                    'label' => false,
                    'required' => true,
                    'type' => 'select',
                    'options' => $productsList,
                    'onchange' => 'setDefaultProductPrice(); setTotalPrice();',
                    'autofocus' => true,
                    'escape' => false,
                ]
            )
            ?>
        </div>
        <div class="mt-3">
            <label for="BreakageBreakageStockQty">Breakage Quantity</label>
            <?=
            $this->Form->input(
                'breakage_stock_qty',
                [
                    'type' => 'number',
                    'value' => '0',
                    'min' => '0',
                    'max' => '99999',
                    'label' => false,
                    'required' => true,
                    'oninput' => 'setTotalPrice()',
                    'title' => 'Values should be between 0 to 99999',
                    'class' => 'form-control form-control-sm'
                ]
            )
            ?>
        </div>
        <div class="mt-3">
            <label for="BreakageBreakageDate">Breakage Date</label>
            <input
                type="date"
                id="BreakageBreakageDate"
                class="form-control form-control-sm"
                name="data[Breakage][breakage_date]"
                value = "<?= $this->data['Breakage']['breakage_date'] ?? date('Y-m-d') ?>"
            >
        </div>
        <div class="mt-4">
            <table class="table table-sm table-striped small">
                <tr>
                    <th>Available Qty</th>
                    <th>Breakage Qty</th>
                    <th>Unit Price (MRP)</th>
                    <th>Total Amount</th>
                </tr>
                <tr>
                    <td><span id="oAvailableQty">0</span></td>
                    <td><span id="oBreakageQty">0</span></td>
                    <td><span id="oUnitPrice">0</span></td>
                    <td><span id="oTotalPrice" style="">0</span></td>
                </tr>
            </table>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-sm btn-primary">Add Product</button>
        </div>

        <?php
        echo $this->Form->end();
        ?>
        <script type="text/javascript">
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
    <h5 class="mt-3">Recently Added Products</h5>
    <?php
    if ($breakageProducts) {
        ?>
        <table class='table table-sm table-striped small'>
            <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Category</th>
                <th>Product</th>
                <th>Breakage Qty</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($breakageProducts as $row) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['Breakage']['breakage_date'])); ?></td>
                    <td><?php echo $row['Breakage']['category_name']; ?></td>
                    <td><?php echo $row['Breakage']['product_name']; ?></td>
                    <td style="text-align:center;"><?php echo $row['Breakage']['total_units']; ?></td>
                    <td><?php echo $row['Breakage']['unit_price']; ?></td>
                    <td><?php echo $row['Breakage']['total_amount']; ?></td>
                    <td class="text-center">
                        <form method="post" style="" name="invoice_remove_product_<?php echo $row['Breakage']['id']; ?>" id="invoice_remove_product_<?php echo $row['Breakage']['id']; ?>" action="<?php echo $this->Html->url("/breakages/removeProduct/" . $row['Breakage']['id']); ?>">
                            <a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this product <?php echo $row['Breakage']['product_name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Breakage']['id']; ?>').submit(); } event.returnValue = false; return false;" class="text-danger">
                                <span class="fa fa-trash-alt" aria-hidden="true"></span>
                            </a>
                        </form>
                        <?php //echo $this->Form->postLink('Remove', array('controller'=>'breakages', 'action'=>'removeProduct', $row['Breakage']['id']), array('title'=>'Remove product from the list - '.$row['Breakage']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Breakage']['product_name'].'"?');?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

    <?php } else { ?>
        <p>No breakages found</p>
    <?php } ?>

    <?php
} else {
    echo 'No products found. You need to add products to continue.';
}
?>