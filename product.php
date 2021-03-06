<?php
    include_once('header.php');
    include_once('includes/product.class.php');
    $obj_product = new Product($db);

    $this_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 1;

    if ($this_id < 1) {
        echo '<main><div class="small-content mt-5">Something went wrong</div></main>';
        include_once('footer.php');
        exit();
    }

    $product = $obj_product->getProduct($this_id);
    $artist = $obj_product->getArtist($product['artist_id']);
    $tags = $obj_product->getProductGenres($this_id);
    $featured_products = $obj_product->getRecommendedProducts($this_id);

?>

<!--BODY-->
<main>
    
<div class="container">
    <div class="row my-4">
        <div class="col-sm-7 col-12">
            <img src="product_imgs/product_<?=$product['product_id']?>.jpg" alt="<?=$product['product_name']?>" class="img-fluid">
        </div>

        <div class="col-sm-5 col-12">
            <div class="product-description">
                <div class="product-name font-rubik mt-2 mt-sm-4"><h1><?=$product['product_name']?></h1></div>
                <hr>
                <div class="product-artist mb-2">
                    <strong>Artist: </strong>
                    <a href="artist.php?id=<?=$product['artist_id']?>" class="category-tag"><?=$artist?></a>
                </div>
                
                <div class="product-total mb-2"><strong>Run: </strong><?=$product['product_total']?></div>
                <p>Each print is numbered and signed by the artist.</p>
                <?php if ($product['product_quantity'] == 0): ?>
                    <div class="sold-out mb-2"><strong>SOLD OUT</strong></div>
                <?php else: ?>
                    <div class="price-info mb-2">
                <?php if ($product['product_sale'] > 0): ?>
                    <span class="product-price h2">&pound;<?=$product['product_sale']?></span>
                    <span class="product-old-price">&pound;<?=$product['product_price']?></span>
                    <br>
                    <span class="product-savings">You save &pound;<?=$product['product_price']-$product['product_sale']?></span>
                    
                <?php else: ?>
                    <span class="product-price h2">&pound;<?=$product['product_price']?></span>
                <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if($product['product_quantity'] <= 20 && $product['product_quantity'] > 0): ?>
                    <div class="low-stock mb-2">Low in stock, buy it now!</div>
                <?php endif; ?>
                <?php if ($product['product_quantity'] > 0): ?>
                <form class="add_to_cart" action="add_to_cart.php" method="POST">
                    <input type="hidden" id="product-id" name="product_id" value=<?=$this_id?>>
                    <p class="my-4">
                        Quantity:
                        <select class="product-quantity-select w-auto ms-2 p-1">
                            <?php for ($i = 1; $i <= 3; $i++) : ?>
                                <?php if($i == 1): ?>
                                    <option selected value=<?=$i?>><?=$i?></option>
                                <?php else: ?>
                                    <option value=<?=$i?>><?=$i?></option>
                                <?php endif ?>
                            <?php endfor ?>
                        </select>
                    </p>
                    <button type="submit" id="add-cart-submit" class="btn btn-primary add-to-cart mb-2">Add To Cart</button>
                    <div class="alert cart-alert" role="alert">
                        Cart message here
                    </div>
                </form>
                <?php endif; ?>
                
            </div>
            <hr>
            <div class="product-tags">
                <h5>Tags:</h5>
                <?php foreach ($tags as $tag) : ?>
                    <a href="category.php?id=<?=$tag['genre_id']?>" class="category-tag"><?=$tag['genre_name']?></a>
                    <br>
                <?php endforeach?>
            </div>
        </div>
    </div>

    <!-- More Products  -->
    <div class="row font-rubik" id="recommended-products">
            <h3>View More Prints</h3>
            <hr>
            <div class="row mb-3 mx-auto">
                <?php foreach ($featured_products as $item) : ?>
                <div class="col-md-3 col-sm-6 col-12 my-1">
                    <?=createProductCard($item);?> 
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <!-- !More Products  -->

</div>
</main>

<?php
    include_once('footer.php');
?>