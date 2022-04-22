<?php $this->start('purchases_report_menu'); ?>
<?php echo $this->element('purchases_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/sales/viewClosingStock">Purchases</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>

    <h1>Add New Purchase</h1>

<?php
if ($productsInfo) {
    ?>
    <script type="text/javascript">
        var unitsInBox = [];
        var unitBoxPrice = [];
        <?php
        foreach($productsInfo as $row) {
        ?>
        unitsInBox['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_qty'];?>';
        unitBoxPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_buying_price'];?>';
        <?php
        }
        ?>

        function setDefaultProductPrice() {
            var productID = $('#PurchaseProductId').val();
            var oBoxPrice = parseFloat((unitBoxPrice[productID] > 0) ? unitBoxPrice[productID] : 0);
            var oUnitsInBox = parseInt((unitsInBox[productID] > 0) ? unitsInBox[productID] : 0);
            var unitPrice = 0;
            if (parseInt(oUnitsInBox) > 0) {
                unitPrice = parseFloat((oBoxPrice / oUnitsInBox)).toFixed(2);
            }

            $('#PurchaseUnitPrice').val(unitPrice);
        }

        function setTotalPrice() {
            var productID = $('#PurchaseProductId').val();
            var iTotalUnits = parseInt(($('#PurchaseTotalUnits').val() > 0) ? $('#PurchaseTotalUnits').val() : 0);
            var oBoxPrice = parseFloat((unitBoxPrice[productID] > 0) ? unitBoxPrice[productID] : 0);
            var oUnitsInBox = parseInt((unitsInBox[productID] > 0) ? unitsInBox[productID] : 0);
            var unitPrice = parseFloat(($('#PurchaseUnitPrice').val() > 0) ? $('#PurchaseUnitPrice').val() : 0);

            var oTotalPrice = ((iTotalUnits * unitPrice) > 0) ? (iTotalUnits * unitPrice).toFixed(2) : 0;
            var oTotalUnits = iTotalUnits;
            var oTotalUnitsString = ' [' + oTotalUnits + ' units] ';

            if (iTotalUnits <= 0) {
                $('#SubmitForm').attr('title', 'No. of Units should be greater than 0');
            } else {
                $('#SubmitForm').attr('title', '');
            }

            // set hidden variables

            $('#PurchaseTotalAmount').val(oTotalPrice);

            // set output
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


    <div id="AddInvoiceProductDiv">
        <?php
        echo $this->Form->create();
        echo $this->Form->input('total_amount', ['type' => 'hidden']);
        ?>

        <div class="mt-3">
            <label for="">Select Product</label>
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
            <label for="PurchaseTotalUnits">No. of Units</label>
            <?=
            $this->Form->input(
                'total_units',
                [
                    'type' => 'number',
                    'class' => 'form-control form-control-sm',
                    'min' => '1',
                    'max' => '99999',
                    'label' => false,
                    'required' => true,
                    'oninput' => 'setTotalPrice()',
                    'title' => 'Values should be between 1 to 99999'
                ]
            );
            ?>
        </div>

        <div class="mt-3">
            <label for="PurchaseUnitPrice">Unit Price</label>
            <?=
            $this->Form->input(
                'unit_price',
                [
                    'type' => 'text',
                    'class' => 'form-control form-control-sm',
                    'label' => false,
                    'required' => true,
                    'oninput' => 'setTotalPrice()',
                    'title' => 'Unit Price'
                ]
            );
            ?>
        </div>

        <div class="mt-3">
            <label for="PurchasePurchaseDate">Purchase Date</label>
            <input
                type="date"
                id="PurchasePurchaseDate"
                class="form-control form-control-sm"
                name="data[Purchase][purchase_date]"
                value = "<?= $this->data['Purchase']['purchase_date'] ?? date('Y-m-d') ?>"
            >
        </div>

        <div class="mt-3">
            <strong>Total Amount: <span id="oTotalPrice" style="">0</span></strong>
        </div>








        <div class="mt-4">
            <button
                type="submit"
                class="btn btn-sm btn-primary"
                onclick = 'return submitButtonMsg()'
                >Add Product</button>
        </div>
        <?php
        echo $this->Form->end();
        ?>

        <script type="text/javascript">
            $(document).ready(function () {
                <?php
                $setDefaultPrice = (!(isset($this->data)) or !($this->data)) ? true : (($this->Session->check('selectedProductID')) ? true : false);
                echo ($setDefaultPrice) ? 'setDefaultProductPrice();' : null;
                echo 'setTotalPrice();';
                ?>
            });
        </script>
    </div>
    <br>
    <hr>
    <h5>10 recently purchased products</h5>
    <?php
    if ($purchaseProducts) {
        ?>
        <table class='table table-striped table-sm small mt-3'>
            <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Product</th>
                <th>No. of Boxes</th>
                <th>Box Price</th>
                <th>Total Qty</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Invoice</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($purchaseProducts as $row) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['Purchase']['purchase_date'])); ?></td>
                    <td><?php echo $row['Purchase']['product_name']; ?></td>
                    <td style="text-align:center;"><?php echo ($row['Purchase']['invoice_id']) ? $row['Purchase']['box_qty'] : '-'; ?></td>
                    <td><?php echo ($row['Purchase']['invoice_id']) ? $row['Purchase']['box_buying_price'] : '-'; ?></td>
                    <td><?php echo ($row['Purchase']['total_units']) ? $row['Purchase']['total_units'] : '-'; ?></td>
                    <td><?php echo $row['Purchase']['unit_price']; ?></td>
                    <td><?php echo $row['Purchase']['total_amount']; ?></td>
                    <td><?php echo ($row['Purchase']['invoice_id']) ? $this->Html->link($row['Purchase']['invoice_name'], ['controller' => 'invoices', 'action' => 'details', $row['Purchase']['invoice_id']], ['title' => 'Invoice Details']) : '-'; ?></td>
                    <td class="text-center">
                        <form method="post" style="" name="purchase_product_<?php echo $row['Purchase']['id']; ?>" id="purchase_product_<?php echo $row['Purchase']['id']; ?>" action="<?php echo $this->Html->url("/purchases/removeProduct/" . $row['Purchase']['id']); ?>">
                            <a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Purchase']['product_name']; ?> from the list?')) { $('#purchase_product_<?php echo $row['Purchase']['id']; ?>').submit(); } event.returnValue = false; return false;" class="text-danger">
                                <span class="fa fa-trash-alt" aria-hidden="true"></span>
                            </a>
                        </form>
                        <?php //echo $this->Form->postLink('Remove', array('controller'=>'purchases', 'action'=>'removeProduct', $row['Purchase']['id']), array('title'=>'Remove product - '.$row['Purchase']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Purchase']['product_name'].'"?');?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <br><br>

    <?php } else { ?>
        <p>No purchases found</p>
    <?php } ?>

    <?php
} else {
    echo 'No products found. You need to add products to continue.';
}
?>