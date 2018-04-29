<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$colors = Bundsgaard_Settings::getOptions('color');

usort($colors, function($a, $b) {
    return $b['id'] - $a['id'];
});

?>

<p>OBS: Hvis du sletter en farve, vil produkter ikke længere have denne farvemulighed</p>

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
                    <input required type="text" name="bc_name" max="50" class="input form-control" placeholder="Farvens navn">
                </td>
                <td>
                    <input required type="number" name="bc_price" class="input form-control" placeholder="Farvens pris (bliver lagt til 1 gang)">
                </td>
                <td>
                    <input class="button button-primary" name="bc_post_color" type="submit" value="Tilføj">
                </td>
            </tr>
        </tbody>
    </table>
</form>
<hr>
<?php if(!empty($colors)): ?>
    <?php foreach($colors as $color): ?>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <td>
                        <input required placeholder="Navn" type="text" name="bc_name" max="50" value="<?php echo $color['name']; ?>" placeholder="Farvens navn">
                    </td>
                    <td>
                        <input required type="number" name="bc_price" class="input form-control" value="<?php echo $color['price']; ?>" placeholder="Farvens pris (bliver lagt til 1 gang)">
                    </td>
                    <td>
                        <input required type="hidden" name="bc_id" value="<?php echo $color['id']; ?>">
                        <input required class="button button-primary" type="submit" name="bc_patch_color" value="Opdater">
                    </td>
                    <td>
                        <input class="button button-danger" type="submit" name="bc_delete_color" value="Slet">
                    </td>
                </tr>
            </table>
        </form>
    <?php endforeach; ?>
<?php endif;?>
