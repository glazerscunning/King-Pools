<script type="text/template" id="ngg_add_to_cart_tmpl">
    <h2><?php esc_html_e($i18n->add_to_cart)?> <span class="fa fa-shopping-cart"></span></h2>
    <div class='cart_summary'>
        <a href='#' class='cart_count'></a>
        <span class='cart_total'></span>
    </div>

    <!--<span id='ngg_qty_add_desc'><?php esc_html_e($i18n->qty_add_desc); ?></span>-->

    <hr/>

    <div id='items_for_sale'>
        <div class='pricelist_source_accordion' class='accordion'>
            <?php foreach($sources as $source): ?>
            <?php echo $source ?>
            <?php endforeach ?>
        </div>
        <input class='nextgen-button' type='button' id='ngg_checkout_btn' value='<?php esc_attr_e($i18n->checkout)?>'/>
        <input class='nextgen-button' type='button' id='ngg_update_cart_btn' value='<?php esc_attr_e($i18n->update_cart)?>'/>
    </div>
    <div id='not_for_sale'>
        <?php esc_html_e($not_for_sale_msg)?>
    </div>
</script>

<script type="text/template" id="ngg_source_item_tmpl">
    <td class='quantity_field'>
        <input type='number' value='0' min='0'/>
    </td>
    <td class='description_field'>
    </td>
    <td class='price_field'>
    </td>
    <td class='total_field'>
    </td>
</script>

<script type="text/template" id="ngg_source_items_tmpl">
    <thead>
        <tr>
            <th class='quantity_field'><?php esc_html_e($i18n->quantity)?></th>
            <th class='description_field'><?php esc_html_e($i18n->description)?></th>
            <th class='price_field'><?php esc_html_e($i18n->price)?></th>
            <th class='total_field'><?php esc_html_e($i18n->total)?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</script>


