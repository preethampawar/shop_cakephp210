<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Wines: <?php echo $title_for_layout; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="icon" type="image/gif" href="/img/stats.gif" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">-->

    <!-- CSS -->
    <!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <!-- jQuery JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <!--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>-->
    <!--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>-->
    <!--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.bundle.min.js" integrity="sha384-BOsAfwzjNJHrJ8cZidOg56tcQWfp6y72vEJ8xQ9w6Quywb24iOsW913URv1IS4GD" crossorigin="anonymous"></script>-->


    <!--    <link rel="stylesheet" href="/css/site.css" crossorigin="anonymous">-->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

    <style type="text/css">
        .select2-results ul {
            color: #333;
        }

        .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"], .radio input[type="radio"], .radio-inline input[type="radio"] {
            margin-left: 0px;
        }

        .btn, .menu-item  a {
            text-decoration: none;
        }

        .menu-item  li {
            padding: 2px 0;
            border-bottom: 1px dotted #ccc;
        }
    </style>
</head>

<body>
<!--[if lt IE 10]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->


<?php
$showHeader = true;
if (isset($hideHeader) and ($hideHeader)) {
    $showHeader = false;
}
?>

<?php
if ($showHeader) {
    ?>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand"><?php echo ($this->Session->check('Store')) ? strtoupper($this->Session->read('Store.name')) : 'SimpleAccounting'; ?></span>
            <div class="" id="">
                <ul class="navbar-nav">
                    <?php if ($this->Session->check('Auth.User')) { ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/stores">My Stores</a>
                        </li>

                        <?php if ($this->Session->read('manager') == '1'): ?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/users">Users</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($this->Session->read('storeAccess.isAdmin')): ?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/stores/storeAccess">Users</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/SimpleAccountingApp-v1.0.0.apk">Download Mobile App</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/users/logout">Logout</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/SimpleAccountingApp-v1.0.0.apk">Download Mobile App</a>
                        </li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </nav>

    <?php
    if ($this->Session->check('Auth.User') && $this->Session->check('Store')) {
        ?>
        <nav class="navbar navbar-expand-sm navbar-white bg-light border-bottom">
            <div class="container-fluid">
                <div>
                    <ul class="navbar-nav small">

                        <li class="nav-item">
                            <a class="nav-link pl-0 text-decoration-underline" aria-current="page" href="/stores/home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/product_categories">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/brands">Brands</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/invoices">Invoices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/sales/viewClosingStock">Closing Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/breakages/viewBreakageStock">Breakage Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/purchases">Purchases</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/sales">Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/cashbook">Cashbook</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/dealers">Dealers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-decoration-underline" aria-current="page" href="/reports/home">Reports</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php
    }
}
?>

<div id="container-fluid">
    <?php
    $showSideBar = true;
    $class = "contentBar";
    if (isset($hideSideBar) and ($hideSideBar == true)) {
        $showSideBar = false;
        $class = "properMargin";
    }
    ?>
    <div class="d-flex">
        <?php
        if ($showSideBar) {
            ?>
            <div class="bg-light small p-2" style="width:200px;">

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

            </div>
            <?php
        }
        ?>
        <div class="px-3 py-1 flex-fill">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.min.js" integrity="sha384-5h4UG+6GOuV9qXh6HqOLwZMY4mnLPraeTrjT5v07o347pj6IkfuoASuGBhfDsp3d" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript" src="<?php echo $this->Html->url('/html-table-search/html-table-search.js'); ?>"></script>
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