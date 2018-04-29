<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$shipping_methods = Bundsgaard_Settings::getOptions('shipping');

usort($shipping_methods, function($a, $b) {
    return $b['id'] - $a['id'];
});

?>

<form method="post" action="">
    <table class="widefat form-table fixed" cellspacing="0">
        <thead>
            <tr>
                <th>Navn</th>
                <th>Pris</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input required type="text" name="bc_name" max="50" class="input form-control" placeholder="Fragtmetodens navn">
                </td>
                <td>
                    <input required type="number" name="bc_price" class="input form-control" placeholder="Fragtmetodens pris">
                </td>
                <td>
                    <input class="button button-primary" name="bc_post_shipping" type="submit" value="Tilføj">
                </td>
            </tr>
        </tbody>
    </table>
</form>
<hr>
<?php if(!empty($shipping_methods)): ?>
    <?php foreach($shipping_methods as $shipping_method): ?>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <td>
                        <input required placeholder="Navn" type="text" name="bc_name" max="50" value="<?php echo $shipping_method['name']; ?>" placeholder="Fragtmetodens navn">
                    </td>
                    <td>
                        <input required type="number" name="bc_price" class="input form-control" value="<?php echo $shipping_method['price']; ?>" placeholder="Fragtmetodens pris">
                    </td>
                    <td>
                        <input required type="hidden" name="bc_id" value="<?php echo $shipping_method['id']; ?>">
                        <input required class="button button-primary" type="submit" name="bc_patch_shipping" value="Opdater">
                    </td>
                    <td>
                        <input class="button button-danger" type="submit" name="bc_delete_shipping" value="Slet">
                    </td>
                </tr>
            </table>
        </form>
    <?php endforeach; ?>
<?php endif;?>
