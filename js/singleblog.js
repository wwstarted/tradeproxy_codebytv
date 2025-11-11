// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add reading progress bar
function createProgressBar() {
    const progressBar = document.createElement('div');
    progressBar.className = 'reading-progress';
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(90deg, #ffffffff 0%, #c7c0fbff 100%);
        z-index: 9999;
        transition: width 0.1s ease;
    `;
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', () => {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
        progressBar.style.width = scrollPercent + '%';
    });
}

// Initialize progress bar
createProgressBar();

// Image lazy loading
function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading
lazyLoadImages();

// Copy code blocks functionality
function addCopyButtons() {
    const codeBlocks = document.querySelectorAll('pre code');
    
    codeBlocks.forEach((codeBlock) => {
        const button = document.createElement('button');
        button.className = 'copy-code-btn';
        button.textContent = 'Copy';
        button.style.cssText = `
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            opacity: 0.8;
            transition: opacity 0.3s;
        `;
        
        button.addEventListener('mouseenter', () => {
            button.style.opacity = '1';
        });
        
        button.addEventListener('mouseleave', () => {
            button.style.opacity = '0.8';
        });
        
        button.addEventListener('click', () => {
            const code = codeBlock.textContent;
            navigator.clipboard.writeText(code).then(() => {
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = 'Copy';
                }, 2000);
            });
        });
        
        const pre = codeBlock.parentElement;
        pre.style.position = 'relative';
        pre.appendChild(button);
    });
}

// Initialize copy buttons
addCopyButtons();

// Table of contents generator
function generateTableOfContents() {
    const headings = document.querySelectorAll('.article-content h2, .article-content h3');
    
    if (headings.length > 0) {
        const toc = document.createElement('div');
        toc.className = 'table-of-contents';
        toc.style.cssText = `
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #0066cc;
        `;
        
        const tocTitle = document.createElement('h3');
        tocTitle.textContent = 'Mục lục';
        tocTitle.style.cssText = `
            font-size: 18px;
            margin-bottom: 15px;
            color: #1a1a1a;
        `;
        toc.appendChild(tocTitle);
        
        const tocList = document.createElement('ul');
        tocList.style.cssText = `
            list-style: none;
            padding: 0;
            margin: 0;
        `;
        
        headings.forEach((heading, index) => {
            const id = `heading-${index}`;
            heading.id = id;
            
            const li = document.createElement('li');
            li.style.cssText = `
                margin-bottom: 8px;
                padding-left: ${heading.tagName === 'H3' ? '20px' : '0'};
            `;
            
            const link = document.createElement('a');
            link.href = `#${id}`;
            link.textContent = heading.textContent;
            link.style.cssText = `
                color: #0066cc;
                text-decoration: none;
                font-size: 14px;
                transition: color 0.3s;
            `;
            link.addEventListener('mouseenter', () => {
                link.style.color = '#004999';
            });
            link.addEventListener('mouseleave', () => {
                link.style.color = '#0066cc';
            });
            
            li.appendChild(link);
            tocList.appendChild(li);
        });
        
        toc.appendChild(tocList);
        
        const articleContent = document.querySelector('.article-content');
        if (articleContent) {
            articleContent.insertBefore(toc, articleContent.firstChild);
        }
    }
}

// Initialize table of contents
generateTableOfContents();

// Social share functionality
function addSocialShare() {
    const shareButtons = document.querySelectorAll('[data-share]');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const platform = button.dataset.share;
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            
            let shareUrl = '';
            
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'telegram':
                    shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
}

// Initialize social share
addSocialShare();

// Scroll to top button
function createScrollToTop() {
    const button = document.createElement('button');
    button.className = 'scroll-to-top';
    button.innerHTML = '<i class="fas fa-arrow-up"></i>';
    button.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: #0066cc;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
        z-index: 1000;
        transition: all 0.3s;
    `;
    
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'scale(1.1)';
        button.style.background = '#004999';
    });
    
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'scale(1)';
        button.style.background = '#0066cc';
    });
    
    button.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            button.style.display = 'flex';
        } else {
            button.style.display = 'none';
        }
    });
    
    document.body.appendChild(button);
}

// Initialize scroll to top
createScrollToTop();

// Add animation on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.post-item, .sidebar-section');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                entry.target.style.transition = 'opacity 0.5s, transform 0.5s';
                
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    elements.forEach(el => observer.observe(el));
    
}

// Initialize animations
animateOnScroll();

// // Print article function
// function addPrintButton() {
//     const printBtn = document.createElement('button');
//     printBtn.className = 'print-article';
//     printBtn.innerHTML = '<i class="fas fa-print"></i> In bài viết';
//     printBtn.style.cssText = `
//         position: fixed;
//         bottom: 90px;
//         right: 30px;
//         padding: 12px 20px;
//         background: #28a745;
//         color: white;
//         border: none;
//         border-radius: 25px;
//         cursor: pointer;
//         font-size: 14px;
//         box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
//         z-index: 1000;
//         display: none;
//         transition: all 0.3s;
//     `;
    
//     printBtn.addEventListener('click', () => {
//         window.print();
//     });
    
//     window.addEventListener('scroll', () => {
//         if (window.pageYOffset > 300) {
//             printBtn.style.display = 'block';
//         } else {
//             printBtn.style.display = 'none';
//         }
//     });
    
//     document.body.appendChild(printBtn);
// }

// Initialize print button
addPrintButton();

console.log('Blog detail page loaded successfully!');