// ============================================
// PROVIDER PAGE FUNCTIONALITY
// ============================================

document.addEventListener("DOMContentLoaded", async function () {

    // API Endpoints
    const API_BASE = 'http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/wp/v2';
    const CATEGORIES_API = `${API_BASE}/provider_category`;
    const PROVIDERS_API = `${API_BASE}/provider?per_page=100`;

    // DOM Elements
    const filterTabs = document.querySelector('.filter-tabs');
    const providerGrid = document.querySelector('.provider-grid');
    const ctaButton = document.querySelector('.cta-button');
    const paginationContainer = document.createElement('div');
    
    // Insert pagination after provider grid
    paginationContainer.className = 'pagination-container';
    paginationContainer.innerHTML = `
        <div class="pagination-pr" id="pagination-pr">
            <button class="pagination-btn-pr" id="prevBtn">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="pagination-numbers" id="paginationNumbers"></div>
            <button class="pagination-btn-pr" id="nextBtn">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    `;
    providerGrid.parentNode.insertBefore(paginationContainer, providerGrid.nextSibling);

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const paginationNumbers = document.getElementById('paginationNumbers');

    // Pagination settings
    const PROVIDERS_PER_PAGE = 12;
    let currentPage = 1;
    let currentCategory = 'all';
    let allProviders = [];
    let filteredProviders = [];
    let categories = [];

    // Add CSS animations dynamically
    const style = document.createElement("style");
    style.textContent = ` 
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);

    // Fetch Categories
    async function fetchCategories() {
        try {
            const response = await fetch(CATEGORIES_API);
            const data = await response.json();
            categories = data;
            renderCategories();
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    }

    // Render Categories
    function renderCategories() {
        filterTabs.innerHTML = '';
        
        // Add "Tất cả" category
        const allTab = document.createElement('button');
        allTab.className = 'filter-tab active';
        allTab.dataset.category = 'all';
        allTab.textContent = 'Tất cả các loại';
        filterTabs.appendChild(allTab);

        // Add other categories
        categories.forEach(cat => {
            const tab = document.createElement('button');
            tab.className = 'filter-tab';
            tab.dataset.category = cat.slug;
            tab.textContent = cat.name;
            filterTabs.appendChild(tab);
        });

        // Add event listeners to tabs
        attachTabListeners();
    }

    // Attach Tab Listeners
    function attachTabListeners() {
        const tabs = document.querySelectorAll('.filter-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                tab.classList.add('active');
                
                // Get selected category
                currentCategory = tab.dataset.category;
                currentPage = 1;
                
                // Log filter applied
                console.log('Filter applied:', currentCategory);
                
                // Filter and paginate
                filterProviders();
            });
        });
    }

    // Fetch Providers
    async function fetchProviders() {
        try {
            providerGrid.innerHTML = '<div style="text-align: center; padding: 40px; grid-column: 1/-1;">Đang tải...</div>';
            
            const response = await fetch(PROVIDERS_API);
            const data = await response.json();
            allProviders = data;
            
            renderProviders();
            filterProviders();
        } catch (error) {
            console.error('Error fetching providers:', error);
            providerGrid.innerHTML = '<div style="text-align: center; padding: 40px; grid-column: 1/-1; color: red;">Không thể tải dữ liệu. Vui lòng thử lại sau.</div>';
        }
    }

    // Render All Providers (Hidden by default)
    function renderProviders() {
        providerGrid.innerHTML = '';
        
        allProviders.forEach((provider, index) => {
            const card = document.createElement('a');
            card.className = 'provider-card';
            card.style.display = 'none'; // Hide initially
            
            // Get category slug from class_list
            const categorySlug = getCategorySlug(provider.class_list);
            card.dataset.category = categorySlug;
            
            // Get provider data
            const logo = provider.provider_data?.logo || 'https://via.placeholder.com/150';
            const title = provider.title?.rendered || 'Không có tên';
            const summary = provider.provider_data?.summary || 'Không có mô tả';
            const link = provider.link || '#';
            
            // Build card HTML
            card.href = link;
            card.innerHTML = `
                <div class="provider-card-header">
                    <div class="provider-logo">
                        <img src="${logo}" alt="${title}" />
                    </div>
                    <h3 class="provider-name">${title}</h3>
                </div>
                <p class="provider-description">${stripHtml(summary)}</p>
            `;
            
            providerGrid.appendChild(card);
        });
    }

    // Get Category Slug from class_list
    function getCategorySlug(classList) {
        if (!classList || !Array.isArray(classList)) return 'other';
        
        // Find category class (format: provider_category-*)
        const categoryClass = classList.find(cls => cls.startsWith('provider_category-'));
        if (categoryClass) {
            return categoryClass.replace('provider_category-', '');
        }
        
        return 'other';
    }

    // Strip HTML tags
    function stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    // Filter Providers Function
    function filterProviders() {
        const allCards = document.querySelectorAll('.provider-card');
        
        filteredProviders = Array.from(allCards).filter(card => {
            const cardCategory = card.dataset.category;
            
            // Check category match
            return currentCategory === 'all' || cardCategory === currentCategory;
        });
        
        // Show/hide no results message
        if (filteredProviders.length === 0) {
            providerGrid.innerHTML = '<div style="text-align: center; padding: 40px; grid-column: 1/-1;">Không tìm thấy provider nào trong danh mục này.</div>';
            paginationContainer.style.display = 'none';
        } else {
            paginationContainer.style.display = 'flex';
            renderPagination();
        }
    }

    // Render Pagination
    function renderPagination() {
        const allCards = document.querySelectorAll('.provider-card');
        
        // Hide all cards first
        allCards.forEach(card => {
            card.style.display = 'none';
            card.style.opacity = '0';
            card.style.animation = 'none';
        });
        
        // Calculate pagination
        const totalPages = Math.ceil(filteredProviders.length / PROVIDERS_PER_PAGE);
        const startIndex = (currentPage - 1) * PROVIDERS_PER_PAGE;
        const endIndex = startIndex + PROVIDERS_PER_PAGE;
        
        // Show cards for current page with fadeInUp animation
        const cardsToShow = filteredProviders.slice(startIndex, endIndex);
        cardsToShow.forEach((card, index) => {
            card.style.display = 'block';
            card.style.animation = `fadeInUp 0.5s ease ${index * 0.05}s both`;
        });
        
        // Update prev/next buttons
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        
        // Render page numbers
        renderPageNumbers(totalPages);
    }

    // Render Page Numbers
    function renderPageNumbers(totalPages) {
        paginationNumbers.innerHTML = '';
        
        if (totalPages === 0) return;
        
        // Show max 5 page numbers
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        // Adjust if near the end
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        // Add first page and ellipsis if needed
        if (startPage > 1) {
            addPageNumber(1);
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'pagination-ellipsis';
                ellipsis.textContent = '...';
                paginationNumbers.appendChild(ellipsis);
            }
        }
        
        // Add page numbers
        for (let i = startPage; i <= endPage; i++) {
            addPageNumber(i);
        }
        
        // Add ellipsis and last page if needed
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'pagination-ellipsis';
                ellipsis.textContent = '...';
                paginationNumbers.appendChild(ellipsis);
            }
            addPageNumber(totalPages);
        }
    }

    // Add Page Number Button
    function addPageNumber(pageNum) {
        const pageBtn = document.createElement('button');
        pageBtn.className = 'pagination-number';
        pageBtn.textContent = pageNum;
        
        if (pageNum === currentPage) {
            pageBtn.classList.add('active');
        }
        
        pageBtn.addEventListener('click', () => {
            currentPage = pageNum;
            renderPagination();
            scrollToTop();
        });
        
        paginationNumbers.appendChild(pageBtn);
    }

    // Prev/Next button handlers
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPagination();
            scrollToTop();
        }
    });

    nextBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(filteredProviders.length / PROVIDERS_PER_PAGE);
        if (currentPage < totalPages) {
            currentPage++;
            renderPagination();
            scrollToTop();
        }
    });

    // Scroll to top when changing page
    function scrollToTop() {
        const filterTabsTop = filterTabs.offsetTop - 20;
        window.scrollTo({
            top: filterTabsTop,
            behavior: 'smooth'
        });
    }

    // Smooth scroll for CTA button
    if (ctaButton) {
        ctaButton.addEventListener('click', function () {
            console.log('CTA button clicked - Redirect to registration page');
            // Add your registration page URL here if needed
            // window.location.href = '/register';
        });
    }

    // Initialize
    async function init() {
        await fetchCategories();
        await fetchProviders();
        console.log('Provider page loaded successfully!');
    }

    // Start the app
    init();
});