<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>

<article>
    <header><h1>Invoices</h1></header>
    <?php
    if (!empty($invoices)) {
        ?>
        <table class='table table-sm table-striped small mt-3'>
            <thead>
            <tr>
                <th>#</th>
                <th>Invoice No.</th>
                <th>Invoice Value</th>
                <th>MRP Rounding Up</th>
                <th>Net Invoice Value</th>
                <th>DD Amount</th>
                <th>Invoice Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $k = 0;
            foreach ($invoices as $row) {
                $k++;
                $invoiceTax = $row['Invoice']['tax'];
                $invoice_amt = 0;
                if (isset($invoiceAmount[$row['Invoice']['id']])) {
                    $invoice_amt = number_format(($invoiceAmount[$row['Invoice']['id']] + $invoiceTax), '2', '.', '');
                }
                ?>
                <tr>
                    <td><?php echo $k; ?></td>
                    <td style="width:150px;">
                        <a href="/invoices/details/<?= $row['Invoice']['id'] ?>" class="">
                            <?= $row['Invoice']['name'] ?>
                        </a>

                    </td>
                    <td><?php echo $row['Invoice']['invoice_value']; ?></td>
                    <td><?php echo number_format($row['Invoice']['mrp_rounding_off'], '2', '.', ''); ?></td>
                    <td><?php echo $row['Invoice']['invoice_value'] + $row['Invoice']['mrp_rounding_off']; ?></td>
                    <td><?php echo $row['Invoice']['dd_amount']; ?></td>

                    <td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
                    <td style="width:80px;">

                        <form method="post" style="" name="invoice_remove_product_<?php echo $row['Invoice']['id']; ?>" id="invoice_remove_product_<?php echo $row['Invoice']['id']; ?>" action="<?php echo $this->Html->url("/invoices/Delete/" . $row['Invoice']['id']); ?>">

                            <a href="/invoices/selectInvoice/<?= $row['Invoice']['id'] ?>" class="text-primary" title="Add/Remove products in this invoice"><span class="fa fa-plus-circle"></span></a>

                            <a href="/invoices/edit/<?= $row['Invoice']['id'] ?>" class="text-warning ml-2" title="Edit invoice details"><span class="fa fa-edit"></span></a>

                            <a
                                href="javascript:return false;"
                                onclick="if (confirm('Deleting this invoice will remove all the products associated with it.\n\nAre you sure you want to delete this invoice <?php echo $row['Invoice']['name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Invoice']['id']; ?>').submit(); } event.returnValue = false; return false;"
                                class="text-danger ml-2"
                                title="Delete this invoice"
                            >
                                <span class="fa fa-trash-alt" aria-hidden="true"></span>
                            </a>

                            <?php //echo $this->Form->postLink('Delete', array('controller'=>'invoices', 'action'=>'Delete', $row['Invoice']['id']), array('title'=>'Remove Invoice - '.$row['Invoice']['name']), 'Deleting this invoice will remove all the products associated with it.\nAre you sure you want to delete this Invoice - "'.$row['Invoice']['name'].'" ?');	?>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    } else {
        ?>
        <p>No Invoices Found</p>
        <?php
    }
    ?>

</article>