jQuery(document).ready(function($) {
    console.log('=== Product Variations Script Loaded ===');

    const $form = $('.variations_form');

    if (!$form.length) {
        console.log('No variation form found');
        return;
    }

    // Save const
    const rawVariations = $form.data('product_variations') || [];
    const productData = {
        variations: rawVariations,
        selectedAttributes: {}
    };

    console.log('Total variations:', productData.variations.length);
    
    const allAttributeNames = [];
    $('.attribute-selector').each(function() {
        allAttributeNames.push($(this).attr('name'));
    });
    
    if (allAttributeNames.length === 0) {
        console.warn('Could not detect product attribute selectors (.attribute-selector).');
    }

    // Xử lý click các nút
    $('.quantity-btn-proxy, .duration-btn').on('click', function(e) {
        e.preventDefault();

        const $btn = $(this);
        const attribute = $btn.data('attribute'); 
        const value = $btn.data('value');

        console.log('=== Button Clicked ===');
        console.log('Attribute:', attribute);
        console.log('Value:', value);

        // Toggle active class
        $btn.siblings().removeClass('active');
        $btn.addClass('active');

        // Tên input ẩn mà WooCommerce sử dụng
        const inputName = `attribute_${attribute}`;
        const $hiddenInput = $(`input[name="${inputName}"]`);

        // Update hidden input
        $hiddenInput.val(value);

        // Lưu attribute vào selectedAttributes
        productData.selectedAttributes[inputName] = value;

        console.log('Current selections:', productData.selectedAttributes);

        // Update giá
        updatePrice();
    });

    function updatePrice() {
        const selected = productData.selectedAttributes;
        const selectedCount = Object.keys(selected).length;

        console.log('=== Updating Price ===');
        console.log('Selected attributes:', selected);

        // Kiểm tra xem đã chọn đủ tất cả các thuộc tính hay chưa
        if (selectedCount !== allAttributeNames.length) {
            $('#variation-price-display').text('Vui lòng chọn đủ gói');
            $('.variation_id').val(0);
            $('.btn-primary-proxy, .btn-secondary-proxy').prop('disabled', true);
            return;
        }

        // Tìm variation khớp
        let matchedVariation = null;

        for (let i = 0; i < productData.variations.length; i++) {
            const variation = productData.variations[i];
            const varAttrs = variation.attributes;

            let isMatch = true;

            for (let attrKey in selected) {
                const selectedValue = selected[attrKey];
                const variationValue = varAttrs[attrKey];
                
                if (variationValue !== '' && variationValue !== selectedValue) {
                    isMatch = false;
                    break;
                }
            }

            if (isMatch) {
                matchedVariation = variation;
                console.log('✓ MATCHED variation:', variation);
                break;
            }
        }

        if (matchedVariation && matchedVariation.is_in_stock) {
            const priceHtml = matchedVariation.price_html || formatPrice(matchedVariation.display_price);

            $('#variation-price-display').html(priceHtml);
            $('.variation_id').val(matchedVariation.variation_id);
            $('.btn-primary-proxy, .btn-secondary-proxy').prop('disabled', false);

            console.log('✓ Price updated successfully!');
        } else {
            console.log('✗ No matching variation or out of stock');
            $('#variation-price-display').text('Không có sẵn');
            $('.variation_id').val(0);
            $('.btn-primary-proxy, .btn-secondary-proxy').prop('disabled', true);
        }
    }
    
    function formatPrice(price) {
        if (price === undefined) return 'N/A';
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    }

    // ==========================================
    // XỬ LÝ NÚT "MUA NGAY" (Submit form)
    // ==========================================
    $('.btn-primary-proxy.single_add_to_cart_button').on('click', function(e) {
        e.preventDefault();
        
        const variationId = $('.variation_id').val();
        const productId = $('input[name="product_id"]').val();

        if (!variationId || variationId === '0') {
            alert('Vui lòng chọn đầy đủ Kế hoạch và Thời hạn!');
            return false;
        }

        console.log('Mua ngay clicked - redirecting to cart');
        
        // Thêm vào giỏ hàng rồi chuyển đến trang giỏ hàng
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...');

        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_add_to_cart',
                product_id: productId,
                variation_id: variationId,
                quantity: 1
            },
            success: function(response) {
                console.log('Add to cart response:', response);
                
                // Trigger WooCommerce event
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                
                // Chuyển hướng đến trang giỏ hàng
                window.location.href = wc_add_to_cart_params.cart_url;
            },
            error: function(xhr, status, error) {
                console.error('Add to cart error:', error);
                $button.prop('disabled', false).html('<i class="fa-solid fa-credit-card"></i> Mua ngay');
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });

    // ==========================================
    // XỬ LÝ NÚT "THÊM VÀO GIỎ HÀNG" (AJAX)
    // ==========================================
    $('.btn-secondary-proxy.ajax_add_to_cart').on('click', function(e) {
        e.preventDefault();

        const variationId = $('.variation_id').val();
        const productId = $('input[name="product_id"]').val();

        if (!variationId || variationId === '0') {
            alert('Vui lòng chọn đầy đủ Kế hoạch và Thời hạn!');
            return;
        }

        console.log('Adding to cart:', {
            product_id: productId,
            variation_id: variationId
        });

        const $button = $(this);
        const originalHtml = $button.html();
        
        $button.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang thêm...');

        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_add_to_cart',
                product_id: productId,
                variation_id: variationId,
                quantity: 1
            },
            success: function(response) {
                console.log('✓ Added to cart successfully:', response);
                
                // Trigger WooCommerce event để update mini cart
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                
                // Reset button
                $button.prop('disabled', false).html(originalHtml);
                
                // Hiển thị thông báo thành công
                showSuccessNotification('Đã thêm sản phẩm vào giỏ hàng!');
                
                // Update số lượng giỏ hàng nếu có element
                if (response.fragments) {
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('✗ Add to cart error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                $button.prop('disabled', false).html(originalHtml);
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });

    // ==========================================
    // HIỂN THỊ NOTIFICATION THÀNH CÔNG
    // ==========================================
    function showSuccessNotification(message) {
        // Tạo notification element nếu chưa có
        let $notification = $('#add-to-cart-notification');
        
        if (!$notification.length) {
            $('body').append(`
                <div id="add-to-cart-notification" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #10b981;
                    color: white;
                    padding: 16px 24px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 99999;
                    display: none;
                    animation: slideInRight 0.3s ease-out;
                ">
                    <i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>
                    <span class="notification-message"></span>
                </div>
            `);
            $notification = $('#add-to-cart-notification');
        }
        
        // Update message và hiển thị
        $notification.find('.notification-message').text(message);
        $notification.fadeIn(300);
        
        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            $notification.fadeOut(300);
        }, 3000);
    }
});

// CSS Animation cho notification
if (!document.getElementById('notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}