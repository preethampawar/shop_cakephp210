<?php
App::uses('PromoCode', 'Model');
$promoCodeModel = new PromoCode;
$siteId = $this->Session->read('Site.id');
$promoCodes = $promoCodeModel->getActivePromoCodes($siteId);
?>

<?php
if ($promoCodes) {
    ?>
    <div class="mt-4">
        <h6>Available Offers <i class="bi bi-percent"></i></h6>

        <div>
            <table class="table">
                <tbody>
                <?php
                foreach ($promoCodes as $row) {
                    ?>                
                    <tr>
                        <td>
                            <span class="fw-bold small"><?= $row['PromoCode']['name'] ?></span>
                            <div class="text-muted small"><?= $row['PromoCode']['terms'] ?></div>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-primary btn-sm" onclick="applyPromoCode('<?= $row['PromoCode']['name'] ?>')">Apply</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}
?>