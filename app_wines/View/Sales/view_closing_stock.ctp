<?php $this->start('stock_reports_menu'); ?>
<?php echo $this->element('stock_menu'); ?>
<?php echo $this->element('stock_report_menu'); ?>
<?php $this->end(); ?>

    <h1>Closing Stock</h1>
    <p><?php echo $this->Html->link('Import Closing Stock', ['controller' => 'sales', 'action' => 'uploadCsv']); ?></p>
<?php
if ($sales) {
    ?>
    <div class="small mt-3">
    <?php
    // prints X of Y, where X is current page and Y is number of pages
    echo 'Page ' . $this->Paginator->counter();
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';

    // Shows the next and previous links
    echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    // Shows the page numbers
    echo $this->Paginator->numbers();

    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
    ?>
    </div>

    <table class='table table-striped table-sm small mt-3'>
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
        foreach ($sales as $row) {
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
                        <a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name']; ?> from the list?')) { $('#sales_<?php echo $row['Sale']['id']; ?>').submit(); } event.returnValue = false; return false;" class="ml-2 text-danger">
                            <span class="fa fa-trash-alt" aria-hidden="true"></span>
                        </a>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
    if (count($sales) > 10) {
        // prints X of Y, where X is current page and Y is number of pages
        echo 'Page ' . $this->Paginator->counter();
        echo '&nbsp;&nbsp;&nbsp;&nbsp;';

        // Shows the next and previous links
        echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        // Shows the page numbers
        echo $this->Paginator->numbers();

        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
    }
    ?>
<?php } else { ?>
    <p>No products found.</p>
<?php } ?>