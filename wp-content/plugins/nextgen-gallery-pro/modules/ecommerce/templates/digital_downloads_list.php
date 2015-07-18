<div class="digital_downloads_list">
    <p><?php echo esc_html($i18n->order_info) ?></p>
    <table style="font-size: 84%">
        <tr>
            <th class="image_column"><?php echo esc_html($i18n->image_header) ?></th>
            <th class="desc_column"><?php echo esc_html($i18n->item_description_header) ?></th>
            <th class="resolution_column"><?php echo esc_html($i18n->resolution_header) ?></th>
            <th class="download_column"><?php echo esc_html($i18n->download_header) ?></th>
        </tr>
        <tr>
            <td colspan="4" style="padding: 0px; border-bottom: 1px solid rgb(217, 209, 209);"></td>
        </tr>
        <?php foreach ($images as $image): ?>
            <tr>
                <td>
                    <img
                        src="<?php echo esc_attr($image->thumbnail_url)?>"
                        alt="<?php echo esc_attr($image->alttext) ?>"
                    />
                </td>

                <td><?php echo esc_html($image->item_description)?></td>

                <td><?php echo esc_html($image->resolution) ?> px</td>

                <td>
                    <a href="<?php echo esc_attr($image->download_url)?>" download="<?php echo esc_attr($image->resolution)?>-<?php echo esc_attr($image->filename) ?>">Download</a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>