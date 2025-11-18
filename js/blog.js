document.addEventListener("DOMContentLoaded", async function () {

    // API Endpoints
    const API_BASE = 'http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/wp/v2';
    const CATEGORIES_API = `${API_BASE}/post_category`;
    const POSTS_API = `${API_BASE}/cpt_post`;

    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.querySelector('.search-btn');
    const categoriesList = document.querySelector('.categories-list');
    const postsList = document.getElementById('postsList');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const paginationNumbers = document.getElementById('paginationNumbers');

    // Pagination settings
    const POSTS_PER_PAGE = 5;
    let currentPage = 1;
    let currentCategory = 'all';
    let currentSearchTerm = '';
    let allPosts = [];
    let filteredPosts = [];
    let categories = [];

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
        categoriesList.innerHTML = '';
        
        // Add "Tất cả" category
        const allItem = document.createElement('li');
        allItem.className = 'category-item active';
        allItem.dataset.category = 'all';
        allItem.innerHTML = '<a href="#" class="category-link">Bài viết mới nhất</a>';
        categoriesList.appendChild(allItem);

        // Add other categories
        categories.forEach(cat => {
            const li = document.createElement('li');
            li.className = 'category-item';
            li.dataset.category = cat.slug;
            li.innerHTML = `<a href="#" class="category-link">${cat.name}</a>`;
            categoriesList.appendChild(li);
        });

        // Add event listeners to new category items
        attachCategoryListeners();
    }

    // Attach Category Listeners
    function attachCategoryListeners() {
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                // Update active category
                categoryItems.forEach(cat => cat.classList.remove('active'));
                item.classList.add('active');
                
                // Get selected category
                currentCategory = item.dataset.category;
                currentPage = 1;
                
                // Filter and paginate
                filterPosts();
            });
        });
    }

    // Fetch Posts
    async function fetchPosts() {
        try {
            const response = await fetch(POSTS_API);
            const data = await response.json();
            allPosts = data;
            renderPosts();
            filterPosts();
        } catch (error) {
            console.error('Error fetching posts:', error);
        }
    }

    // Render Posts
    function renderPosts() {
        postsList.innerHTML = '';
        
        allPosts.forEach(post => {
            const article = document.createElement('article');
            article.className = 'post-card';
            
            // Get category slug from post_data
            const categorySlug = getCategorySlug(post.class_list);
            article.dataset.category = categorySlug;
            
            // Get post data
            const thumbnail = post.post_data?.thumbnail || 'https://tse4.mm.bing.net/th/id/OIP.NLuP6Q7V5dg3eSdpLxUt2QHaE7?pid=Api&P=0&h=220';
            const title = post.post_data?.title || post.title?.rendered || 'Không có tiêu đề';
            const summary = post.post_data?.summary || post.excerpt?.rendered || 'Không có mô tả';
            const date = post.post_data?.date || formatDate(post.date);
            const tags = post.post_data?.tags || [];
            const link = post.link || '#';
            
            // Build post card HTML
            article.innerHTML = `
                <div class="post-thumbnail">
                    <div class="thumbnail-wrapper">
                        <img src="${thumbnail}" alt="${title}">
                    </div>
                    ${tags.length > 0 ? `<span class="post-badge">${tags[0]}</span>` : ''}
                    <time class="post-date">${date}</time>
                </div>
                <div class="post-content">
                    <h3 class="post-title">${title}</h3>
                    <p class="post-summary">${stripHtml(summary)}</p>
                    <a href="${link}" class="post-link">Đọc tiếp →</a>
                </div>
            `;
            
            postsList.appendChild(article);
        });
    }

    // Get Category Slug from class_list
    function getCategorySlug(classList) {
        if (!classList || !Array.isArray(classList)) return 'other';
        
        // Find category class (format: post_category-*)
        const categoryClass = classList.find(cls => cls.startsWith('post_category-'));
        if (categoryClass) {
            return categoryClass.replace('post_category-', '');
        }
        
        return 'other';
    }

    // Format Date
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.getMonth() + 1;
        return `${day} tháng ${month}`;
    }

    // Strip HTML tags
    function stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    // Search Functionality
    function handleSearch() {
        currentSearchTerm = searchInput.value.toLowerCase().trim();
        currentPage = 1;
        filterPosts();
    }

    searchBtn.addEventListener('click', handleSearch);

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    });

    // Real-time search
    searchInput.addEventListener('input', () => {
        currentSearchTerm = searchInput.value.toLowerCase().trim();
        currentPage = 1;
        filterPosts();
    });

    // Filter Posts Function
    function filterPosts() {
        const postCards = document.querySelectorAll('.post-card');
        
        filteredPosts = Array.from(postCards).filter(card => {
            const cardCategory = card.dataset.category;
            const cardTitle = card.querySelector('.post-title').textContent.toLowerCase();
            const cardSummary = card.querySelector('.post-summary').textContent.toLowerCase();
            
            // Check category match
            const categoryMatch = currentCategory === 'all' || cardCategory === currentCategory;
            
            // Check search term match
            const searchMatch = currentSearchTerm === '' || 
                               cardTitle.includes(currentSearchTerm) || 
                               cardSummary.includes(currentSearchTerm);
            
            return categoryMatch && searchMatch;
        });
        
        // Show/hide no results message
        if (filteredPosts.length === 0) {
            noResults.style.display = 'block';
            postsList.style.display = 'none';
            pagination.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            postsList.style.display = 'flex';
            pagination.style.display = 'flex';
            renderPagination();
        }
    }

    // Render Pagination
    function renderPagination() {
        const allCards = document.querySelectorAll('.post-card');
        
        // Hide all posts first
        allCards.forEach(card => {
            card.style.display = 'none';
        });
        
        // Calculate pagination
        const totalPages = Math.ceil(filteredPosts.length / POSTS_PER_PAGE);
        const startIndex = (currentPage - 1) * POSTS_PER_PAGE;
        const endIndex = startIndex + POSTS_PER_PAGE;
        
        // Show posts for current page
        const postsToShow = filteredPosts.slice(startIndex, endIndex);
        postsToShow.forEach(card => {
            card.style.display = 'flex';
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
                ellipsis.style.padding = '0 8px';
                ellipsis.style.color = '#999';
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
                ellipsis.style.padding = '0 8px';
                ellipsis.style.color = '#999';
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
        const totalPages = Math.ceil(filteredPosts.length / POSTS_PER_PAGE);
        if (currentPage < totalPages) {
            currentPage++;
            renderPagination();
            scrollToTop();
        }
    });

    // Scroll to top when changing page
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Initialize - Fetch data and setup
    async function init() {
        // Show loading state
        postsList.innerHTML = '<div style="text-align: center; padding: 40px;">Đang tải...</div>';
        
        // Fetch categories and posts
        await fetchCategories();
        await fetchPosts();
        
        // Add animations
        const postCards = document.querySelectorAll('.post-card');
        postCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Start the app
    init();
});