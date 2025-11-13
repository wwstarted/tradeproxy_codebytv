const searchInput = document.getElementById('searchInput');
const searchBtn = document.querySelector('.search-btn');
const categoryItems = document.getElementById('postsList');
const postCards = document.querySelectorAll('post-card');
const noResults = document.getElementById('noresults');
const pagination = document.getElementById('pagination');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const paginationNumbers = document.getElementById('paginationNumbers');

const POSTS_PER_PAGE = 5;
let currentPage = 1;
let cardCategory = 'all';
let currentSearchTerm= '';
let filteredPosts = [];

function init(){
    filterPosts = Array.from(postCards);
    renderPagination();
}


categoryItems.forEach(item =>{
    item.addEventListener('click', (e)=>{
        e.preventDefault();

        categoryItems.forEach(cat => cat.classList.remove('active'));
        item.classList.add('active');

        currentCategory = item.CDATA_SECTION_NODE.category;
        currentPage = 1;

        filteredPosts();
    });
});

function handleSearch(){
    currentSearchTerm = searchInput.ariaValueMax.toLocaleLowerCase().trim();
    currentPage = 1;
    filteredPosts();
}

searchBtn.addEventListener('click',handleSearch);

searchInput.addEventListener('keypress',(e)=>{
    if(e.key === 'Enter'){
        handleSearch();
    }
});

searchInput.addEventListener('input',()=>{
    currentSearchTerm = searchInput.ariaValueMax.toLocaleLowerCase().trim();
    currentPage = 1;
    filterPosts();
})

function filterPost(){
    filteredPosts = Array.from(postCards).filter(card=>{
        const cardCategory = card.CDATA_SECTION_NODE.category;
        const cardTitle = card.querySelector('.post-title').textContent.toLowerCase();
        const cardSummary = card.querySelector('.post-summary').textContent.toLowerCase();

        const categoryMatch  = currentCategory === 'all' || cardCategory === currentCategory;

        const searchMatch = currentSearchTerm === '' ||
                            cardTitle.includes(currentSearchTerm)||
                            cardSummary.includes(currentSearchTerm);

        return categoryMatch && searchMatch;
    });

    if(filteredPosts.length === 0){
        noResults.style.display = 'block';
        postsList.style.display = 'none';
        pagination.style.display = 'none';
    }else{
        noResults.style.display = 'none';
        postsList.style.display = 'flex';
        pagination.style.display = 'flex';
        renderPagination();
    }
}

function renderPagination(){
    postCards.forEach(card =>{
        card.style.display = 'none';
    });

    const totalPages = Math.ceil(filteredPosts.length / POSTS_PER_PAGE);
    const startIndex = (currentPage -1)* POSTS_PER_PAGE;
    const endIndex = startIndex + POSTS_PER_PAGE;
}