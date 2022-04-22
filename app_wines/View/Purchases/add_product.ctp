<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/invoices">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $invoiceInfo['Invoice']['name'] ?></li>
        </ol>
    </nav>


    <div class="d-flex justify-content-between mt-3">
        <div class=""><h1>Add Products</h1></div>
        <div class="">
            <a href="/invoices/edit/<?= $invoiceInfo['Invoice']['id'] ?>" class="btn btn-sm btn-warning"><span class="fa fa-edit"></span> Edit Invoice</a>
            <a href="/invoices/details/<?= $invoiceInfo['Invoice']['id'] ?>" class="btn btn-sm btn-primary ml-2"><span class="fa fa-info-circle"></span> Invoice Details</a>
        </div>
    </div>


<?php
if ($productsInfo) {
    $boxQuantity = 0;
    ?>
    <script type="text/javascript">
        var unitsInBox = [];
        var unitBoxPrice = [];
        var specialMargin = [];
        <?php
        foreach($productsInfo as $row) {
        ?>
        unitsInBox['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_qty'];?>';
        unitBoxPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_buying_price'];?>';
        specialMargin['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['special_margin'];?>';
        <?php
        $boxQuantity = $row['Product']['box_qty'];
        }
        ?>

        function setExtraUnits() {
            var productID = $('#PurchaseProductId').val();
            var extra_units = parseInt(unitsInBox[productID]);
            var select_options = '';
            if (extra_units) {
                for (var i = 0; i < extra_units; i++) {
                    select_options = select_options + '<option value="' + i + '">' + i + '</option>';
                }
            }
            $('#PurchaseExtraUnits').html(select_options);

        }

        function setTotalPrice() {
            var productID = $('#PurchaseProductId').val();
            var productName = $('#PurchaseProductId option:selected').text();
            var iBoxQty = parseInt(($('#PurchaseBoxQty').val() > 0) ? $('#PurchaseBoxQty').val() : 0);
            //var iBoxQty = $('#PurchaseBoxQty').val();
            var extraUnits = $('#PurchaseExtraUnits').val();
            //alert(extraUnits);
            var iBoxQtyText = "";
            if (extraUnits != 0) {
                iBoxQtyText = iBoxQty + '.' + extraUnits;
            } else {
                iBoxQtyText = iBoxQty;
            }
            var oBoxPrice = parseFloat((unitBoxPrice[productID] > 0) ? unitBoxPrice[productID] : 0);
            var oUnitsInBox = parseInt((unitsInBox[productID] > 0) ? unitsInBox[productID] : 0);
            var oSpecialMargin = parseFloat((specialMargin[productID] > 0) ? specialMargin[productID] : 0);
            var unitPrice = 0;
            if (parseInt(oUnitsInBox) > 0) {
                unitPrice = parseFloat((oBoxPrice / oUnitsInBox)).toFixed(2);
            }
            var oTotalPrice = ((iBoxQty * oBoxPrice) > 0) ? (iBoxQty * oBoxPrice).toFixed(2) : 0;
            var oTotalUnits = parseInt(((iBoxQty * oUnitsInBox) > 0) ? (iBoxQty * oUnitsInBox) : 0);
            if (extraUnits != 0) {
                oTotalUnits = parseInt(oTotalUnits) + parseInt(extraUnits);
                var pricePerUnit = parseFloat(oBoxPrice / oUnitsInBox);
                var extraUnitsPrice = parseFloat(pricePerUnit * extraUnits);
                oTotalPrice = parseFloat(oTotalPrice) + parseFloat(extraUnitsPrice);
                oTotalPrice = oTotalPrice.toFixed(2);

            }
            var oTotalUnitsString = ' [' + oTotalUnits + ' units] ';
            var oTotalSpecialMargin = ((oTotalUnits * oSpecialMargin) > 0) ? (oTotalUnits * oSpecialMargin).toFixed(2) : 0;

            // set hidden variables

            $('#PurchaseBoxBuyingPrice').val(oBoxPrice);
            $('#PurchaseUnitsInBox').val(oUnitsInBox);
            $('#PurchaseUnitPrice').val(unitPrice);
            $('#PurchaseSpecialMargin').val(oSpecialMargin);
            $('#PurchaseTotalUnits').val(oTotalUnits);
            $('#PurchaseTotalAmount').val(oTotalPrice);
            $('#PurchaseTotalSpecialMargin').val(oTotalSpecialMargin);


            if (oTotalPrice <= 0) {
                $('#SubmitForm').attr('title', 'Total amount should be greater than 0');
            } else {
                $('#SubmitForm').attr('title', '');
            }

            // set output
            $('#oTotalBoxQty').text(iBoxQtyText);
            $('#oOneBoxQty').text(oUnitsInBox);
            $('#oBoxPrice').text(oBoxPrice);
            $('#oUnitPrice').text(unitPrice);
            $('#oTotalUnits').text(oTotalUnitsString);
            $('#oTotalPrice').text(oTotalPrice);
            $('#oSpecialMargin').text(oSpecialMargin);
            $('#oTotalSpecialMargin').text(oTotalSpecialMargin);
            $('#oProductName').text(productName);
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


    <?php
    echo $this->Form->create();

    echo $this->Form->input('box_buying_price', ['type' => 'hidden']);
    echo $this->Form->input('units_in_box', ['type' => 'hidden']);
    echo $this->Form->input('unit_price', ['type' => 'hidden']);
    echo $this->Form->input('total_units', ['type' => 'hidden']);
    echo $this->Form->input('total_amount', ['type' => 'hidden']);
    echo $this->Form->input('special_margin', ['type' => 'hidden']);
    echo $this->Form->input('total_special_margin', ['type' => 'hidden']);

    ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="mt-3">
                <label for="PurchaseProductId">Select Product</label>
                <?=
                $this->Form->input(
                    'product_id',
                    [
                        'empty' => false,
                        'label' => false,
                        'required' => true,
                        'type' => 'select',
                        'options' => $productsList,
                        'onchange' => 'setExtraUnits(); setTotalPrice()',
                        'autofocus' => true,
                        'class' => 'autoSuggest form-control form-control-sm',
                    ]
                )
                ?>
            </div>

            <div class="mt-3">
                <label for="PurchaseBoxQty">No. of Boxes</label>
                <?=
                $this->Form->input(
                    'box_qty',
                    [
                        'type' => 'number',
                        'value' => 1,
                        'min' => '0',
                        'max' => '99999',
                        'label' => false,
                        'required' => true,
                        'oninput' => 'setTotalPrice()',
                        'title' => 'Values should be between 1 to 99999',
                        'class' => 'form-control form-control-sm',
                    ]
                )
                ?>
            </div>

            <div class="mt-3">
                <label for="PurchaseExtraUnits">Extra Units</label>

                <?php
                $extraUnitArray = [];

                for ($i = 1; $i <= $boxQuantity; $i++) {
                    $extraUnitArray[$i - 1] = $i - 1;
                }

                echo $this->Form->input(
                    'extra_units',
                    [
                        'empty' => false,
                        'label' => false,
                        'type' => 'select',
                        'options' => $extraUnitArray,
                        'onchange' => 'setTotalPrice()',
                        'autofocus' => true,
                        'class' => 'form-control form-control-sm',
                    ]
                )
                ?>
            </div>

            <div class="mt-3">
                <table class="table table-sm small table-striped table-bordered border-info mt-3">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Box Price</th>
                        <th>Unit Price</th>
                        <th>No. of Boxes</th>

                        <th>Total Amount</th>
                    </tr>
                    </thead>
                    <tr>
                        <td><span id="oProductName"></span></td>
                        <td><span id="oBoxPrice">0</span></td>
                        <td><span id="oUnitPrice">0</span></td>
                        <td><span id="oTotalBoxQty">0</span> &nbsp;-&nbsp; <span id="oTotalUnits"></span></td>

                        <td><span id="oTotalPrice">0</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-6">

        </div>
    </div>

    <div class="">
        <button class="btn btn-primary btn-sm" onclick='return submitButtonMsg()'><span class="fa fa-plus-circle"></span> Add Product</button>
    </div>

    <?php
    echo $this->Form->end();
    ?>

    <script type="text/javascript">
        setExtraUnits();
        setTotalPrice();
    </script>
    <br>
    <hr>
    <table class="table table-sm small mt-3 d-none">
        <tbody>
        <tr class="bold">
            <td style="width:210px;">Invoice No:</td>
            <td><?php echo $invoiceInfo['Invoice']['name']; ?></td>
            <td style="width:150px;">Invoice Date:</td>
            <td><?php echo date('d-m-Y', strtotime($invoiceInfo['Invoice']['invoice_date'])); ?></td>
            <td style="width:130px;">DD Amount:</td>
            <td><?php echo $invoiceInfo['Invoice']['dd_amount']; ?></td>
        </tr>
        <tr>
            <td>Invoice Value:</td>
            <td><?php echo $invoiceInfo['Invoice']['invoice_value']; ?></td>
            <td>MRP Rounding Up:</td>
            <td><?php echo $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
            <td>Net Invoice Value:</td>
            <td><?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
        </tr>
        <tr>
            <td>Retail Shop Excise Turnover Tax:</td>
            <td><?php echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax']; ?></td>
            <td>Special Excise Cess:</td>
            <td><?php echo $invoiceInfo['Invoice']['special_excise_cess']; ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>TCS Value:</td>
            <td><?php echo $invoiceInfo['Invoice']['tcs_value']; ?></td>
            <td>Prev Credit:</td>
            <td><?php echo $invoiceInfo['Invoice']['prev_credit']; ?></td>
            <td>Credit Balance:</td>
            <td><?php echo $invoiceInfo['Invoice']['credit_balance']; ?></td>
        </tr>
        </tbody>
    </table>

    <div class="mt-3">
        <h5>Invoice Products</h5>
    </div>
    <?php
    //debug($invoiceProducts);
    if ($invoiceProducts) {
        ?>
        <table class="table table-sm table-striped small">
            <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Category Name</th>
                <?php echo $this->Session->read('Store.show_brands_in_products') ? "<th>Brand</th>" : ""; ?>
                <th>Product Name</th>
                <th class="text-center" style="width: 130px;">No. of Boxes</th>

                <th class="text-center" style="width: 130px;">Single Box Price</th>
                <th class="text-right" style="width: 130px;">Total Amount</th>
                <th style="width: 50px;"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            $totalBoxes = 0;
            $totalAmount = 0;
            $totalSpecialMargin = 0;
            $totalNoOfUnits = 0;
            $tax = $this->Session->read('Invoice.tax');
            foreach ($invoiceProducts as $row) {
                $i++;
                $totalBoxes += $row['Purchase']['box_qty'];
                $totalAmount += $row['Purchase']['total_amount'];
                $totalSpecialMargin += $row['Purchase']['total_special_margin'];
                $totalUnits = $row['Purchase']['total_units'];
                $noOfBoxes = floor($row['Purchase']['total_units'] / $row['Purchase']['units_in_box']);
                $unitInBox = $row['Purchase']['units_in_box'];
                $noOfUnits = ($totalUnits) - ($noOfBoxes * $unitInBox);
                $totalNoOfUnits += $noOfUnits;
                //debug($row['Product']);
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['Purchase']['category_name']; ?></td>
                    <?php
                    if ($this->Session->read('Store.show_brands_in_products')) {
                        ?>
                        <td><?php echo isset($row['Product']['Brand']['name']) ? $row['Product']['Brand']['name'] : ''; ?></td>
                        <?php
                    }
                    ?>
                    <td><?php echo $row['Purchase']['product_name']; ?></td>
                    <td class="text-center"><?php echo $row['Purchase']['box_qty'];
                        if ($noOfUnits) {
                            echo "&nbsp;($noOfUnits)";
                        }
                        ?></td>

                    <td class="text-center"><?php echo $row['Purchase']['box_buying_price']; ?></td>
                    <td class="text-right"><?php echo $row['Purchase']['total_amount']; ?></td>
                    <td class="text-center">
                        <form
                                method="post"
                                name="invoice_remove_product_<?php echo $row['Purchase']['id']; ?>" id="invoice_remove_product_<?php echo $row['Purchase']['id']; ?>"
                                action="<?php echo $this->Html->url("/purchases/removeProduct/" . $row['Purchase']['id']); ?>"
                        >
                            <a
                                    href="#"
                                    name="Remove"
                                    onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Purchase']['product_name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Purchase']['id']; ?>').submit(); } event.returnValue = false; return false;"
                                    class="text-danger ml-2"
                            >
                                <span class="fa fa-trash-alt" aria-hidden="true"></span>
                            </a>
                        </form>
                        <?php
                        //echo $this->Form->postLink('Remove', array('controller'=>'purchases', 'action'=>'removeProduct', $row['Purchase']['id']), array('title'=>'Remove product from invoice - '.$row['Purchase']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Purchase']['product_name'].'" from the list?');
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tfoot style="font-weight:bold;">
            <tr>
                <td colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 4 : 3; ?>'></td>
                <td style="text-align:center;"><?php echo $totalBoxes;
                    if ($totalNoOfUnits) {
                        echo "&nbsp;($totalNoOfUnits)";
                    }
                    ?> Boxes
                </td>
                <td style="text-align:right;" colspan='2'><?php echo number_format($totalAmount, '2', '.', ''); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right; color:red;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 8 : 7; ?>'>&nbsp;</td>
            </tr>

            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>
                    Invoice Value: <br>

                    MRP Rounding Off: <br>
                    Net Invoice Value: <br>
                </td>
                <td style="text-align:right;">
                    <?php echo number_format($totalAmount, '2', '.', ''); ?> <br>
                    <?php echo $invoiceInfo['Invoice']['mrp_rounding_off']; ?> <br>
                    <?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['special_margin'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?> <br>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 8 : 7; ?>'>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>e-challan / DD Amount:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['dd_amount']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>Previous Credit:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['prev_credit']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>Sub Total:</td>
                <td style="text-align:right;"><?php echo number_format($invoiceInfo['Invoice']['dd_amount'] + $invoiceInfo['Invoice']['prev_credit'], '2', '.', ''); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>(-) Less this Invoice Value:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['special_margin'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
                <td>&nbsp;</td>
            </tr>


            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>Retail Shop Excise Turnover Tax:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>Special Excise Cess:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['special_excise_cess']; ?></td>
                <td>&nbsp;</td>
            </tr>


            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>TCS:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['tcs_value']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>Retailer Credit Balance:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['credit_balance']; ?></td>
                <td>&nbsp;</td>
            </tr>
            </tfoot>
            </tbody>
        </table>
        <br><br>

    <?php } else { ?>
        <p>No products found in Invoice "<?php echo $this->Session->read('Invoice.name'); ?>".</p>
    <?php } ?>

    <?php
} else {
    echo 'No products found. You need to add products to continue.';
}
?>