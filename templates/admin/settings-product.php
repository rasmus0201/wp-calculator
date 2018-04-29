<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$products = Bundsgaard_Settings::getOptions('product');
$categories = Bundsgaard_Settings::getOptions('category');
$colors = Bundsgaard_Settings::getOptions('color');

usort($products, function($a, $b) {
    return $b['id'] - $a['id'];
});

usort($categories, function($a, $b) {
    return $b['id'] - $a['id'];
});

usort($colors, function($a, $b) {
    return $b['id'] - $a['id'];
});

?>

<?php if(empty($categories)): ?>
    <p>OBS: Du skal oprette kategorier før du kan tilføje produkter</p>
<?php else: ?>
    <form method="post" action="">
        <table class="widefat form-table" cellspacing="0">
            <thead>
                <tr>
                    <th>Navn</th>
                    <th>Kategori</th>
                    <th>Pris</th>
                    <th>Rabat ved (>=)</th>
                    <th>% rabat</th>
                    <th>Farver</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input required type="text" name="bc_name" max="50" class="input form-control" placeholder="Produktets navn">
                    </td>
                    <td>
                        <select required class="select form-control" name="bc_category">
                            <option value="">Vælg kategori</option>
                            <?php foreach($categories as $key => $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input required type="number" name="bc_price" class="input form-control" placeholder="Pris">
                    </td>
                    <td>
                        <input type="number" name="bc_qty_discount_at" class="input form-control" placeholder="Ex. 5">
                    </td>
                    <td>
                        <input type="number" name="bc_qty_discount_amount" class="input form-control" placeholder="Ex. 10">
                    </td>
                    <td>
                        <select multiple class="select form-control" name="bc_colors[]">
                            <?php if(!empty($colors)): ?>
                                <?php foreach($colors as $key => $color): ?>
                                    <option value="<?php echo $color['id']; ?>"><?php echo $color['name']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" selected>Ingen farve</option>
                            <?php endif;?>
                        </select>
                    </td>
                    <td>
                        <input class="button button-primary" name="bc_post_product" type="submit" value="Tilføj">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <hr>
    <?php if(!empty($products)): ?>
        <?php foreach($products as $product): ?>
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <td>
                            <input required type="text" name="bc_name" max="50" class="input form-control" value="<?php echo $product['name']; ?>" placeholder="Produktets navn">
                        </td>
                        <td>
                            <select required class="select form-control" name="bc_category">
                                <option value="">Vælg kategori</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"<?php echo ($category['id'] == $product['category']) ? ' selected' : ''; ?>><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input required type="number" name="bc_price" class="input form-control" value="<?php echo $product['price']; ?>" placeholder="Pris">
                        </td>
                        <td>
                            <input type="number" name="bc_qty_discount_at" class="input form-control" value="<?php echo (!empty($product['qty_discount_at'])) ? $product['qty_discount_at'] : ''; ?>" placeholder="Ex. 5">
                        </td>
                        <td>
                            <input type="number" name="bc_qty_discount_amount" class="input form-control" value="<?php echo (!empty($product['qty_discount_amount'])) ? $product['qty_discount_amount'] : ''; ?>" placeholder="Ex. 10">
                        </td>
                        <td>
                            <select multiple class="select form-control" name="bc_colors[]">
                                <?php if(!empty($colors)): ?>
                                    <?php foreach($colors as $key => $color): ?>
                                        <option value="<?php echo $color['id']; ?>"<?php echo (in_array($color['id'], $product['color_ids'])) ? ' selected' : ''; ?>><?php echo $color['name']; ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" selected>Ingen farve</option>
                                <?php endif;?>
                            </select>
                        </td>
                        <td>
                        </td>
                        <td>
                            <input required type="hidden" name="bc_id" value="<?php echo $product['id']; ?>">
                            <input class="button button-primary" name="bc_patch_product" type="submit" value="Opdater">
                            <input class="button button-danger" type="submit" name="bc_delete_product" value="Slet">
                        </td>
                    </tr>
                </table>
            </form>
        <?php endforeach; ?>
    <?php endif;?>
<?php endif; ?>
