<?php
// ==================================== CPT proxy =================================
function create_proxy_cpt()
{
    $labels = array(
        'name' => 'Proxy',
        'singular_name' => 'Proxy',
        'menu_name' => 'Proxy',
        'all_items' => 'All Proxy',
        'add_new_item' => 'Add New Proxy',
        'edit_item' => 'Edit Proxy'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true
    );

    register_post_type('proxy', $args);
}
add_action('init', 'create_proxy_cpt');
// ==================== METABOX - MAIN CONTENT ==============================================================
function add_proxy_content_metabox()
{
    add_meta_box(
        'proxy_content_box',             // ID
        'Proxy Main Content',          // Tiêu đề box
        'render_proxy_content_metabox',  // Callback 
        'proxy',                     // CPT
        'normal',                    // Vị trí
        'high'                       // Ưu tiên
    );
}
add_action('add_meta_boxes', 'add_proxy_content_metabox');

// hiển thị trình soạn thảo
function render_proxy_content_metabox($post)
{
    // Lấy dữ liệu đã lưu
    $desc = get_post_meta($post->ID, '_proxy_content', true);

    wp_editor(
        $desc,
        'proxy_content',
        array(
            'textarea_name' => 'proxy_content',
            'media_buttons' => true, //chèn ảnh
            'textarea_rows' => 10,
            'teeny' => false, //toolbar
            'quicktags' => true, // HTML nhanh
        )
    );
}

function save_proxy_content_metabox($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (isset($_POST['proxy_content'])) {
        update_post_meta($post_id, '_proxy_content', wp_kses_post($_POST['proxy_content']));
    }
}
add_action('save_post', 'save_proxy_content_metabox');

// ====================================== proxy category taxonomy ============================================
function create_proxy_category_taxonomy()
{
    $labels = array(
        'name' => 'Proxy Categories',
        'singular_name' => 'Proxy Category',
        'menu_name' => 'Categories',
        'all_items' => 'All Categories',
        'edit_item' => 'Edit Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'search_items' => 'Search Categories',
        'popular_items' => 'Popular Categories',
        'separate_items_with_commas' => 'Separate categories with commas',
        'add_or_remove_items' => 'Add or remove categories',
        'choose_from_most_used' => 'Choose from the most used categories',
        'not_found' => 'No categories found.'
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true, //  checkbox 
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'proxy-category'),
    );

    register_taxonomy('proxy_category', array('proxy'), $args);
}
add_action('init', 'create_proxy_category_taxonomy');

// ================================== logo category ==================================

add_action('proxy_category_add_form_fields', 'proxy_category_custom_fields_add');
function proxy_category_custom_fields_add()
{
    ?>
    <div class="form-field">
        <label for="category_logo">Category Logo</label>
        <textarea name="category_logo" id="category_logo"></textarea>
    </div>
    <?php
}

add_action('proxy_category_edit_form_fields', 'proxy_category_custom_fields_edit');
function proxy_category_custom_fields_edit($term)
{
    $value = get_term_meta($term->term_id, 'category_logo', true);
    ?>
    <tr class="form-field">
        <th><label for="category_logo">User Reviews</label></th>
        <td>
            <textarea name="category_logo" id="category_logo" rows="5"><?php echo esc_textarea($value); ?></textarea>
        </td>
    </tr>
    <?php
}


add_action('created_proxy_category', 'proxy_category_custom_fields_save');
add_action('edited_proxy_category', 'proxy_category_custom_fields_save');
function proxy_category_custom_fields_save($term_id)
{
    if (isset($_POST['category_logo'])) {
        update_term_meta($term_id, 'category_logo', sanitize_textarea_field($_POST['category_logo']));
    }
}

// ====================== them field vao endpoint ======================================================================

add_action('rest_api_init', function () {
    register_rest_field(
        'proxy_category',              // taxonomy slug
        'category_logo',                // REST field name
        [
            'get_callback' => function ($term_arr) {
                return get_term_meta($term_arr['id'], 'category_logo', true);
            },
            'schema' => null
        ]
    );
});
// ======================================================================================================================
// ======================================== proxy home info =================================================

// Register meta box
function proxy_description_meta_box()
{
    add_meta_box(
        'proxy_description',
        'Proxy Summary (HomePage)',
        'proxy_home_info_callback', // callback
        'proxy',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'proxy_description_meta_box');

//================ Render meta box HTML=======================
function proxy_home_info_callback($post)
{
    wp_nonce_field('proxy_home_info_nonce', 'proxy_home_info_nonce_field');

    // Lấy dữ liệu (unserialize)
    $saved_data = get_post_meta($post->ID, '_proxy_data', true);

    if (!empty($saved_data) && is_string($saved_data)) {
        $saved_data = maybe_unserialize($saved_data);
    }

    // Set default values
    $tags = isset($saved_data['tags']) && is_array($saved_data['tags']) ? $saved_data['tags'] : array();
    $logo = isset($saved_data['logo']) ? $saved_data['logo'] : '';
    $summary = isset($saved_data['summary']) ? $saved_data['summary'] : '';
    $rating = isset($saved_data['rating']) ? $saved_data['rating'] : '';
    $advanced = isset($saved_data['advanced']) && is_array($saved_data['advanced']) ? $saved_data['advanced'] : array();
    $status = isset($saved_data['status']) && is_array($saved_data['status']) ? $saved_data['status'] : array();
    $price = isset($saved_data['price']) ? $saved_data['price'] : '';
    $features_overview = isset($saved_data['features_overview']) && is_array($saved_data['features_overview']) ? $saved_data['features_overview'] : array();
    ?>

    <style>
        .provider-meta-box {
            padding: 20px;
        }

        .provider-field {
            margin-bottom: 25px;
        }

        .provider-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .provider-field input[type="text"],
        .provider-field input[type="number"],
        .provider-field textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .provider-field textarea {
            min-height: 80px;
        }

        .repeatable-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .repeatable-item input {
            flex: 1;
        }

        .btn-add,
        .btn-remove {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-add {
            background: #0073aa;
            color: white;
        }

        .btn-add:hover {
            background: #005a87;
        }

        .btn-remove {
            background: #dc3232;
            color: white;
            padding: 8px 12px;
        }

        .btn-remove:hover {
            background: #a00;
        }

        .field-description {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }

        .desc-meta-box {
            padding: 20px;
        }

        .desc-section {
            margin-bottom: 35px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #0073aa;
        }

        .desc-section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0073aa;
            text-transform: uppercase;
        }

        .desc-field {
            margin-bottom: 20px;
        }

        .desc-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .desc-field textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .desc-repeatable-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .desc-repeatable-item input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .desc-btn-add,
        .desc-btn-remove {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .desc-btn-add {
            background: #0073aa;
            color: white;
        }

        .desc-btn-add:hover {
            background: #005a87;
        }

        .desc-btn-remove {
            background: #dc3232;
            color: white;
            padding: 8px 12px;
        }

        .desc-btn-remove:hover {
            background: #a00;
        }

        .feature-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
        }

        .feature-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .feature-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .feature-group-main-title {
            margin-bottom: 15px;
        }

        .feature-group-main-title input {
            width: 100%;
            padding: 10px;
            border: 2px solid #0073aa;
            border-radius: 6px;
            font-weight: 600;
        }

        .feature-items-list {
            margin-top: 15px;
            padding: 15px;
            background: #f0fdf4;
            border-radius: 6px;
        }

        .feature-item-group {
            border: 2px solid #0073aa;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 6px;
            background: white;
        }

        .feature-item-group input,
        .feature-item-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .feature-item-group textarea {
            min-height: 50px;
        }

        .feature-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        #pricing-plans-metabox {
            margin-bottom: 20px;
        }

        .pricing-plan-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .pricing-plan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .pricing-plan-title {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        .pricing-plan-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .pricing-field {
            display: flex;
            flex-direction: column;
        }

        .pricing-field label {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .pricing-field input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            width: 100%;
        }
    </style>

    <div class="provider-meta-box">

        <!-- Tags -->
        <div class="provider-field">
            <label>Tags</label>
            <div id="tags-container">
                <?php
                if (!empty($tags)) {
                    foreach ($tags as $index => $tag) {
                        echo '<div class="repeatable-item">
                                <input type="text" name="proxy_tags[]" value="' . esc_attr($tag) . '" placeholder="Nhập tag (vd: Best Overall)">
                                <button type="button" class="btn-remove remove-tag">✕</button>
                              </div>';
                    }
                } else {
                    echo '<div class="repeatable-item">
                            <input type="text" name="proxy_tags[]" value="" placeholder="Best Overall">
                            <button type="button" class="btn-remove remove-tag">✕</button>
                          </div>';
                }
                ?>
            </div>
            <button type="button" class="btn-add add-tag">ADD</button>
        </div>

        <!-- Logo URL -->
        <div class="provider-field">
            <label>Logo URL</label>
            <input type="text" name="proxy_logo" id="proxy_logo" value="<?php echo esc_attr($logo); ?>"
                placeholder="https://example.com/logo.png">
            <?php if (!empty($logo)): ?>
                <div id="logo-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($logo); ?>"
                        style="max-width: 150px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
            <?php else: ?>
                <div id="logo-preview" style="margin-top: 10px; display: none;">
                    <img src=""
                        style="max-width: 150px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary -->
        <div class="provider-field">
            <label>Summary</label>
            <textarea name="proxy_summary"
                placeholder="Premium residential proxies with 100M+ IP pool"><?php echo esc_textarea($summary); ?></textarea>
        </div>

        <!-- Rating -->
        <div class="provider-field">
            <label>Rating</label>
            <input type="number" name="proxy_rating" value="<?php echo esc_attr($rating); ?>" step="0.1" min="0" max="5"
                placeholder="0.0">
            <p class="field-description">Điểm đánh giá từ 0 đến 5</p>
        </div>
        <!-- Advanced Features -->
        <div class="provider-field">
            <label>Advanced Features</label>
            <div id="advanced-container">
                <?php
                if (!empty($advanced)) {
                    foreach ($advanced as $index => $feature) {
                        echo '<div class="repeatable-item">
                                <input type="text" name="proxy_advanced[]" value="' . esc_attr($feature) . '" placeholder="195+ Countries, 99.9% Uptime...">
                                <button type="button" class="btn-remove remove-advanced">✕</button>
                              </div>';
                    }
                } else {
                    echo '<div class="repeatable-item">
                            <input type="text" name="proxy_advanced[]" value="" placeholder="195+ Countries">
                            <button type="button" class="btn-remove remove-advanced">✕</button>
                          </div>';
                }
                ?>
            </div>
            <button type="button" class="btn-add add-advanced">ADD</button>
        </div>

        <!-- Status -->
        <div class="provider-field">
            <label>Status</label>
            <div id="status-container">
                <?php
                if (!empty($status)) {
                    foreach ($status as $index => $item) {
                        echo '<div class="repeatable-item">
                                <input type="text" name="proxy_status[]" value="' . esc_attr($item) . '" placeholder="Còn hàng/Hết hàng">
                                <button type="button" class="btn-remove remove-status">✕</button>
                              </div>';
                    }
                } else {
                    echo '<div class="repeatable-item">
                            <input type="text" name="proxy_status[]" value="" placeholder="Còn hàng">
                            <button type="button" class="btn-remove remove-status">✕</button>
                          </div>';
                }
                ?>
            </div>
            <button type="button" class="btn-add add-status">ADD</button>
        </div>

        <!-- Price -->
        <div class="provider-field">
            <label>Price</label>
            <input type="number" name="proxy_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0"
                placeholder="0.0">
            <p class="field-description">Giá khởi điểm (USD)</p>
        </div>

        <div class="desc-section" id="features-overview-metabox-new">
            <div class="desc-section-title">Price based on </div>

            <div id="features-overview-container-new" class="features-container-unique">
                <?php
                // Lấy dữ liệu từ features_section thay vì features_overview
                $proxy_data = get_post_meta($post->ID, '_proxy_data', true);
                $proxy_data = maybe_unserialize($proxy_data);
                $features_section = isset($proxy_data['features_section']) ? $proxy_data['features_section'] : array();

                if (!empty($features_section)) {
                    foreach ($features_section as $group_index => $feature_group) {
                        $group_title = isset($feature_group['title']) ? $feature_group['title'] : '';
                        $group_items = isset($feature_group['items']) && is_array($feature_group['items']) ? $feature_group['items'] : array();
                        ?>
                        <div class="feature-group" data-group-index="<?php echo $group_index; ?>">
                            <div class="feature-group-header">
                                <span class="feature-group-title">Proxy Group #<?php echo $group_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-feature-group">✕</button>
                            </div>

                            <div class="feature-group-main-title">
                                <label class="rating-label">Group Title</label>
                                <input type="text" name="features_section_group_title[]"
                                    value="<?php echo esc_attr($group_title); ?>" placeholder="Proxy Types">
                            </div>

                            <div class="feature-items-list">
                                <label class="rating-label">Proxy in this Group</label>
                                <div class="feature-items-container">
                                    <?php
                                    if (!empty($group_items)) {
                                        foreach ($group_items as $item_index => $item) {
                                            $item_title = isset($item['title']) ? $item['title'] : '';
                                            $item_summary = isset($item['summary']) ? $item['summary'] : '';
                                            ?>
                                            <div class="feature-item-group">
                                                <div class="feature-item-header">
                                                    <label class="rating-label" style="margin: 0;">Item
                                                        #<?php echo $item_index + 1; ?></label>
                                                    <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                                </div>
                                                <input type="text" name="features_section_item_title_<?php echo $group_index; ?>[]"
                                                    value="<?php echo esc_attr($item_title); ?>" placeholder="Residential Proxies">
                                                <textarea name="features_section_item_summary_<?php echo $group_index; ?>[]"
                                                    placeholder="100M+ real residential IPs"><?php echo esc_textarea($item_summary); ?></textarea>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="feature-item-group">
                                            <div class="feature-item-header">
                                                <label class="rating-label" style="margin: 0;">Item #1</label>
                                                <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                            </div>
                                            <input type="text" name="features_section_item_title_<?php echo $group_index; ?>[]" value=""
                                                placeholder="Residential Proxies">
                                            <textarea name="features_section_item_summary_<?php echo $group_index; ?>[]"
                                                placeholder="100M+ real residential IPs"></textarea>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="feature-group" data-group-index="0">
                        <div class="feature-group-header">
                            <span class="feature-group-title">Proxys Group #1</span>
                            <button type="button" class="desc-btn-remove remove-feature-group">✕</button>
                        </div>

                        <div class="feature-group-main-title">
                            <label class="rating-label">Group Title</label>
                            <input type="text" name="features_section_group_title[]" value="" placeholder="Proxy Types">
                        </div>

                        <div class="feature-items-list">
                            <label class="rating-label">Proxy in this Group</label>
                            <div class="feature-items-container">
                                <div class="feature-item-group">
                                    <div class="feature-item-header">
                                        <label class="rating-label" style="margin: 0;">Item #1</label>
                                        <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                    </div>
                                    <input type="text" name="features_section_item_title_0[]" value=""
                                        placeholder="Residential Proxies">
                                    <textarea name="features_section_item_summary_0[]"
                                        placeholder="100M+ real residential IPs"></textarea>
                                </div>
                            </div>
                            <button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-feature-group">ADD</button>
        </div>
        <div class="desc-section" id="pricing-plans-metabox">
            <div class="desc-section-title">Pricing Plans</div>

            <div id="pricing-plans-container">
                <?php
                // Lấy dữ liệu pricing plans
                $proxy_data = get_post_meta($post->ID, '_proxy_data', true);
                $proxy_data = maybe_unserialize($proxy_data);
                $pricing_plans = isset($proxy_data['pricing_plans']) ? $proxy_data['pricing_plans'] : array();

                if (!empty($pricing_plans)) {
                    foreach ($pricing_plans as $plan_index => $plan) {
                        $month = isset($plan['month']) ? $plan['month'] : '';
                        $price = isset($plan['price']) ? $plan['price'] : '';
                        ?>
                        <div class="pricing-plan-item" data-plan-index="<?php echo $plan_index; ?>">
                            <div class="pricing-plan-header">
                                <span class="pricing-plan-title">Plan #<?php echo $plan_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-pricing-plan">✕</button>
                            </div>
                            <div class="pricing-plan-fields">
                                <div class="pricing-field">
                                    <label class="rating-label">Month(s)</label>
                                    <input type="number" name="pricing_plan_month[]" value="<?php echo esc_attr($month); ?>"
                                        placeholder="3" min="1" step="1">
                                </div>
                                <div class="pricing-field">
                                    <label class="rating-label">Price</label>
                                    <input type="number" name="pricing_plan_price[]" value="<?php echo esc_attr($price); ?>"
                                        placeholder="25.00" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="pricing-plan-item" data-plan-index="0">
                        <div class="pricing-plan-header">
                            <span class="pricing-plan-title">Plan #1</span>
                            <button type="button" class="desc-btn-remove remove-pricing-plan">✕</button>
                        </div>
                        <div class="pricing-plan-fields">
                            <div class="pricing-field">
                                <label class="rating-label">Month(s)</label>
                                <input type="number" name="pricing_plan_month[]" value="" placeholder="3" min="1" step="1">
                            </div>
                            <div class="pricing-field">
                                <label class="rating-label">Price</label>
                                <input type="number" name="pricing_plan_price[]" value="" placeholder="*1.0" min="0"
                                    step="0.01">
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-pricing-plan">ADD</button>
        </div>


    </div>
    <script>
        jQuery(document).ready(function ($) {
            // ===================================================================
            // === CÁC PHẦN KHÁC (logo, tag, advanced, status) - GIỮ NGUYÊN ===
            // ===================================================================
            $('#proxy_logo').on('input', function () {
                var logoUrl = $(this).val();
                if (logoUrl) {
                    $('#logo-preview img').attr('src', logoUrl);
                    $('#logo-preview').show();
                } else {
                    $('#logo-preview').hide();
                }
            });

            $('.add-tag').on('click', function () {
                var html = '<div class="repeatable-item">' +
                    '<input type="text" name="proxy_tags[]" value="" placeholder="Nhập tag">' +
                    '<button type="button" class="btn-remove remove-tag">X</button>' +
                    '</div>';
                $('#tags-container').append(html);
            });

            $(document).on('click', '.remove-tag', function () {
                if ($('#tags-container .repeatable-item').length > 1) {
                    $(this).closest('.repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 tag!');
                }
            });

            $('.add-advanced').on('click', function () {
                var html = '<div class="repeatable-item">' +
                    '<input type="text" name="proxy_advanced[]" value="" placeholder="Nhập feature">' +
                    '<button type="button" class="btn-remove remove-advanced">X</button>' +
                    '</div>';
                $('#advanced-container').append(html);
            });

            $(document).on('click', '.remove-advanced', function () {
                if ($('#advanced-container .repeatable-item').length > 1) {
                    $(this).closest('.repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 feature!');
                }
            });

            $('.add-status').on('click', function () {
                var html = '<div class="repeatable-item">' +
                    '<input type="text" name="proxy_status[]" value="" placeholder="Nhập status">' +
                    '<button type="button" class="btn-remove remove-status">X</button>' +
                    '</div>';
                $('#status-container').append(html);
            });

            $(document).on('click', '.remove-status', function () {
                if ($('#status-container .repeatable-item').length > 1) {
                    $(this).closest('.repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 status!');
                }
            });

            // ======================== plan pricing ========================
            jQuery(document).ready(function ($) {
                var $metabox = $('#pricing-plans-metabox');
                if (!$metabox.length) return;

                var $container = $('#pricing-plans-container');

                // === ADD PRICING PLAN ===
                $metabox.on('click', '.add-pricing-plan', function (e) {
                    e.stopImmediatePropagation();
                    e.preventDefault();

                    var count = $container.find('.pricing-plan-item').length;
                    var html = '<div class="pricing-plan-item" data-plan-index="' + count + '">' +
                        '<div class="pricing-plan-header">' +
                        '<span class="pricing-plan-title">Plan #' + (count + 1) + '</span>' +
                        '<button type="button" class="desc-btn-remove remove-pricing-plan">✕</button>' +
                        '</div>' +
                        '<div class="pricing-plan-fields">' +
                        '<div class="pricing-field">' +
                        '<label class="rating-label">Month(s)</label>' +
                        '<input type="number" name="pricing_plan_month[]" value="" placeholder="3" min="1" step="1">' +
                        '</div>' +
                        '<div class="pricing-field">' +
                        '<label class="rating-label">Price ($)</label>' +
                        '<input type="number" name="pricing_plan_price[]" value="" placeholder="25.00" min="0" step="0.01">' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    $container.append(html);
                    return false;
                });

                // === REMOVE PRICING PLAN ===
                $metabox.on('click', '.remove-pricing-plan', function (e) {
                    e.stopImmediatePropagation();
                    if ($container.find('.pricing-plan-item').length > 1) {
                        $(this).closest('.pricing-plan-item').remove();
                        updatePlanNumbers();
                    } else {
                        alert('Phải có ít nhất 1 pricing plan!');
                    }
                    return false;
                });

                // === UPDATE PLAN NUMBERS ===
                function updatePlanNumbers() {
                    $container.find('.pricing-plan-item').each(function (index) {
                        $(this).attr('data-plan-index', index);
                        $(this).find('.pricing-plan-title').text('Plan #' + (index + 1));
                    });
                }

                updatePlanNumbers();
            });


            // =================== Features Overview Section ================
            var $metabox = $('#features-overview-metabox-new');
            if (!$metabox.length) return;

            var $container = $('#features-overview-container-new');

            // === ADD GROUP ===
            $metabox.on('click', '.add-feature-group', function (e) {
                e.stopImmediatePropagation();
                e.preventDefault();

                var count = $container.find('.feature-group').length;
                var html = '<div class="feature-group" data-group-index="' + count + '">' +
                    '<div class="feature-group-header">' +
                    '<span class="feature-group-title">Feature Group #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-feature-group">✕</button>' +
                    '</div>' +
                    '<div class="feature-group-main-title">' +
                    '<label class="rating-label">Group Title</label>' +
                    '<input type="text" name="features_section_group_title[]" value="" placeholder="Proxy Types">' +
                    '</div>' +
                    '<div class="feature-items-list">' +
                    '<label class="rating-label">Features in this Group</label>' +
                    '<div class="feature-items-container">' +
                    '<div class="feature-item-group">' +
                    '<div class="feature-item-header">' +
                    '<label class="rating-label" style="margin: 0;">Item #1</label>' +
                    '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
                    '</div>' +
                    '<input type="text" name="features_section_item_title_' + count + '[]" value="" placeholder="Residential Proxies">' +
                    '<textarea name="features_section_item_summary_' + count + '[]" placeholder="100M+ real residential IPs"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>' +
                    '</div>' +
                    '</div>';

                $container.append(html);
                return false;
            });

            // === ADD ITEM ===
            $metabox.on('click', '.add-feature-item', function (e) {
                e.stopImmediatePropagation();
                e.preventDefault();

                var $group = $(this).closest('.feature-group');
                var groupIndex = $group.attr('data-group-index');
                var $itemsContainer = $group.find('.feature-items-container');
                var itemCount = $itemsContainer.find('.feature-item-group').length + 1;

                var html = '<div class="feature-item-group">' +
                    '<div class="feature-item-header">' +
                    '<label class="rating-label" style="margin: 0;">Item #' + itemCount + '</label>' +
                    '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
                    '</div>' +
                    '<input type="text" name="features_section_item_title_' + groupIndex + '[]" value="" placeholder="Feature Title">' +
                    '<textarea name="features_section_item_summary_' + groupIndex + '[]" placeholder="Feature summary"></textarea>' +
                    '</div>';

                $itemsContainer.append(html);
                return false;
            });

            // === REMOVE GROUP ===
            $metabox.on('click', '.remove-feature-group', function (e) {
                e.stopImmediatePropagation();
                if ($container.find('.feature-group').length > 1) {
                    $(this).closest('.feature-group').remove();
                    updateGroupIndexes();
                } else {
                    alert('Phải có ít nhất 1 feature group!');
                }
                return false;
            });

            // === REMOVE ITEM ===
            $metabox.on('click', '.remove-feature-item', function (e) {
                e.stopImmediatePropagation();
                var $itemsContainer = $(this).closest('.feature-items-container');
                if ($itemsContainer.find('.feature-item-group').length > 1) {
                    $(this).closest('.feature-item-group').remove();
                    updateItemNumbers($itemsContainer);
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
                return false;
            });

            // === CẬP NHẬT INDEX ===
            function updateGroupIndexes() {
                $container.find('.feature-group').each(function (index) {
                    var $group = $(this);
                    $group.attr('data-group-index', index);
                    $group.find('.feature-group-title').text('Feature Group #' + (index + 1));
                    $group.find('input[name^="features_section_item_title_"]').attr('name', 'features_section_item_title_' + index + '[]');
                    $group.find('textarea[name^="features_section_item_summary_"]').attr('name', 'features_section_item_summary_' + index + '[]');
                });
            }

            function updateItemNumbers($itemsContainer) {
                $itemsContainer.find('.feature-item-group').each(function (index) {
                    $(this).find('.feature-item-header label').first().text('Item #' + (index + 1));
                });
            }

            updateGroupIndexes();
        });
    </script>
    <?php
}

// Lưu dữ liệu
function save_proxy_home_info($post_id)
{
    // nonce
    if (
        !isset($_POST['proxy_home_info_nonce_field']) ||
        !wp_verify_nonce($_POST['proxy_home_info_nonce_field'], 'proxy_home_info_nonce')
    ) {
        return;
    }

    // autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Kiểm tra quyền
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // serialize
    $proxy_data = array(
        'tags' => array(),
        'logo' => '',
        'summary' => '',
        'rating' => 0,
        'advanced' => array(),
        'status' => array(),
        'price' => 0,
        'pricing_plans' => array(),
        'features_section' => array(),  // METABOX TRÊN - Features Overview Section
    );

    // Thu thập dữ liệu từ form
    if (isset($_POST['proxy_tags'])) {
        $proxy_data['tags'] = array_map('wp_kses_post', array_filter($_POST['proxy_tags']));
    }

    if (isset($_POST['proxy_logo'])) {
        $proxy_data['logo'] = esc_url_raw($_POST['proxy_logo']);
    }

    if (isset($_POST['proxy_summary'])) {
        $proxy_data['summary'] = wp_kses_post($_POST['proxy_summary']);
    }

    if (isset($_POST['proxy_rating'])) {
        $proxy_data['rating'] = floatval($_POST['proxy_rating']);
    }

    if (isset($_POST['proxy_advanced'])) {
        $proxy_data['advanced'] = array_map('wp_kses_post', array_filter($_POST['proxy_advanced']));
    }

    // Thêm xử lý status
    if (isset($_POST['proxy_status'])) {
        $proxy_data['status'] = array_map('wp_kses_post', array_filter($_POST['proxy_status']));
    }

    // Thêm xử lý price
    if (isset($_POST['proxy_price'])) {
        $proxy_data['price'] = floatval($_POST['proxy_price']);
    }
    if (isset($_POST['pricing_plan_month']) && is_array($_POST['pricing_plan_month'])) {
        $months = $_POST['pricing_plan_month'];
        $prices = isset($_POST['pricing_plan_price']) ? $_POST['pricing_plan_price'] : array();

        foreach ($months as $index => $month) {
            if (!empty($month) && isset($prices[$index]) && !empty($prices[$index])) {
                $proxy_data['pricing_plans'][] = array(
                    'month' => absint($month),  // Chuyển thành số nguyên dương
                    'price' => floatval($prices[$index])  // Chuyển thành số thực
                );
            }
        }
    }

    // =========================================================================
    // THU THẬP METABOX TRÊN: Features Overview Section (features_section)
    // =========================================================================
    if (isset($_POST['features_section_group_title']) && is_array($_POST['features_section_group_title'])) {
        $group_titles = $_POST['features_section_group_title'];

        foreach ($group_titles as $group_index => $group_title) {
            if (!empty($group_title)) {
                // Thu thập items cho group này
                $group_items = array();
                $item_titles_key = 'features_section_item_title_' . $group_index;
                $item_summaries_key = 'features_section_item_summary_' . $group_index;

                if (isset($_POST[$item_titles_key]) && is_array($_POST[$item_titles_key])) {
                    $item_titles = $_POST[$item_titles_key];
                    $item_summaries = isset($_POST[$item_summaries_key]) ? $_POST[$item_summaries_key] : array();

                    foreach ($item_titles as $item_index => $item_title) {
                        if (!empty($item_title)) {
                            $group_items[] = array(
                                'title' => wp_kses_post($item_title),
                                'summary' => isset($item_summaries[$item_index]) ? wp_kses_post($item_summaries[$item_index]) : ''
                            );
                        }
                    }
                }

                $proxy_data['features_section'][] = array(
                    'title' => wp_kses_post($group_title),
                    'items' => $group_items
                );
            }
        }
    }

    // Serialize dữ liệu và lưu vào 1 meta key
    update_post_meta($post_id, '_proxy_data', maybe_serialize($proxy_data));
}
add_action('save_post', 'save_proxy_home_info');

// ==================================== PROXY - DESCRIPTION=================

function proxy_desc_meta_box()
{
    add_meta_box(
        'proxy_desc',
        'Proxy Description',
        'proxy_desc_callback',
        'proxy',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'proxy_desc_meta_box');

//================ Render meta box HTML=======================
function proxy_desc_callback($post)
{
    wp_nonce_field('proxy_desc_nonce', 'proxy_desc_nonce_field');

    // Lấy dữ liệu 
    $saved_data = get_post_meta($post->ID, '_proxy_data', true);

    // Unserialize 
    if (!empty($saved_data) && is_string($saved_data)) {
        $saved_data = maybe_unserialize($saved_data);
    }

    // Lấy features_overview từ saved_data
    $features_overview = isset($saved_data['features_overview']) && is_array($saved_data['features_overview'])
        ? $saved_data['features_overview']
        : array();
    $perfect_for = isset($saved_data['perfect_for']) && is_array($saved_data['perfect_for']) ? $saved_data['perfect_for'] : array();


    ?>
    <style>
        .provider-meta-box {
            padding: 20px;
        }

        .provider-field {
            margin-bottom: 25px;
        }

        .provider-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .provider-field input[type="text"],
        .provider-field input[type="number"],
        .provider-field textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .provider-field textarea {
            min-height: 80px;
        }

        .feature-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
        }

        .feature-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .feature-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .feature-group-main-title {
            margin-bottom: 15px;
        }

        .feature-group-main-title input {
            width: 100%;
            padding: 10px;
            border: 2px solid #0073aa;
            border-radius: 6px;
            font-weight: 600;
        }

        .feature-items-list {
            margin-top: 15px;
            padding: 15px;
            background: #f0fdf4;
            border-radius: 6px;
        }

        .feature-item-group {
            border: 2px solid #0073aa;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 6px;
            background: white;
        }

        .feature-item-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .feature-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .desc-btn-add,
        .desc-btn-remove {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .desc-btn-add {
            background: #0073aa;
            color: white;
        }

        .desc-btn-add:hover {
            background: #005a87;
        }

        .desc-btn-remove {
            background: #dc3232;
            color: white;
            padding: 8px 12px;
        }

        .desc-btn-remove:hover {
            background: #a00;
        }

        .desc-section {
            margin-bottom: 30px;
        }

        .desc-section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0073aa;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
        }

        .rating-label {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        /* commit & achieve */

        .perfect-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
            position: relative;
        }

        .perfect-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .perfect-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .perfect-field {
            margin-bottom: 15px;
        }

        .perfect-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 13px;
            color: #555;
        }

        .perfect-field input,
        .perfect-field textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .perfect-field textarea {
            min-height: 80px;
        }

        .perfect-field textarea.icon-field {
            min-height: 120px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>

    <div class="provider-meta-box">

        <div class="desc-section">
            <div class="desc-section-title">Commitment and achievement</div>

            <div id="perfect-for-container">
                <?php
                if (!empty($perfect_for)) {
                    foreach ($perfect_for as $pf_index => $pf_item) {
                        $pf_title = isset($pf_item['title']) ? $pf_item['title'] : '';
                        $pf_icon = isset($pf_item['icon']) ? $pf_item['icon'] : '';
                        $pf_summary = isset($pf_item['summary']) ? $pf_item['summary'] : '';
                        ?>
                        <div class="perfect-group" data-perfect-index="<?php echo $pf_index; ?>">
                            <div class="perfect-group-header">
                                <span class="perfect-group-title">Use Case #<?php echo (int) $pf_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-perfect-group">✕</button>
                            </div>

                            <div class="perfect-field">
                                <label>Title</label>
                                <input type="text" name="perfect_for_title[]" value="<?php echo esc_attr($pf_title); ?>"
                                    placeholder="Web Scraping">
                            </div>

                            <div class="perfect-field">
                                <label>Icon Code (HTML/SVG)</label>
                                <textarea name="perfect_for_icon[]" class="icon-field"
                                    placeholder="<svg>...</svg> or HTML icon code"><?php echo esc_textarea($pf_icon); ?></textarea>
                            </div>

                            <div class="perfect-field">
                                <label>Summary</label>
                                <textarea name="perfect_for_summary[]"
                                    placeholder="Extract data from websites at scale without getting blocked..."><?php echo esc_textarea($pf_summary); ?></textarea>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="perfect-group" data-perfect-index="0">
                        <div class="perfect-group-header">
                            <span class="perfect-group-title">Use Case #1</span>
                            <button type="button" class="desc-btn-remove remove-perfect-group">✕</button>
                        </div>

                        <div class="perfect-field">
                            <label>Title</label>
                            <input type="text" name="perfect_for_title[]" value="" placeholder="Web Scraping">
                        </div>

                        <div class="perfect-field">
                            <label>Icon Code (HTML/SVG)</label>
                            <textarea name="perfect_for_icon[]" class="icon-field"
                                placeholder="<svg>...</svg> or HTML icon code"></textarea>
                        </div>

                        <div class="perfect-field">
                            <label>Summary</label>
                            <textarea name="perfect_for_summary[]"
                                placeholder="Extract data from websites at scale without getting blocked..."></textarea>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-perfect-group">ADD</button>
        </div>

        <div class="desc-section">
            <div class="desc-section-title">Proxy Details</div>

            <div id="features-overview-container">
                <?php
                if (!empty($features_overview)) {
                    foreach ($features_overview as $group_index => $feature_group) {
                        $group_title = isset($feature_group['title']) ? $feature_group['title'] : '';
                        $group_items = isset($feature_group['items']) && is_array($feature_group['items']) ? $feature_group['items'] : array();
                        ?>
                        <div class="feature-group" data-group-index="<?php echo $group_index; ?>">
                            <div class="feature-group-header">
                                <span class="feature-group-title">Details Group #<?php echo $group_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-feature-group">✕</button>
                            </div>

                            <div class="feature-group-main-title">
                                <label class="rating-label">Details Title</label>
                                <input type="text" name="feature_group_title[]" value="<?php echo esc_attr($group_title); ?>"
                                    placeholder="Proxy Types">
                            </div>

                            <div class="feature-items-list">
                                <label class="rating-label">Items in this Group</label>
                                <div class="feature-items-container">
                                    <?php
                                    if (!empty($group_items)) {
                                        foreach ($group_items as $item_index => $item) {
                                            $item_title = isset($item['title']) ? $item['title'] : '';
                                            ?>
                                            <div class="feature-item-group">
                                                <div class="feature-item-header">
                                                    <label class="rating-label" style="margin: 0;">Item
                                                        #<?php echo $item_index + 1; ?></label>
                                                    <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                                </div>
                                                <input type="text" name="feature_item_title_<?php echo $group_index; ?>[]"
                                                    value="<?php echo esc_attr($item_title); ?>" placeholder="Residential Proxies">
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="feature-item-group">
                                            <div class="feature-item-header">
                                                <label class="rating-label" style="margin: 0;">Item #1</label>
                                                <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                            </div>
                                            <input type="text" name="feature_item_title_<?php echo $group_index; ?>[]" value=""
                                                placeholder="Residential Proxies">
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="feature-group" data-group-index="0">
                        <div class="feature-group-header">
                            <span class="feature-group-title">Details Group #1</span>
                            <button type="button" class="desc-btn-remove remove-feature-group">✕</button>
                        </div>

                        <div class="feature-group-main-title">
                            <label class="rating-label">Group Title</label>
                            <input type="text" name="feature_group_title[]" value="" placeholder="Proxy Types">
                        </div>

                        <div class="feature-items-list">
                            <label class="rating-label">Items in this Group</label>
                            <div class="feature-items-container">
                                <div class="feature-item-group">
                                    <div class="feature-item-header">
                                        <label class="rating-label" style="margin: 0;">Item #1</label>
                                        <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                    </div>
                                    <input type="text" name="feature_item_title_0[]" value="" placeholder="Residential Proxies">
                                </div>
                            </div>
                            <button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-feature-group">ADD</button>
        </div>

    </div>

    <script>
        jQuery(document).ready(function ($) {
            // ===================================================================
            // === METABOX: Proxy Details - CHỈ XỬ LÝ METABOX NÀY ===
            // ===================================================================

            // Tìm container của Proxy Details metabox
            var $container = $('#features-overview-container');
            if (!$container.length) return;

            // Lấy parent metabox để scope events
            var $metabox = $container.closest('.desc-section');

            // === ADD FEATURE GROUP ===
            $metabox.on('click', '.add-feature-group', function (e) {
                e.stopImmediatePropagation();
                e.preventDefault();

                var count = $container.find('.feature-group').length;
                var html = '<div class="feature-group" data-group-index="' + count + '">' +
                    '<div class="feature-group-header">' +
                    '<span class="feature-group-title">Details Group #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-feature-group">✕</button>' +
                    '</div>' +
                    '<div class="feature-group-main-title">' +
                    '<label class="rating-label">Details Title</label>' +
                    '<input type="text" name="feature_group_title[]" value="" placeholder="Proxy Types">' +
                    '</div>' +
                    '<div class="feature-items-list">' +
                    '<label class="rating-label">Items in this Group</label>' +
                    '<div class="feature-items-container">' +
                    '<div class="feature-item-group">' +
                    '<div class="feature-item-header">' +
                    '<label class="rating-label" style="margin: 0;">Item #1</label>' +
                    '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
                    '</div>' +
                    '<input type="text" name="feature_item_title_' + count + '[]" value="" placeholder="Residential Proxies">' +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD ITEM</button>' +
                    '</div>' +
                    '</div>';
                $container.append(html);
                return false;
            });

            // === REMOVE FEATURE GROUP ===
            $metabox.on('click', '.remove-feature-group', function (e) {
                e.stopImmediatePropagation();
                if ($container.find('.feature-group').length > 1) {
                    $(this).closest('.feature-group').remove();
                    updateFeatureGroupNumbers();
                } else {
                    alert('Phải có ít nhất 1 feature group!');
                }
                return false;
            });

            // === ADD FEATURE ITEM ===
            $metabox.on('click', '.add-feature-item', function (e) {
                e.stopImmediatePropagation();
                e.preventDefault();

                var featureGroup = $(this).closest('.feature-group');
                var groupIndex = featureGroup.attr('data-group-index');
                var itemsContainer = featureGroup.find('.feature-items-container');
                var itemCount = itemsContainer.find('.feature-item-group').length + 1;

                var html = '<div class="feature-item-group">' +
                    '<div class="feature-item-header">' +
                    '<label class="rating-label" style="margin: 0;">Item #' + itemCount + '</label>' +
                    '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
                    '</div>' +
                    '<input type="text" name="feature_item_title_' + groupIndex + '[]" value="" placeholder="Residential Proxies">' +
                    '</div>';
                itemsContainer.append(html);
                return false;
            });

            // === REMOVE FEATURE ITEM ===
            $metabox.on('click', '.remove-feature-item', function (e) {
                e.stopImmediatePropagation();
                var itemsContainer = $(this).closest('.feature-items-container');
                if (itemsContainer.find('.feature-item-group').length > 1) {
                    $(this).closest('.feature-item-group').remove();
                    updateFeatureItemNumbers(itemsContainer);
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
                return false;
            });

            // === UPDATE FEATURE GROUP NUMBERS ===
            function updateFeatureGroupNumbers() {
                $container.find('.feature-group').each(function (index) {
                    $(this).attr('data-group-index', index);
                    $(this).find('.feature-group-title').text('Details Group #' + (index + 1));

                    // Update input names for items in this group
                    $(this).find('.feature-items-container input').each(function () {
                        var name = $(this).attr('name');
                        name = name.replace(/feature_item_title_\d+\[\]/, 'feature_item_title_' + index + '[]');
                        $(this).attr('name', name);
                    });
                });
            }

            // === UPDATE FEATURE ITEM NUMBERS ===
            function updateFeatureItemNumbers(container) {
                container.find('.feature-item-group').each(function (index) {
                    $(this).find('.feature-item-header label').text('Item #' + (index + 1));
                });
            }

            // === PERFECT FOR SECTION ===
            function updatePerfectForNumbers() {
                $('#perfect-for-container .perfect-group').each(function (index) {
                    $(this).attr('data-perfect-index', index);
                    $(this).find('.perfect-group-title').text('Use Case #' + (index + 1));
                });
            }

            // Add Perfect For Group
            $('.add-perfect-group').on('click', function () {
                var count = $('#perfect-for-container .perfect-group').length;
                var html = '<div class="perfect-group" data-perfect-index="' + count + '">' +
                    '<div class="perfect-group-header">' +
                    '<span class="perfect-group-title">Use Case #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-perfect-group">✕</button>' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Title</label>' +
                    '<input type="text" name="perfect_for_title[]" value="" placeholder="Web Scraping">' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Icon Code (HTML/SVG)</label>' +
                    '<textarea name="perfect_for_icon[]" class="icon-field" placeholder="<svg>...</svg> or HTML icon code"></textarea>' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Summary</label>' +
                    '<textarea name="perfect_for_summary[]" placeholder="Extract data from websites at scale..."></textarea>' +
                    '</div>' +
                    '</div>';
                $('#perfect-for-container').append(html);
            });

            // Remove Perfect For Group
            $(document).on('click', '.remove-perfect-group', function () {
                if ($('#perfect-for-container .perfect-group').length > 1) {
                    $(this).closest('.perfect-group').remove();
                    updatePerfectForNumbers();
                } else {
                    alert('Phải có ít nhất 1 use case!');
                }
            });

            // Initialize
            updateFeatureGroupNumbers();
        });
    </script>

    <?php
}

// Lưu dữ liệu
function save_proxy_desc($post_id)
{
    // check nonce
    if (
        !isset($_POST['proxy_desc_nonce_field']) ||
        !wp_verify_nonce($_POST['proxy_desc_nonce_field'], 'proxy_desc_nonce')
    ) {
        return;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // phân quyền
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // get _proxy_data 
    $existing_data = get_post_meta($post_id, '_proxy_data', true);
    $existing_data = maybe_unserialize($existing_data);

    // array()
    if (!is_array($existing_data)) {
        $existing_data = array();
    }

    $features_overview = array();
    $perfect_for = array();

    // ============ Feature Groups ============
    if (isset($_POST['feature_group_title']) && is_array($_POST['feature_group_title'])) {
        $group_titles = $_POST['feature_group_title'];

        foreach ($group_titles as $group_index => $group_title) {
            $feature_group = array(
                'title' => sanitize_text_field($group_title),
                'items' => array()
            );

            // items
            $items_field_name = 'feature_item_title_' . $group_index;
            if (isset($_POST[$items_field_name]) && is_array($_POST[$items_field_name])) {
                foreach ($_POST[$items_field_name] as $item_title) {
                    if (!empty(trim($item_title))) {
                        $feature_group['items'][] = array(
                            'title' => sanitize_text_field($item_title)
                        );
                    }
                }
            }

            // add group
            if (!empty($feature_group['items'])) {
                $features_overview[] = $feature_group;
            }
        }
    }

    // ============ Perfect For (Use Cases) ============
    if (isset($_POST['perfect_for_title']) && is_array($_POST['perfect_for_title'])) {
        $pf_titles = $_POST['perfect_for_title'];
        $pf_icons = isset($_POST['perfect_for_icon']) ? $_POST['perfect_for_icon'] : array();
        $pf_summaries = isset($_POST['perfect_for_summary']) ? $_POST['perfect_for_summary'] : array();

        foreach ($pf_titles as $pf_index => $pf_title) {
            // Trim title
            $pf_title = trim($pf_title);

            if (!empty($pf_title)) {
                $perfect_for[] = array(
                    'title' => sanitize_text_field($pf_title),
                    'icon' => isset($pf_icons[$pf_index]) ? wp_kses_post($pf_icons[$pf_index]) : '',
                    'summary' => isset($pf_summaries[$pf_index]) ? wp_kses_post($pf_summaries[$pf_index]) : ''
                );
            }
        }
    }

    // ============ Merge ============
    $existing_data['features_overview'] = $features_overview;
    $existing_data['perfect_for'] = $perfect_for;

    //  save database
    update_post_meta($post_id, '_proxy_data', maybe_serialize($existing_data));
}
add_action('save_post', 'save_proxy_desc');

// ==================== REST API ====================
function add_proxy_data_to_rest_api()
{
    register_rest_field('proxy', 'proxy_data', array(
        'get_callback' => 'get_proxy_data_for_api',
        'update_callback' => null,
        'schema' => array(
            'description' => 'Proxy data (unserialized)',
            'type' => 'object'
        )
    ));
}
add_action('rest_api_init', 'add_proxy_data_to_rest_api');

// Callback 
function get_proxy_data_for_api($object)
{
    $proxy_id = $object['id'];

    // meta key '_proxy_data'
    $proxy_data = get_post_meta($proxy_id, '_proxy_data', true);


    if (!empty($proxy_data) && is_string($proxy_data)) {
        $proxy_data = maybe_unserialize($proxy_data);
    }

    // proxy_content
    $proxy_content = get_post_meta($proxy_id, '_proxy_content', true);


    if (is_array($proxy_data)) {
        return array(
            // Home Info fields
            'tags' => isset($proxy_data['tags']) ? $proxy_data['tags'] : array(),
            'logo' => isset($proxy_data['logo']) ? $proxy_data['logo'] : '',
            'summary' => isset($proxy_data['summary']) ? $proxy_data['summary'] : '',
            'rating' => isset($proxy_data['rating']) ? floatval($proxy_data['rating']) : 0,
            'advanced' => isset($proxy_data['advanced']) ? $proxy_data['advanced'] : array(),
            'status' => isset($proxy_data['status']) ? $proxy_data['status'] : array(),
            'price' => isset($proxy_data['price']) ? floatval($proxy_data['price']) : 0,
            'features_section' => isset($proxy_data['features_section']) ? $proxy_data['features_section'] : array(),
            'pricing_plans' => isset($proxy_data['pricing_plans']) ? $proxy_data['pricing_plans'] : array(),

            // Description fields
            'features_overview' => isset($proxy_data['features_overview']) ? $proxy_data['features_overview'] : array(),
            'perfect_for' => isset($proxy_data['perfect_for']) ? $proxy_data['perfect_for'] : array(),

            // Main Content field
            'content' => !empty($proxy_content) ? $proxy_content : ''
        );
    }

    // Return default structure
    return array(
        // Home Info defaults
        'tags' => array(),
        'logo' => '',
        'summary' => '',
        'rating' => 0,
        'advanced' => array(),
        'status' => array(),
        'price' => 0,
        'pricing_plans' => array(),
        'features_section' => array(),

        // Description defaults
        'features_overview' => array(),
        'perfect_for' => array(),


        // Main Content default
        'content' => !empty($proxy_content) ? $proxy_content : ''
    );
}
