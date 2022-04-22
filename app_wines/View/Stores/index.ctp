<style type="text/css">
    #selectStoreDiv {
        font-size: 120%;
    }
</style>
<article>
    <h1>My Stores</h1>

    <?php
    if ($this->Session->read('manager') == '1') {
        ?>
        <p class="mt-3">

            <a href="/stores/add">+ Add New Store</a>
            <a href="/users/add" class="ml-2">+ Add New User</a>

        </p>
        <?php
    }
    ?>

    <?php
    if (!empty($stores)) {
        ?>
        <div id="selectStoreDiv mt-3">
            <table class='table table-striped table-sm'>
                <thead>
                <tr>
                    <th style="width:80px;">Sl.No.</th>
                    <th>
                        Store
                    </th>
                    <?php
                    if ($this->Session->read('manager') == '1') {
                        ?>
                        <th>User


                        </th>
                        <?php
                    }
                    ?>
                    <th>Status</th>
                    <th>Expiry Date</th>
                    <th style="width:200px; text-align:center;">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $k = 0;
                foreach ($stores as $row) {
                    $k++;
                    ?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <td>
                            <?php
                            echo $this->Html->link(strtoupper($row['Store']['name']), ['controller' => 'stores', 'action' => 'selectStore', $row['Store']['id']], ['title' => 'Select this store']);
                            ?>
                        </td>
                        <?php
                        if ($this->Session->read('manager') == '1') {
                            ?>
                            <td>
                                <a href="/users/edit/<?php echo $row['Store']['user_id']; ?>">
                                    <?php echo $userInfo[$row['Store']['user_id']]; ?>
                                </a>
                            </td>
                            <?php
                        }
                        ?>
                        <td>
                            <?php
                            $status = 'active';
                            if ($row['Store']['active']) {
                                if ($row['Store']['name'] != 'test') {
                                    // check for expiry
                                    $storeExpiredOn = $row['Store']['expiry_date'];
                                    $unixTimeStoreExpiry = strtotime($storeExpiredOn);
                                    $unixTimeNow = strtotime("now");
                                    if ($unixTimeNow > $unixTimeStoreExpiry) {
                                        $status = 'expired';
                                    }
                                }
                            } else {
                                $status = 'inactive';
                            }

                            if ($status == 'active') {
                                echo '<span class="text-success"><b>Active</b></span>';
                            }
                            if ($status == 'inactive') {
                                echo '<span class="text-info"><b>Inactive</b></span>';
                            }
                            if ($status == 'expired') {
                                echo '<span class="text-danger"><b>Expired</b></span>';
                            }
                            ?>
                        </td>
                        <td><?php echo $row['Store']['expiry_date'] ? date('d-m-Y', strtotime($row['Store']['expiry_date'])) : '-'; ?></td>


                        <td style="text-align:center;" class="small">
                            <?php
                            if (!$row['Store']['show_brands_in_products']) {
                                ?>
                                <form method="post" style="" name="showbrandsinproducts_<?php echo $row['Store']['id']; ?>" id="showbrandsinproducts_<?php echo $row['Store']['id']; ?>" action="<?php echo $this->Html->url("/stores/showbrandsinproducts/" . $row['Store']['id']); ?>">
                                    <a href="javascript:return false;" onclick="if (confirm('Enabling this feature will show brands along with products. \n\nAre you sure you want to enable it?')) { $('#showbrandsinproducts_<?php echo $row['Store']['id']; ?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-outline-secondary mb-2">
                                        Show Brands In Products
                                    </a>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form method="post" style="" name="hidebrandsinproducts_<?php echo $row['Store']['id']; ?>" id="hidebrandsinproducts_<?php echo $row['Store']['id']; ?>" action="<?php echo $this->Html->url("/stores/hidebrandsinproducts/" . $row['Store']['id']); ?>">
                                    <a href="javascript:return false;" onclick="if (confirm('Enabling this feature will hide brands in products. \n\nAre you sure you want to disable it?')) { $('#hidebrandsinproducts_<?php echo $row['Store']['id']; ?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-outline-primary mb-2">
                                        Hide Brands In Products
                                    </a>
                                </form>
                                <?php
                            }
                            ?>

                            <?php
                            if (!$row['Store']['show_brands_in_reports']) {
                                ?>
                                <form method="post" style="" name="showbrandsinreports_<?php echo $row['Store']['id']; ?>" id="showbrandsinreports_<?php echo $row['Store']['id']; ?>" action="<?php echo $this->Html->url("/stores/showbrandsinreports/" . $row['Store']['id']); ?>">
                                    <a href="javascript:return false;" onclick="if (confirm('Enabling this feature will show brands along with products. \n\nAre you sure you want to enable it?')) { $('#showbrandsinreports_<?php echo $row['Store']['id']; ?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-outline-secondary mb-2">
                                        Show Brands In Reports
                                    </a>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form method="post" style="" name="hidebrandsinreports_<?php echo $row['Store']['id']; ?>" id="hidebrandsinreports_<?php echo $row['Store']['id']; ?>" action="<?php echo $this->Html->url("/stores/hidebrandsinreports/" . $row['Store']['id']); ?>">
                                    <a href="javascript:return false;" onclick="if (confirm('Enabling this feature will hide brands in products. \n\nAre you sure you want to disable it?')) { $('#hidebrandsinreports_<?php echo $row['Store']['id']; ?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-outline-primary mb-2">
                                        Hide Brands In Reports
                                    </a>
                                </form>
                                <?php
                            }
                            ?>

                            <?php
                            if ($this->Session->read('manager') == '1') {
                                ?>
                                <a href="/stores/edit/<?= $row['Store']['id'] ?>" class="btn btn-sm btn-outline-primary btn-block mb-2">Edit</a>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <p>No Stores Found</p>
        <?php
    }
    ?>

</article>