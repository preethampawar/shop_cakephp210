<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/invoices">Invoices</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->data['Invoice']['name'] ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between mt-3">
    <div class=""><h1>Edit Invoice</h1></div>
    <div class="">
        <a href="/invoices/details/<?= $this->data['Invoice']['id'] ?>" class="btn btn-sm btn-primary"><span class="fa fa-info-circle"></span> Invoice Details</a>
        <a href="/invoices/selectInvoice/<?= $this->data['Invoice']['id'] ?>" class="btn btn-sm btn-primary ml-2"><span class="fa fa-plus-circle"></span> Add/Remove Products</a>
    </div>
</div>

<?= $this->Form->create() ?>
<div class="mt-3">
    <label for="InvoiceInvoiceDate">Invoice Date</label>
    <input
        id="InvoiceInvoiceDate"
        name="data[Invoice][invoice_date]"
        type="date"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['invoice_date'] ?>"
        required
    >
</div>

<div class="mt-3">
    <label for="InvoiceName">Invoice name</label>
    <input
        id="InvoiceName"
        name="data[Invoice][name]"
        type="text"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['name'] ?>"
        required
    >
</div>

<div class="mt-3">
    <label for="InvoiceDdAmount">DD Amount</label>
    <input
        id="InvoiceDdAmount"
        name="data[Invoice][dd_amount]"
        type="number"
        step="0.01"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['dd_amount'] ?>"
    >
</div>

<div class="mt-3">
    <label for="InvoiceRetailShopExciseTurnoverTax">Retail Shop Excise Turnover Tax</label>
    <input
        id="InvoiceRetailShopExciseTurnoverTax"
        name="data[Invoice][retail_shop_excise_turnover_tax]"
        type="number"
        step="0.01"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['retail_shop_excise_turnover_tax'] ?>"
    >
</div>

<div class="mt-3">
    <label for="InvoiceSpecialExciseCess">Special Excise Cess</label>
    <input
        id="InvoiceSpecialExciseCess"
        name="data[Invoice][special_excise_cess]"
        type="number"
        step="0.01"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['special_excise_cess'] ?>"
    >
</div>

<div class="mt-3">
    <label for="InvoiceTcsValue">Tcs Value</label>
    <input
        id="InvoiceTcsValue"
        name="data[Invoice][tcs_value]"
        type="number"
        step="0.01"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['tcs_value'] ?>"
    >
</div>

<div class="mt-3">
    <label for="InvoiceMrpRoundingOff">MRP Rounding Off</label>
    <input
        id="InvoiceMrpRoundingOff"
        name="data[Invoice][mrp_rounding_off]"
        type="number"
        step="0.01"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['mrp_rounding_off'] ?>"
    >
</div>

<div class="mt-3">
    <label for="InvoicePrevCredit">Previous Credit</label>
    <input
        id="InvoicePrevCredit"
        name="data[Invoice][prev_credit]"
        type="number"
        step="0.01"
        class="form-control form-control-sm"
        value="<?= $this->data['Invoice']['prev_credit'] ?>"
    >
</div>

<div class="mt-3">
    <label for="InvoiceSupplierId">Supplier</label>
    <?=
    $this->Form->input(
        'Invoice.supplier_id',
        [
            'type' => 'select',
            'label' => false,
            'empty' => '-',
            'title' => 'Select Supplier',
            'options' => $suppliersList,
            'class'=>'form-control form-control-sm'
        ]
    )
    ?>
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-sm btn-primary">Update</button>
    <a href="/invoices" class="btn btn-sm btn-warning ml-2">Cancel</a>
</div>
<?= $this->Form->end() ?>
<br><br>
