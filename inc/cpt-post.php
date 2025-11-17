<?php 
// =============================== CPT POST ============================

function create_post_cpt()
{
    $labels = array(
        'name' => 'CPT_Post',
        'singular_name' => 'CPT_Post',
        'menu_name' => 'CPT_Post',
        'all_items' => 'All Post',
        'add_new_item' => 'Add New Post',
        'edit_item' => 'Edit Post'
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

    register_post_type('cpt_post', $args);
}
add_action('init', 'create_post_cpt');

// =========================== post category taxonomy =========================
function create_post_category_taxonomy(){
    $labels = array(
        'name' => 'Post Categories',
        'singular_name' =>'Post Categories',
        'menu_name'=>'Categories',
        'all_items'=>'All Categories',
        'edit_item' => 'Edit Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'search_items' => 'Search Categories',
        'popular_items'=> 'Popular Categories',
        'separate_items_with_commas' => 'Separate Categories with commas',
        'add_or_remove_items' => 'Add or remove categories',
        'choose_from_most_used' =>'Choose from the most used categories',
        'not_found'=>'No Categories found.'
    );

    $args = array(
        'labels'=> $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column'=>true,
        'show_in_rest'=>true,
        'rewrite' => array('slug'=>'post-category'),
    );

    register_taxonomy('post_category',array('cpt_post'),$args);

}
add_action('init','create_post_category_taxonomy');


// ==================== METABOX - MAIN CONTENT ====================
function add_post_content_metabox()
{
    add_meta_box(
        'post_content_box',             // ID
        'post Main Content',          // Tiêu đề box
        'render_post_content_metabox',  // Callback 
        'cpt_post',                     // CPT
        'normal',                    // Vị trí
        'high'                       // Ưu tiên
    );
}
add_action('add_meta_boxes', 'add_post_content_metabox');

// hiển thị trình soạn thảo
function render_post_content_metabox($post)
{
    // Lấy dữ liệu đã lưu
    $desc = get_post_meta($post->ID, '_post_content', true);

    wp_editor(
        $desc,
        'post_content',
        array(
            'textarea_name' => 'post_content',
            'media_buttons' => true, //chèn ảnh
            'textarea_rows' => 10,
            'teeny' => false, //toolbar
            'quicktags' => true, // HTML nhanh
        )
    );
}

function save_post_content_metabox($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (isset($_POST['post_content'])) {
        update_post_meta($post_id, '_post_content', wp_kses_post($_POST['post_content']));
    }
}
add_action('save_post', 'save_post_content_metabox');

// ================= metabox description ============================
// Register meta box
function post_description_meta_box()
{
    add_meta_box(
        'post_description',
        'Post Description',
        'post_description_callback', // callback
        'cpt_post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'post_description_meta_box');

function post_description_callback($post){
    wp_nonce_field('post_description_nonce','post_description_nonce_field');

    $saved_data = get_post_meta($post->ID,'_post_data',true);

    if(!empty($saved_data) && is_string($saved_data)){
        $saved_data = maybe_unserialize($saved_data);
    }

    // set default values
    $tags = isset($saved_data['tags']) && is_array($saved_data['tags']) ? $saved_data['tags'] : array();
    $thumbnail = isset($saved_data['thumbnail']) ? $saved_data['thumbnail'] : '';
    $summary = isset($saved_data['summary']) ? $saved_data['summary'] : '';
    $date = isset($saved_data['date']) ? $saved_data['date'] : '';

    ?>
    <!-- ==================================css=========================== -->
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

        <!-- thumbnails -->
        <div class="provider-field">
            <label>Thumbnail URL</label>
            <input type="text" name="proxy_thumbnail" id="proxy_thumbnail" value="<?php echo esc_attr($thumbnail); ?>"
                placeholder="https://example.com/thumbnail.png">
            <?php if (!empty($thumbnail)): ?>
                <div id="logo-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($thumbnail); ?>"
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

       <!-- Summary -->
        <div class="provider-field">
            <label>Date</label>
            <textarea name="proxy_date"
                placeholder="dd/mm/yyyy"><?php echo esc_textarea($date); ?></textarea>
        </div>
        
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Add Tag
            $('.add-tag').on('click', function () {
                var html = '<div class="repeatable-item">' +
                    '<input type="text" name="proxy_tags[]" value="" placeholder="Nhập tag">' +
                    '<button type="button" class="btn-remove remove-tag">✕</button>' +
                    '</div>';
                $('#tags-container').append(html);
            });

            // Remove Tag
            $(document).on('click', '.remove-tag', function () {
                if ($('#tags-container .repeatable-item').length > 1) {
                    $(this).closest('.repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 tag!');
                }
            });


        });
    </script>
    <?php
}

// luu du lieu 
function save_post_description($post_id){
    // nonce
    if (
        !isset($_POST['post_description_nonce_field']) ||
        !wp_verify_nonce($_POST['post_description_nonce_field'], 'post_description_nonce')
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
    $post_data = array(
        'tags' => array(),
        'thumbnail' => '',
        'summary' => '',
        'date' => ''
    );

    // Thu thập dữ liệu từ form
    if (isset($_POST['proxy_tags'])) {
        $post_data['tags'] = array_map('wp_kses_post', array_filter($_POST['proxy_tags']));
    }

    if (isset($_POST['proxy_thumbnail'])) {
        $post_data['thumbnail'] = esc_url_raw($_POST['proxy_thumbnail']);
    }

    if (isset($_POST['proxy_summary'])) {
        $post_data['summary'] = wp_kses_post($_POST['proxy_summary']);
    }

      if (isset($_POST['proxy_date'])) {
        $post_data['date'] = wp_kses_post($_POST['proxy_date']);
    }

    update_post_meta($post_id, '_post_data', maybe_serialize($post_data));
}
add_action('save_post','save_post_description');

// ========================= rest api ==============================
// ==================== REST API ====================
function add_post_data_to_rest_api()
{
    register_rest_field('cpt_post', 'post_data', array(
        'get_callback' => 'get_post_data_for_api',
        'update_callback' => null,
        'schema' => array(
            'description' => 'post data (unserialized)',
            'type' => 'object'
        )
    ));
}
add_action('rest_api_init', 'add_post_data_to_rest_api');

// Callback 
function get_post_data_for_api($object)
{
    $post_id = $object['id'];

    // meta key '_post_data'
    $post_data = get_post_meta($post_id, '_post_data', true);


    if (!empty($post_data) && is_string($post_data)) {
        $post_data = maybe_unserialize($post_data);
    }
    
    // post_content
    $post_content = get_post_meta($post_id, '_post_content', true);


    if (is_array($post_data)) {
        return array(
            // Home Info fields
            'tags' => isset($post_data['tags']) ? $post_data['tags'] : array(),
            'thumbnail' => isset($post_data['thumbnail']) ? $post_data['thumbnail'] : '',
            'summary' => isset($post_data['summary']) ? $post_data['summary'] : '',
            'date' => isset($post_data['date']) ? $post_data['date'] : '',
            
            // Main Content field
            'content' => !empty($post_content) ? $post_content : ''
        );
    }
    // Return default structure
    return array(
        // Home Info defaults
        'tags' => array(),
        'thumbnail' => '',
        'summary' => '',
        'date' => '',

        // Main Content default
        'content' => !empty($post_content) ? $post_content : ''
    );
}





