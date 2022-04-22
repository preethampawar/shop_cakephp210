<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Wines: <?php echo $title_for_layout; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/gif" href="/img/stats.gif" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- jQuery JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="<?php echo $this->Html->url('/html-table-search/html-table-search.js'); ?>"></script>

    <link rel="stylesheet" href="/css/site.css" crossorigin="anonymous">

    <style type="text/css">
        .select2-results ul {
            color: #333;
        }

        .row {
            margin-right: 0px;
            margin-left: 0px;
        }

        .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"], .radio input[type="radio"], .radio-inline input[type="radio"] {
            margin-left: 0px;
        }
    </style>
</head>

<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->


<?php
$showHeader = true;
if (isset($hideHeader) and ($hideHeader)) {
    $showHeader = false;
}
?>

<?php if ($showHeader) { ?>
    <header id="header">
        <h1 style="float:left;">
            <?php echo ($this->Session->check('Store')) ? strtoupper($this->Session->read('Store.name')) : 'SimpleAccounting'; ?>
        </h1>
        <?php if ($this->Session->check('Auth.User')) { ?>
            <div style="float:right;">
                <?php
                echo $this->Html->link('My Stores', ['controller' => 'stores', 'action' => 'index']);
                echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                if ($this->Session->read('manager') == '1') {
                    echo $this->Html->link('Users', ['controller' => 'users', 'action' => 'index']);
                    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                }

                if ($this->Session->read('storeAccess.isAdmin')) {
                    echo $this->Html->link('User Access', ['controller' => 'stores', 'action' => 'storeAccess']);
                    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                }

                echo $this->Html->link('Logout', ['controller' => 'users', 'action' => 'logout']);
                ?>
            </div>
        <?php } ?>
        <div style="clear:both;"></div>
        <?php if ($this->Session->check('Auth.User')) { ?>
            <nav style="font-size:12px;">

                <?php
                if ($this->Session->check('Store')) {
                    ?>
                    <?php echo $this->Html->link('Home', ['controller' => 'stores', 'action' => 'home']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Products', ['controller' => 'product_categories', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Brands', ['controller' => 'brands', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Invoices', ['controller' => 'invoices', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Closing Stock', ['controller' => 'sales', 'action' => 'viewClosingStock']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Breakage Stock', ['controller' => 'breakages', 'action' => 'viewBreakageStock']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Purchases', ['controller' => 'purchases', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Sales', ['controller' => 'sales', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Cashbook', ['controller' => 'cashbook', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Dealers', ['controller' => 'dealers', 'action' => 'index']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php echo $this->Html->link('Reports', ['controller' => 'reports', 'action' => 'home']); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="/SimpleAccountingApp-v1.0.0.apk">Download Mobile App</a>

                    <?php // echo $this->Html->link('Counter Balance Sheets', array('controller'=>'CounterBalanceSheets', 'action'=>'index'));?>
                    <?php // echo $this->Html->link('Employees', array('controller'=>'employees', 'action'=>'index'));?>
                    <?php // echo $this->Html->link('Bank Book', array('controller'=>'banks', 'action'=>'index'));?>

                    <?php
                }
                ?>
            </nav>
        <?php } ?>
    </header>
<?php } ?>

<div id="content">
    <?php
    $showSideBar = true;
    $class = "contentBar";
    if (isset($hideSideBar) and ($hideSideBar == true)) {
        $showSideBar = false;
        $class = "properMargin";
    }
    ?>
    <div class="row">
        <?php
        if ($showSideBar) {
            ?>
            <div class="col-xs-3 col-sm-3 col-lg-2">
                <div id="leftSideBar">
                    <nav>
                        <?php
                        // reports menu
                        if ($this->fetch('reports_menu')):
                            echo $this->fetch('reports_menu');
                        endif;

                        // stock report menu
                        if ($this->fetch('stock_reports_menu')):
                            echo $this->fetch('stock_reports_menu');
                        endif;

                        // sales report menu
                        if ($this->fetch('sales_report_menu')):
                            echo $this->fetch('sales_report_menu');
                        endif;

                        // purchases report menu
                        if ($this->fetch('purchases_report_menu')):
                            echo $this->fetch('purchases_report_menu');
                        endif;

                        // invoices report menu
                        if ($this->fetch('invoices_report_menu')):
                            echo $this->fetch('invoices_report_menu');
                        endif;

                        // employees report menu
                        if ($this->fetch('employees_report_menu')):
                            echo $this->fetch('employees_report_menu');
                        endif;

                        // dealers report menu
                        if ($this->fetch('dealers_report_menu')):
                            echo $this->fetch('dealers_report_menu');
                        endif;

                        // bank report menu
                        if ($this->fetch('bank_menu')):
                            echo $this->fetch('bank_menu');
                        endif;

                        ?>
                    </nav>
                    <br>
                </div>
            </div>
            <?php
        }
        ?>
        <div <?php if ($showSideBar) { ?> class="col-xs-9 col-sm-9 col-lg-10" <?php } ?>>
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
    </div>
    <div class="clear"></div>
</div>

<script>
    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function () {
        if ($('.autoSuggest').length) {
            $('.autoSuggest').select2();
        }

        if ($('table.search-table').length) {
            $('table.search-table').tableSearch({
                searchText: '', searchPlaceHolder: 'Search...', caseSensitive: false
            });
        }

    });
</script>

<?php echo $this->element('sql_dump'); ?>
</body>
</html>