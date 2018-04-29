<?php


/**
 * Add the shortcode
 */
class Bundsgaard_Calculator_Shortcode
{
    public function __construct(){
        # Code on init
    }

    /**
    * Shortcode content
    */
    public function shortcode($atts){
        $atts = shortcode_atts( array(
            'show' => true,
        ), $atts );

        $categories = Bundsgaard_Settings::formatCategories();
        $shipping_methods = Bundsgaard_Settings::formatShipping();

        ob_start(); ?>

        <div class="thumbdiv">
            <div class="table-wrapper">
                <table id="prisberegner-table" data-next-row="0" data-source='<?php echo json_encode($categories); ?>'>
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Farve</th>
                            <th>Pris pr. stk.</th>
                            <th>Antal</th>
                            <th>Rabat</th>
                            <th>Pris i alt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="picker-row">
                            <td>
                                <select class="input input-select" id="choose-product">
                                    <option value="-1">Vælg produkt</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <optgroup label="<?php echo $category['name']; ?>" id="category-<?php echo $category['id']; ?>" data-id="<?php echo $category['id']; ?>" data-type="<?php echo $category['type']; ?>">
                                                <?php if (count($category['subcategories'])): ?>
                                                    <?php foreach ($category['subcategories'] as $subcategory): ?>
                                                        <optgroup label=" - <?php echo $subcategory['name']; ?>" id="subcategory-<?php echo $subcategory['id']; ?>" data-id="<?php echo $subcategory['id']; ?>" data-type="<?php echo $subcategory['type']; ?>">
                                                            <?php foreach ($subcategory['products'] as $product): ?>
                                                                <option value="<?php echo $product['id']; ?>" id="product-<?php echo $product['id']; ?>" data-id="<?php echo $product['id']; ?>" data-category-id="<?php echo $category['id']; ?>" data-subcategory-id="<?php echo $subcategory['id']; ?>" data-type="<?php echo $product['type']; ?>" data-name="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></option>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php foreach ($category['products'] as $product): ?>
                                                    <option value="<?php echo $product['id']; ?>" id="product-<?php echo $product['id']; ?>" data-id="<?php echo $product['id']; ?>" data-category-id="<?php echo $category['id']; ?>" data-subcategory-id="" data-type="<?php echo $product['type']; ?>" data-name="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td>
                                <select class="input input-select" id="choose-color">
                                    <option value="">Vælg farve</option>
                                </select>
                            </td>
                            <td>
                                <span id="price_pr"></span>
                            </td>
                            <td>
                                <input id="product_qty" class="input" type="number" value="1" min="1" max="9999" step="1" value="1">
                            </td>
                            <td>
                                <span id="discount"></span>
                            </td>
                            <td>
                                <span id="price_total"></span>
                            </td>
                        </tr>
                        <tr id="template-row">
                            <td>
                                <select class="input input-select line-choose-product">
                                    <option value="-1">Vælg produkt</option>
                                    <?php foreach ($categories as $category): ?>
                                        <optgroup label="<?php echo $category['name']; ?>">
                                            <?php if (count($category['subcategories'])): ?>
                                                <?php foreach ($category['subcategories'] as $subcategory): ?>
                                                    <optgroup label=" - <?php echo $subcategory['name']; ?>">
                                                        <?php foreach ($subcategory['products'] as $product): ?>
                                                            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php foreach ($category['products'] as $product): ?>
                                                <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="input input-select line-choose-color">
                                    <option value="-1">Vælg farve</option>
                                </select>
                            </td>
                            <td>
                                <span class="line-price_pr"></span>
                            </td>
                            <td>
                                <input class="line-product_qty" class="input" type="number" value="1" min="1" max="9999" step="1" value="1">
                            </td>
                            <td>
                                <span class="line-discount"></span>
                            </td>
                            <td>
                                <span class="line-price_total"></span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button type="button" id="add_row">Tilføj produkt</button>
                            </td>
                            <td></td>
                            <td></td>
                            <td colspan="2">Pris i alt:</td>
                            <td>
                                <span id="lines_total">0 kr</span>
                            </td>
                        </tr>
                        <tr id="shipping-row">
                            <td>
                                <select class="input input-select wpcf7-form-control wpcf7-select form-control" id="choose-shipping" data-source='<?php echo json_encode($shipping_methods); ?>' data-chosen-shipping="">
                                    <option value="-1">Vælg fragt</option>
                                    <?php foreach ($shipping_methods as $method): ?>
                                        <option value="<?php echo $method['id']; ?>" id="category-<?php echo $method['id']; ?>" data-id="<?php echo $method['id']; ?>" data-type="shipping"><?php echo $method['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <noscript>
                    Javascript skal være slået til for at kunne bruge beregneren.
                </noscript>
            </div>
            <hr class="green">
        </div>

        <div id="myModal" class="bunds modal">
            <!-- The Close Button -->
            <span class="close">&times;</span>

            <!-- Modal Content (The Image) -->
            <img class="modal-content" id="img01">

            <!-- Modal Caption (Image Text) -->
            <div id="caption"></div>
        </div>

        <script type="text/javascript">
            (function($){
                $(document).ready(function(){

                    if ($('#prisberegner-table').length) {
                        var data = $('#prisberegner-table').data('source');
                    } else {
                        data = [];
                    }

                    var results = [];

                    addLine();
                    $('#line-0').find('select.line-choose-product').focus();

                    var ship_html = $('#choose-shipping').clone();
                    ship_html.attr('id', 'inserted-shipping')

                    $('#insert-shipping').html( ship_html );

                    $('#prisberegner-table').on('change', '.line-choose-product', function(){
                        var tr = $(this).parent().parent();
                        if (this.value < 0) {
                            //delete element
                            results.splice(tr.data('line-id'), 1);
                            tr.remove();
                            calcLinesTotal();

                            return;
                        }

                        var picked_id = this.value;

                        var picked = $('#product-'+picked_id);
                        var category_id = picked.data('category-id');
                        var subcategory_id = picked.data('subcategory-id');

                        tr.attr('data-chosen-product', picked_id);
                        tr.attr('data-chosen-category', category_id);
                        tr.attr('data-chosen-subcategory', subcategory_id);

                        calcTotalLine(tr);
                        calcLinesTotal();
                        updateTextfield();
                    });

                    $('#prisberegner-table').on('change', '.line-choose-color', function(){
                        $(this).parent().parent().attr('data-chosen-color', this.value);

                        calcTotalLine($(this).parent().parent());
                        calcLinesTotal();
                        updateTextfield();
                    });

                    $('#prisberegner-table').on('change keyup', '.line-product_qty', function(){
                        if (isValidQty(this.value)) {
                            $(this).parent().parent().attr('data-chosen-qty', this.value);

                            calcTotalLine($(this).parent().parent());
                            calcLinesTotal();
                            updateTextfield();
                        }
                    });

                    $('#insert-shipping').on('change', '#inserted-shipping', function(){
                        var shipping_methods = $(this).data('source');
                        $(this).attr('data-chosen-shipping', '');

                        for (method of shipping_methods) {
                            if (this.value == method['id'])  {
                                $(this).attr('data-chosen-shipping', JSON.stringify(method));
                            }
                        }

                        updateTextfield();
                    });

                    $('#add_row').on('click', function(){
                        addLine();
                    });

                    $('body.single-industrilakering #contentwrapper img.models').on('click', function(){
                        $(this).toggleClass('full-image');
                    });

                    function calcTotalLine(tr){
                        var line_id = tr.attr('data-line-id');
                        var product_id = tr.attr('data-chosen-product');
                        var category_id = tr.attr('data-chosen-category');
                        var subcategory_id = tr.attr('data-chosen-subcategory');
                        var color_id = tr.attr('data-chosen-color');

                        var qty = tr.attr('data-chosen-qty');
                        qty = isValidQty(qty) ? qty : 1;

                        var category, subcategory, colors;
                        var chosen_cat, chosen_subcat, chosen_product, chosen_color;

                        if (subcategory_id !== '') {
                            // Product in subcategory
                            for (let [ca_key, category] of data.entries()) {
                                if (category['id'] == category_id) {
                                    for (let [sc_key, subcategory] of category['subcategories'].entries()) {
                                        if (subcategory['id'] == subcategory_id) {
                                            for (let [p_key, product] of subcategory['products'].entries()) {
                                                if (product['id'] == product_id) {
                                                    tr.find('.line-choose-color').html('<option value="-1">Vælg farve</option>');

                                                    colors = product['colors'];

                                                    for (color of colors) {
                                                        if (color_id == color['id']) {
                                                            chosen_color = color;
                                                            tr.find('.line-choose-color').append('<option value="'+color['id']+'" selected>'+color['name']+'</option>');
                                                        } else {
                                                            tr.find('.line-choose-color').append('<option value="'+color['id']+'">'+color['name']+'</option>');
                                                        }
                                                    }

                                                    chosen_cat = category;
                                                    chosen_subcat = subcategory;
                                                    chosen_product = product;

                                                    break;
                                                }
                                            }

                                            break;
                                        }
                                    }

                                    break;
                                }
                            }
                        } else {
                            for (let [ca_key, category] of data.entries()) {
                                if (category['id'] == category_id) {
                                    for (let [p_key, product] of category['products'].entries()) {
                                        if (product['id'] == product_id) {
                                            tr.find('.line-choose-color').html('<option value="-1">Vælg farve</option>');

                                            colors = product['colors'];

                                            for (color of colors) {
                                                if (color_id == color['id']) {
                                                    chosen_color = color;
                                                    tr.find('.line-choose-color').append('<option value="'+color['id']+'" selected>'+color['name']+'</option>');
                                                } else {
                                                    tr.find('.line-choose-color').append('<option value="'+color['id']+'">'+color['name']+'</option>');
                                                }
                                            }

                                            chosen_cat = category;
                                            chosen_subcat = subcategory;
                                            chosen_product = product;

                                            break;
                                        }
                                    }

                                    break;
                                }
                            }
                        }

                        var chosen_line, line_key;
                        for (let [l_key, line] of results.entries()) {
                            if (line['id'] == line_id) {
                                chosen_line = line;
                                line_key = l_key;
                            }
                        }

                        if (chosen_product && chosen_line) {
                            var subtotal = +chosen_product['price'] * qty;
                            var discount = 0;


                            if (chosen_product['qty_discount'] == true && qty >= +chosen_product['qty_discount_at']) {
                                subtotal = (+chosen_product['price'] * qty) * (1 - ((+chosen_product['qty_discount_amount'])/100));

                                discount = (+chosen_product['price'] * qty) * (+chosen_product['qty_discount_amount']/100);
                            }

                            if (chosen_color) {
                                subtotal += +chosen_color['price'];
                            }

                            tr.find('.line-discount').html(+discount+' kr');
                            tr.find('.line-price_pr').html(+chosen_product['price']+' kr');
                            tr.find('.line-price_total').html(+subtotal+' kr');

                            line = {
                                'id': chosen_line['id'],
                                'subtotal': subtotal,
                                'total': subtotal,
                                'discount': discount,
                                'product': chosen_product,
                                'qty': qty,
                                'color': chosen_color,
                                'category': chosen_cat,
                                'subcategory': chosen_subcat,
                            }

                            results[line_key] = line;
                        }
                    }

                    function calcLinesTotal(){
                        total = 0;

                        for (line of results) {
                            total += +line['total'];
                        }

                        $('#lines_total').html(+total+' kr');
                    }

                    function addLine(){
                        var clone = $('#template-row').clone();

                        var next_id = results.length;

                        clone.attr('id', 'line-'+next_id);

                        clone.attr('data-line-id', next_id);
                        clone.attr('data-chosen-qty', 1);
                        clone.attr('data-chosen-product', '');
                        clone.attr('data-chosen-category', '');
                        clone.attr('data-chosen-subcategory', '');

                        clone.addClass('line');

                        results.push({
                            'id': next_id,
                            'subtotal': 0,
                            'total': 0,
                            'discount': 0,
                            'product': null,
                            'category': null,
                            'subcategory': null,
                            'color': null,
                            'qty': null,
                        });

                        $('#prisberegner-table tbody').append(clone);

                        $('#line-'+next_id+' select.line-choose-product').focus();
                    }

                    function updateTextfield(){
                        var ns = '-------\n';
                        var total = 0;

                        var msg = 'Prisberegner: \n';
                        msg += ns;

                        for (line of results) {
                            if (line['product']) {
                                if (line['subcategory']) {
                                    msg += 'Produkt: '+line['qty']+' x '+line['product']['name']+' ('+line['category']['name']+', '+line['subcategory']['name']+'), estimeret pris: '+line['total']+'kr, estimeret rabat: '+line['discount']+'kr (er trukket fra) \n';
                                } else {
                                    msg += 'Produkt: '+line['qty']+' x '+line['product']['name']+' ('+line['category']['name']+'), estimeret pris: '+line['total']+'kr, estimeret rabat: '+line['discount']+'kr (er trukket fra) \n';
                                }

                                if (line['color']) {
                                    msg += 'Produkts farve: '+line['color']['name']+', estimeret pris: '+line['color']['price']+'kr \n';
                                } else {
                                    msg += 'Produkts farve: Ikke valgt \n';
                                }
                                msg += ns;

                                total += +line['total'];
                            }
                        }

                        var shipping = $('#inserted-shipping').attr('data-chosen-shipping');
                        if (shipping != '') {
                            shipping = JSON.parse(shipping);
                            total += +shipping['price'];
                            msg += 'Fragt: '+shipping['name']+', pris: '+shipping['price']+'kr \n';
                        } else {
                            msg += 'Fragt: Ikke valgt \n';
                        }

                        msg += 'Total pris: '+total+'kr \n';

                        msg += ns;
                        msg += 'Din besked til os: \n';

                        $('#prisberegner-textfield').html(msg);
                        $('#prisberegner-textfield').val(msg);
                    }

                    function isValidQty(n){
                        return !isNaN(parseFloat(n)) && isFinite(n) && n >= 1;
                    }

                });
            })(jQuery);

            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = document.getElementById('models-image');
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            img.onclick = function(){
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }
            modal.onclick = function() {
                modal.style.display = "none";
            }
        </script>

        <?php
        return ob_get_clean();
    }
}
