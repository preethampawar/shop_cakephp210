<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/product_categories">Categories</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $pCatInfo['ProductCategory']['name'] ?></li>
    </ol>
</nav>

<h1>Edit Category</h1>


<div class="mt-3 col-md-8 col-lg-6">
    <?= $this->Form->create('ProductCategory') ?>
    <label for="ProductCategoryName">Category Name</label>
    <?=
    $this->Form->input(
        'name',
        [
            'placeholder' => 'Enter Category Name',
            'label' => false,
            'value' => html_entity_decode($this->data['ProductCategory']['name']),
            'required' => true,
            'class' => 'form-control form-control-sm',
        ]
    );
    ?>
    <button type="submit" class="mt-3 btn btn-sm btn-primary">Update</button>
    <a href="/product_categories" class="mt-3 ml-2 btn btn-sm btn-warning">Cancel</a>

    <?= $this->Form->end() ?>
</div>
