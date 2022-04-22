<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/product_categories">Categories</a></li>
        <li class="breadcrumb-item">
            <a href="/product_categories/index/<?= $productCategoryInfo['ProductCategory']['id'] ?>">
                <?= $productCategoryInfo['ProductCategory']['name'] ?>
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->data['Product']['name'] ?></li>
    </ol>
</nav>


<h1>Edit Product</h1>

<div class="mt-3 col-md-8 col-lg-6">
    <?php
    echo $this->Form->create();
    ?>
    <div class="mt-3">
        <label for="ProductName">Product Name</label>
        <?php
        echo $this->Form->input(
            'name',
            [
                'placeholder' => 'Enter Product Name',
                'label' => false,
                'required' => true,
                'pattern' => '[a-zA-Z0-9\s\-\/\&]{1,100}',
                'class' => 'form-control form-control-sm',
                'title' => 'eg: Royal Challenge - Q, Kingfisher - P, etc.',
            ]
        );
        ?>
    </div>

    <div class="mt-3">
        <label for="ProductBrandId">
            Brand
            <a href="/brands/add" class="ml-1 small fa fa-plus-circle text-decoration-none" title="Add New Brand"></a>
        </label>
        <?=
        $this->Form->input(
            'brand_id',
            [
                'label' => false,
                'type' => 'select',
                'options' => $brands,
                'escape' => false,
                'class' => 'form-control form-control-sm',
                'empty' => ' -- Select Brand -- ',
            ]
        )
        ?>
    </div>

    <div class="mt-3">
        <label for="ProductBoxQty">Items per Box</label>
        <?=
        $this->Form->input(
            'box_qty',
            [
                'placeholder' => 'Number of items in each box',
                'label' => false,
                'list' => 'boxqty',
                'pattern' => '[1-9][0-9]{0,3}',
                'title' => 'Values 1 to 999 are allowed',
                'class' => 'form-control form-control-sm',
            ]
        )
        ?>
        <datalist id='boxqty'>
            <option value='12'>
            <option value='24'>
            <option value='48'>
            <option value='96'>
        </datalist>
    </div>

    <div class="mt-3">
        <label for="ProductBoxBuyingPrice">Box Purchase Price</label>
        <?=
        $this->Form->input(
            'box_buying_price',
            [
                'placeholder' => 'Purchase price of each box',
                'label' => false,
                'required' => false,
                'pattern' => '[0-9]+(\.[0-9][0-9]?)?',
                'title' => 'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc',
                'class' => 'form-control form-control-sm',
            ]
        )
        ?>
    </div>

    <div class="mt-3">
        <label for="ProductName">Unit Sale Price (MRP)</label>
        <?=
        $this->Form->input(
            'unit_selling_price',
            [
                'placeholder' => 'Unit selling price',
                'label' => false,
                'required' => false,
                'pattern' => '[0-9]+(\.[0-9][0-9]?)?',
                'title' => 'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc',
                'class' => 'form-control form-control-sm',
            ]
        )
        ?>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-sm btn-primary">Update</button>
    </div>

    <?= $this->Form->end() ?>
</div>