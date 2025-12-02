<?php

// ==================================== CPT provider =================================
function create_provider_cpt()
{
    $labels = array(
        'name' => 'Provider',
        'singular_name' => 'Provider',
        'menu_name' => 'Provider',
        'all_items' => 'All Provider',
        'add_new_item' => 'Add New Provider',
        'edit_item' => 'Edit Provider'
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

    register_post_type('provider', $args);
}
add_action('init', 'create_provider_cpt');

// ==================== METABOX - MAIN CONTENT ====================
function add_provider_content_metabox()
{
    add_meta_box(
        'provider_content_box',
        'Provider Main Content',
        'render_provider_content_metabox',
        'provider',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_provider_content_metabox');

// hiển thị trình soạn thảo
function render_provider_content_metabox($post)
{
    // Lấy dữ liệu đã lưu
    $desc = get_post_meta($post->ID, '_provider_content', true);

    wp_editor(
        $desc,
        'provider_content',
        array(
            'textarea_name' => 'provider_content',
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true,
        )
    );
}

function save_provider_content_metabox($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (isset($_POST['provider_content'])) {
        update_post_meta($post_id, '_provider_content', wp_kses_post($_POST['provider_content']));
    }
}
add_action('save_post', 'save_provider_content_metabox');

// ====================================== provider category taxonomy =============================
function create_provider_category_taxonomy()
{
    $labels = array(
        'name' => 'Provider Categories',
        'singular_name' => 'Provider Category',
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
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'provider-category'),
    );

    register_taxonomy('provider_category', array('provider'), $args);
}
add_action('init', 'create_provider_category_taxonomy');

// ========================== metabox home info provider ============================
// ======================================== provider home info =================================================

// Register meta box
function provider_description_meta_box()
{
    add_meta_box(
        'provider_description',
        'provider Summary (HomePage)',
        'provider_home_info_callback',
        'provider',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'provider_description_meta_box');

//================ Render meta box HTML=======================
function provider_home_info_callback($post)
{
    wp_nonce_field('provider_home_info_nonce', 'provider_home_info_nonce_field');

    // Lấy dữ liệu (unserialize)
    $saved_data = get_post_meta($post->ID, '_provider_data', true);

    if (!empty($saved_data) && is_string($saved_data)) {
        $saved_data = maybe_unserialize($saved_data);
    }

    // Set default values
    $tags = isset($saved_data['tags']) && is_array($saved_data['tags']) ? $saved_data['tags'] : array();
    $logo = isset($saved_data['logo']) ? $saved_data['logo'] : '';
    $summary = isset($saved_data['summary']) ? $saved_data['summary'] : '';
    // Lấy features_overview từ saved_data
    $features_overview = isset($saved_data['features_overview']) && is_array($saved_data['features_overview'])
        ? $saved_data['features_overview']
        : array();
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

    <!-- Tags -->
    <div class="provider-field">
        <label>Tags</label>
        <div id="tags-container">
            <?php
                if (!empty($tags)) {
                    foreach ($tags as $index => $tag) {
                        echo '<div class="repeatable-item">
                                <input type="text" name="provider_tags[]" value="' . esc_attr($tag) . '" placeholder="Nhập tag (vd: Best Overall)">
                                <button type="button" class="btn-remove remove-tag">✕</button>
                              </div>';
                    }
                } else {
                    echo '<div class="repeatable-item">
                            <input type="text" name="provider_tags[]" value="" placeholder="Best Overall">
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
        <input type="text" name="provider_logo" id="provider_logo" value="<?php echo esc_attr($logo); ?>"
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
        <textarea name="provider_summary"
            placeholder="Premium residential proxies with 100M+ IP pool"><?php echo esc_textarea($summary); ?></textarea>
    </div>

    <div class="desc-section">
        <div class="desc-section-title">Provider Details</div>

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
                        placeholder="provider Types">
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
                    <input type="text" name="feature_group_title[]" value="" placeholder="provider Types">
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
jQuery(document).ready(function($) {
    // Preview logo
    $('#provider_logo').on('input', function() {
        var logoUrl = $(this).val();
        if (logoUrl) {
            $('#logo-preview img').attr('src', logoUrl);
            $('#logo-preview').show();
        } else {
            $('#logo-preview').hide();
        }
    });

    // Add Tag
    $('.add-tag').on('click', function() {
        var html = '<div class="repeatable-item">' +
            '<input type="text" name="provider_tags[]" value="" placeholder="Nhập tag">' +
            '<button type="button" class="btn-remove remove-tag">✕</button>' +
            '</div>';
        $('#tags-container').append(html);
    });

    // Remove Tag
    $(document).on('click', '.remove-tag', function() {
        if ($('#tags-container .repeatable-item').length > 1) {
            $(this).closest('.repeatable-item').remove();
        } else {
            alert('Phải có ít nhất 1 tag!');
        }
    });

    // Add Advanced Feature
    $('.add-advanced').on('click', function() {
        var html = '<div class="repeatable-item">' +
            '<input type="text" name="provider_advanced[]" value="" placeholder="Nhập feature">' +
            '<button type="button" class="btn-remove remove-advanced">✕</button>' +
            '</div>';
        $('#advanced-container').append(html);
    });

    // Remove Advanced Feature
    $(document).on('click', '.remove-advanced', function() {
        if ($('#advanced-container .repeatable-item').length > 1) {
            $(this).closest('.repeatable-item').remove();
        } else {
            alert('Phải có ít nhất 1 feature!');
        }
    });

    // Add Status 
    $('.add-status').on('click', function() {
        var html = '<div class="repeatable-item">' +
            '<input type="text" name="provider_status[]" value="" placeholder="Nhập status">' +
            '<button type="button" class="btn-remove remove-status">✕</button>' +
            '</div>';
        $('#status-container').append(html);
    });

    // Remove Status 
    $(document).on('click', '.remove-status', function() {
        if ($('#status-container .repeatable-item').length > 1) {
            $(this).closest('.repeatable-item').remove();
        } else {
            alert('Phải có ít nhất 1 status!');
        }
    });

    // Add Feature Group
    $('.add-feature-group').on('click', function() {
        var count = $('#features-overview-container .feature-group').length;
        var html = '<div class="feature-group" data-group-index="' + count + '">' +
            '<div class="feature-group-header">' +
            '<span class="feature-group-title">Details Group #' + (count + 1) + '</span>' +
            '<button type="button" class="desc-btn-remove remove-feature-group">✕</button>' +
            '</div>' +
            '<div class="feature-group-main-title">' +
            '<label class="rating-label">Group Title</label>' +
            '<input type="text" name="feature_group_title[]" value="" placeholder="provider Types">' +
            '</div>' +
            '<div class="feature-items-list">' +
            '<label class="rating-label">Items in this Group</label>' +
            '<div class="feature-items-container">' +
            '<div class="feature-item-group">' +
            '<div class="feature-item-header">' +
            '<label class="rating-label" style="margin: 0;">Item #1</label>' +
            '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
            '</div>' +
            '<input type="text" name="feature_item_title_' + count +
            '[]" value="" placeholder="Residential Proxies">' +
            '</div>' +
            '</div>' +
            '<button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD ITEM</button>' +
            '</div>' +
            '</div>';
        $('#features-overview-container').append(html);
    });

    // Remove Feature Group
    $(document).on('click', '.remove-feature-group', function() {
        if ($('#features-overview-container .feature-group').length > 1) {
            $(this).closest('.feature-group').remove();
            updateFeatureGroupNumbers();
        } else {
            alert('Phải có ít nhất 1 feature group!');
        }
    });

    // Add Feature Item
    $(document).on('click', '.add-feature-item', function() {
        var featureGroup = $(this).closest('.feature-group');
        var groupIndex = featureGroup.attr('data-group-index');
        var itemsContainer = featureGroup.find('.feature-items-container');
        var itemCount = itemsContainer.find('.feature-item-group').length + 1;

        var html = '<div class="feature-item-group">' +
            '<div class="feature-item-header">' +
            '<label class="rating-label" style="margin: 0;">Item #' + itemCount + '</label>' +
            '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
            '</div>' +
            '<input type="text" name="feature_item_title_' + groupIndex +
            '[]" value="" placeholder="Residential Proxies">' +
            '</div>';
        itemsContainer.append(html);
    });

    // Remove Feature Item
    $(document).on('click', '.remove-feature-item', function() {
        var itemsContainer = $(this).closest('.feature-items-container');
        if (itemsContainer.find('.feature-item-group').length > 1) {
            $(this).closest('.feature-item-group').remove();
            updateFeatureItemNumbers(itemsContainer);
        } else {
            alert('Phải có ít nhất 1 item!');
        }
    });

    // Update Feature Group Numbers
    function updateFeatureGroupNumbers() {
        $('#features-overview-container .feature-group').each(function(index) {
            $(this).attr('data-group-index', index);
            $(this).find('.feature-group-title').text('Details Group #' + (index + 1));

            // Update input names for items in this group
            $(this).find('.feature-items-container input').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/feature_item_title_\d+\[\]/, 'feature_item_title_' +
                    index + '[]');
                $(this).attr('name', name);
            });
        });
    }

    // Update Feature Item Numbers
    function updateFeatureItemNumbers(container) {
        container.find('.feature-item-group').each(function(index) {
            $(this).find('.feature-item-header label').text('Item #' + (index + 1));
        });
    }

    // update perfect for groups number
    function updatePerfectForNumbers() {
        $('#perfect-for-container .perfect-group').each(function(index) {
            $(this).attr('data-perfect-index', index);
            $(this).find('.perfect-group-title').text('Use Case #' + (index + 1));
        });
    }


});
</script>

<?php
}

// Lưu dữ liệu
function save_provider_home_info($post_id)
{
    // nonce
    if (
        !isset($_POST['provider_home_info_nonce_field']) ||
        !wp_verify_nonce($_POST['provider_home_info_nonce_field'], 'provider_home_info_nonce')
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
    $provider_data = array(
        'tags' => array(),
        'logo' => '',
        'summary' => '',
        'features_overview' => array()
    );

    // Thu thập dữ liệu từ form
    if (isset($_POST['provider_tags'])) {
        $provider_data['tags'] = array_map('wp_kses_post', array_filter($_POST['provider_tags']));
    }

    if (isset($_POST['provider_logo'])) {
        $provider_data['logo'] = esc_url_raw($_POST['provider_logo']);
    }

    if (isset($_POST['provider_summary'])) {
        $provider_data['summary'] = wp_kses_post($_POST['provider_summary']);
    }

    // ============ Feature Groups ============ 
    if (isset($_POST['feature_group_title']) && is_array($_POST['feature_group_title'])) {

        $provider_data['features_overview'] = array(); // luôn reset trước

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
                    $item_title = trim($item_title);
                    if ($item_title !== '') {
                        $feature_group['items'][] = array(
                            'title' => sanitize_text_field($item_title)
                        );
                    }
                }
            }

            // chỉ add nếu group còn tồn tại
            if (!empty($feature_group['title']) || !empty($feature_group['items'])) {
                $provider_data['features_overview'][] = $feature_group;
            }
        }
    }


    // Serialize dữ liệu và lưu vào 1 meta key
    update_post_meta($post_id, '_provider_data', maybe_serialize($provider_data));
}
add_action('save_post', 'save_provider_home_info');



// ==================== REST API ====================
function add_provider_data_to_rest_api()
{
    register_rest_field('provider', 'provider_data', array(
        'get_callback' => 'get_provider_data_for_api',
        'update_callback' => null,
        'schema' => array(
            'description' => 'provider data (unserialized)',
            'type' => 'object'
        )
    ));
}
add_action('rest_api_init', 'add_provider_data_to_rest_api');

// Callback 
function get_provider_data_for_api($object)
{
    $provider_id = $object['id'];

    // meta key '_provider_data'
    $provider_data = get_post_meta($provider_id, '_provider_data', true);


    if (!empty($provider_data) && is_string($provider_data)) {
        $provider_data = maybe_unserialize($provider_data);
    }

    // provider_content
    $provider_content = get_post_meta($provider_id, '_provider_content', true);


    if (is_array($provider_data)) {
        return array(
            // Home Info fields
            'tags' => isset($provider_data['tags']) ? $provider_data['tags'] : array(),
            'logo' => isset($provider_data['logo']) ? $provider_data['logo'] : '',
            'summary' => isset($provider_data['summary']) ? $provider_data['summary'] : '',

            // Description fields
            'features_overview' => isset($provider_data['features_overview']) ? $provider_data['features_overview'] : array(),

            // Main Content field
            'content' => !empty($provider_content) ? $provider_content : ''
        );
    }

    // Return default structure
    return array(
        // Home Info defaults
        'tags' => array(),
        'logo' => '',
        'summary' => '',

        // Description defaults
        'features_overview' => array(),

        // Main Content default
        'content' => !empty($provider_content) ? $provider_content : ''
    );
}