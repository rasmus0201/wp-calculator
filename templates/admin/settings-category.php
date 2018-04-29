<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$categories = Bundsgaard_Settings::getOptions('category');

usort($categories, function($a, $b) {
    $rdiff = $a['parent'] - $b['parent'];
    if ($rdiff) return $rdiff;
    return $a['parent'] - $b['parent'];
});


?>

<p>OBS: Du kan ikke ændre en kategori om til en underkategori, hvis den allerede har underkategorier</p>
<p>OBS: Hvis du sletter en kategori som indeholder underkategorier vil de miste deres parent kategori</p>

<form method="post" action="">
    <table class="widefat form-table fixed" cellspacing="0">
        <thead>
            <tr>
                <th>Navn</th>
                <th>Parent kategori</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input required type="text" name="bc_name" max="50" class="input form-control" placeholder="Kategoriens navn">
                </td>
                <td>
                    <select class="select form-control" name="bc_parent">
                        <option value="-1">Vælg kategori</option>
                        <?php if(!empty($categories)): ?>
                            <?php foreach($categories as $key => $category): ?>
                                <?php if ($category['parent'] < 0): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif;?>
                    </select>
                </td>
                <td>
                    <input class="button button-primary" name="bc_post_category" type="submit" value="Tilføj">
                </td>
            </tr>
        </tbody>
    </table>
</form>
<hr>
<?php if(!empty($categories)): ?>
    <?php foreach($categories as $category): ?>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <td>
                        <input required placeholder="Navn" type="text" name="bc_name" max="50" value="<?php echo $category['name']; ?>">
                    </td>
                    <td>
                        <select class="select form-control" name="bc_parent">
                            <option value="-1">Vælg kategori</option>
                            <?php foreach($categories as $_category): ?>
                                <?php if ($_category['id'] != $category['id'] && $_category['parent'] < 0): ?>
                                    <option value="<?php echo $_category['id']; ?>"<?php echo ($category['parent'] == $_category['id']) ? ' selected' : '';?>><?php echo $_category['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input required type="hidden" name="bc_id" value="<?php echo $category['id']; ?>">
                        <input required class="button button-primary" type="submit" name="bc_patch_category" value="Opdater">
                    </td>
                    <td>
                        <input class="button button-danger" type="submit" name="bc_delete_category" value="Slet">
                    </td>
                </tr>
            </table>
        </form>
    <?php endforeach; ?>
<?php endif;?>
