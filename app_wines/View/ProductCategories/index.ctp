<h1>Manage Products</h1>

<div class="mb-3">
    <?php echo $this->Html->link('Import Products', ['controller' => 'product_categories', 'action' => 'uploadCsv']); ?> &nbsp;|&nbsp;<?php echo $this->Html->link('Download Products', ['controller' => 'product_categories', 'action' => 'downloadCsv']); ?>
</div>

<hr>
<div class="row">
    <div class="col-xs-5 col-sm-5 col-lg-3">
        <h5>Categories</h5>

        <?php echo $this->Form->create('ProductCategory', ['url' => '/product_categories/add/']); ?>
        <div class="input-group mt-3">
            <?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => false, 'required' => true, 'class' => 'form-control form-control-sm']); ?>
            <span class="input-group-btn">
			    <button type="submit" class="btn btn-primary btn-sm">+ Add</button>
            </span>
        </div>
        <?php echo $this->Form->end(); ?>

        <?php if ($categories) { ?>
            <table class='table table-sm table-striped small mt-3'>
                <thead>
                <tr>
                    <th style="width:20px">#</th>
                    <th>Category</th>
                    <th style="text-align:center; width:80px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($categories as $row) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                            <?php echo $this->Html->link($row['ProductCategory']['name'], ['controller' => 'product_categories', 'action' => 'index', $row['ProductCategory']['id']], ['title' => 'Show all products in ' . $row['ProductCategory']['name'] . ' category']); ?>

                        </td>
                        <td style="text-align:center;">

                            <a href="/products/add/<?= $row['ProductCategory']['id'] ?>"><span class="fa fa-plus-circle" title="Add Product in <?= $row['ProductCategory']['name'] ?>"></span></a>
                            <?php echo $this->Html->link('<span class="ml-2 fa fa-edit" aria-hidden="true"></span>', ['controller' => 'product_categories', 'action' => 'edit', $row['ProductCategory']['id']], ['title' => 'Edit Category - ' . $row['ProductCategory']['name'], 'escape' => false, 'class' => 'text-warning']); ?>

                            <?php echo $this->Html->link('<span class="ml-2 fa fa-trash-alt" aria-hidden="true"></span>', ['controller' => 'product_categories', 'action' => 'delete', $row['ProductCategory']['id']], ['title' => 'Delete Category - ' . $row['ProductCategory']['name'], 'escape' => false, 'class' => 'text-danger'], ' Category - ' . $row['ProductCategory']['name'] . "\n Deleting this category will remove all the products associated with it.\n All Sales & Purchase records will be deleted.\n\n Are you sure you want to delete this category?"); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
    <div class="col-xs-7 col-sm-7 col-lg-9">

        <div class="d-flex justify-content-between">
            <div>
                <h5><?php echo $categoryID ? 'Products in ' . $category['ProductCategory']['name'] : 'All Products'; ?></h5>
            </div>
            <div>
                <?php
                echo $categoryID ? $this->Html->link('+ Add Product', ['controller' => 'products', 'action' => 'add', $category['ProductCategory']['id']], ['title' => $category['ProductCategory']['name'] . ' - Add Product', 'escape' => false, 'class' => 'btn btn-primary btn-sm']) : '';
                ?>
                <?php echo $categoryID ? '<span class="text-right ml-2"><a href="/product_categories" class="btn btn-sm btn-warning">Show All Products</a></span>' : '' ?>
            </div>
        </div>


        <?php if ($products) { ?>
            <table class='table table-striped table-sm small mt-3'>
                <thead>
                <tr>
                    <th style="width:20px">#</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Product</th>
                    <th>Units/Box</th>
                    <th>Box Price</th>
                    <th>Unit Price</th>

                    <th>Created on</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($products as $row) {
                    if (!empty($row['Product'])) {
                        foreach ($row['Product'] as $product) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?= $row['ProductCategory']['name'] ?></td>
                                <td><?= ($product['brand_id']) ? $brands[$product['brand_id']] : null ?></td>
                                <td><?= $this->Html->link($product['name'], ['controller' => 'products', 'action' => 'edit', $row['ProductCategory']['id'], $product['id']], ['title' => 'Edit Product - ' . $product['name']]) ?></td>
                                <td><?= $product['box_qty'] ?></td>
                                <td><?= $product['box_buying_price'] ?></td>
                                <td><?= $product['unit_selling_price'] ?></td>

                                <td><?php echo date('d-m-Y', strtotime($product['created'])); ?></td>
                                <td style="text-align: center">
                                    <?= $this->Html->link('<span class="fa fa-edit" aria-hidden="true"></span>', ['controller' => 'products', 'action' => 'edit', $row['ProductCategory']['id'], $product['id']], ['title' => 'Edit Product - ' . $product['name'], 'escape' => false, 'class' => 'text-warning']) ?>
                                    &nbsp;
                                    <?= $this->Html->link('<span class="fa fa-trash-alt" aria-hidden="true"></span>', ['controller' => 'products', 'action' => 'delete', $product['id']], ['title' => 'Delete Product - ' . $product['name'], 'escape' => false, 'class' => 'text-danger'], 'Product - ' . $product['name'] . "\n\nDeleting this product will remove all Sales & Purchase records associated with it.\n\nAre you sure you want to delete this product?") ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </div>
</div>


<?php
if (!$categories) {
    ?>

    <p>No category found.</p>
    <p>First create a "Category" and add products in it.</p>

    <?php
}
?>