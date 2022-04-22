<?php
// echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom'); // jQuery UI 
// echo $this->Html->script('jquery-ui-1.8.18.custom.min'); // jQuery UI	
?>
<?php echo $this->set('title_for_layout', ucwords($categoryInfo['Category']['name'])); ?>
<?php $this->Html->meta('keywords', ucwords($categoryInfo['Category']['name']), array('inline' => false)); ?>
<?php $this->Html->meta('description', 'Showing all items under ' . ucwords($categoryInfo['Category']['name']) . ' category', array('inline' => false)); ?>

<section id="ProductInfo">
    <article>
        <header>
            <h2><?php echo ucwords($categoryInfo['Category']['name']); ?></h2>
        </header>
        <?php
        $categoryProducts = $categoryInfo['CategoryProducts'];

        if (!empty($categoryProducts)) {
            foreach ($categoryProducts as $row) {
                $productID = $row['Product']['id'];
                $productName = ucwords($row['Product']['name']);
                $productNameSlug = Inflector::slug($productName, '-');
                $showRequestPriceQuote = $row['Product']['request_price_quote'];

                $productTitle = $productName;
                $productLength = strlen($productTitle);
                if ($productLength > 24) {
                    $productTitle = substr($productTitle, 0, 22) . '...';
                }


                $categoryID = $categoryInfo['Category']['id'];
                $categoryName = $categoryInfo['Category']['name'];
                $categoryNameSlug = Inflector::slug($categoryName, '-');

                $productDsc = $row['Product']['description'];
                $imageID = 0;
                if (!empty($row['Images'])) {
                    $imageID = (isset($row['Images'][0]['Image']['id'])) ? $row['Images'][0]['Image']['id'] : 0;
                }

                //calculate height of product container div
                $height = '205px';
                if ($this->Session->read('Site.request_price_quote')) {
                    $height = '265px';
                }
                ?>

                <div style="height:<?php echo $height; ?>;" class="productBox">
                    <div style="padding:5px 0px;">
                        <div style="text-align:center;">
                            <div class="fitText">
                                <strong><?php
                                    //echo $productTitle;
                                    echo $this->Html->link($productTitle, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, array('title' => $productName, 'escape' => false)); ?></strong>
                            </div>
                            <p>
                                <?php

                                // $productImage = $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'75', 'filename'=>$productNameSlug), array('style'=>'', 'height'=>'150','width'=>'150', 'alt'=>$productName, 'id'=>'image'.$categoryID.'-'.$imageID));
                                // echo $this->Html->link($productImage, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$productName, 'escape'=>false));

                                $productImageUrl = $this->Html->url($this->Img->showImage('img/images/' . $imageID, array('height' => '150', 'width' => '150', 'type' => 'crop', 'quality' => '85', 'filename' => $productNameSlug), array('style' => '', 'height' => '150', 'width' => '150', 'alt' => $productName, 'id' => 'image' . $categoryID . '-' . $imageID), true), true);
                                $imageTagId = 'image' . $categoryID . '-' . $imageID;
                                $image = '<img 
												src = "/img/plants.gif" 
												data-original="' . $productImageUrl . '" 
												height="150" 
												width="150" 
												class="lazy" 
												alt="' . $productName . '"
												id="' . $imageTagId . '" 
											/>';

                                echo $this->Html->link($image, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, array('title' => $productName, 'escape' => false, 'id' => 'imagelink' . $productID));
                                ?>
                            </p>
                        </div>
                        <?php
                        if ($this->Session->read('Site.request_price_quote') and $showRequestPriceQuote) { ?>
                            <div style="text-align:center;">
                                <?php echo $this->Form->submit('Request Price Quote', array('div' => false, 'escape' => false, 'style' => 'width:150px; margin-bottom:5px;', 'onclick' => "showRequestPriceQuoteForm('$categoryID','$productID', '$productTitle')")); ?>

                                <?php echo $this->Form->submit('+ Add To Cart', array('div' => false, 'escape' => false, 'style' => 'width:150px;', 'onclick' => "showAddToCartForm('$categoryID','$productID', '$productTitle')")); ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <p>No Products Found</p>
            <?php
        }
        ?>

    </article>
</section>