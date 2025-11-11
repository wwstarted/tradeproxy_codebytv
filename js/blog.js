// DOM Elements
const searchInput = document.getElementById('searchInput');
const searchBtn = document.querySelector('.search-btn');
const categoryItems = document.querySelectorAll('.category-item');
const postsList = document.getElementById('postsList');
const postCards = document.querySelectorAll('.post-card');
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
let filteredPosts = [];

// Initialize
function init() {
    filteredPosts = Array.from(postCards);
    renderPagination();
}

// Category Filter
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

// Real-time search (optional)
searchInput.addEventListener('input', () => {
    currentSearchTerm = searchInput.value.toLowerCase().trim();
    currentPage = 1;
    filterPosts();
});

// Filter Posts Function
function filterPosts() {
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
    // Hide all posts first
    postCards.forEach(card => {
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

// Add animation on page load
document.addEventListener('DOMContentLoaded', () => {
    init();
    
    postCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});