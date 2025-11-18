

// ============================== FETCH & RENDER DATA TRƯỚC ======================================
document.addEventListener("DOMContentLoaded", async () => {
  const API_BASE = "http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/wp/v2";

  // Lấy slug từ URL
  function getSlugFromPath() {
    const parts = location.pathname.split('/').filter(Boolean); 
    const slug = parts[parts.length - 1] || null;
    return slug;
  }

  const slug = getSlugFromPath(); 

  /**====================== Fetch header ===============*/
  const single_header = document.querySelector(".blog-content");

  // Loading state
  single_header.innerHTML = '<p style="text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin"></i> Đang tải...</p>';

  try {
    const response = await fetch(`${API_BASE}/cpt_post?slug=${encodeURIComponent(slug)}`);
    
    const posts = await response.json();

    const post = posts[0];

    // Lấy dữ liệu
    const title = post.title?.rendered || "No title";
    const postData = post.post_data || {};
    
    const date = postData.date || "";
    const tags = Array.isArray(postData.tags) ? postData.tags : [];
    const author = "TheVy";
    const content = postData.content || "Không có nội dung";
    const thumbnail = postData.thumbnail || "https://via.placeholder.com/1200x600";

    const formattedContent = content
      .split('\r\n\r\n')
      .map(para => para.trim())
      .filter(para => para)
      .map(para => `<p>${para.replace(/\r\n/g, '<br>')}</p>`)
      .join('');

    // Render HTML
    const articleHTML = `
      <!-- Title -->
      <h1 class="article-title">${title}</h1>

      <!-- Author & Date -->
      <div class="article-meta">
          <div class="author-info">
              <img src="https://images.unsplash.com/photo-1644088379091-d574269d422f?q=80&w=1393&auto=format&fit=crop" 
                   alt="Author" class="author-avatar">
              <span class="author-name">${author}</span>
          </div>
          <div class="date-info">
              <i class="far fa-calendar"></i>
              <span>${date}</span>
          </div>
      </div>

      <!-- Tags -->
      ${tags.length > 0 ? `
        <div class="article-tags" style="margin: 20px 0;">
          ${tags.map(tag => `<span class="tag" style="display:inline-block;background:#f0f0f0;padding:5px 10px;margin-right:5px;border-radius:4px;">${tag}</span>`).join('')}
        </div>
      ` : ''}

      <!-- Featured Image -->
      <div class="featured-image">
          <img src="${thumbnail}" alt="${title}">
      </div>

      <!-- Article Content -->
      <div class="article-content">
          ${formattedContent}
      </div>
    `;
    
    single_header.innerHTML = articleHTML;

    // ========== another ==========
    setTimeout(() => {
      
      // load images
      lazyLoadImages();
      
      // Add copy buttons cho code blocks
      addCopyButtons();
      
      // Generate table of contents
      generateTableOfContents();
      
      // Social share
      addSocialShare();
      
      console.log("Tất cả tính năng đã sẵn sàng!");
    }, 100);

  } catch (error) {
    console.error("Lỗi khi fetch:", error);
  }
});

// ============================== run single ======================================

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

// Add reading progress bar
function createProgressBar() {
  const progressBar = document.createElement("div");
  progressBar.className = "reading-progress";
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

  window.addEventListener("scroll", () => {
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
    progressBar.style.width = scrollPercent + "%";
  });
}

// Initialize progress bar
createProgressBar();

// Image lazy loading
function lazyLoadImages() {
  const images = document.querySelectorAll("img[data-src]");
  
  if (images.length === 0) {
    console.log("Không có ảnh nào cần lazy load");
    return;
  }

  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute("data-src");
        observer.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));
  console.log("Lazy loading initialized:", images.length, "images");
}

// Copy code blocks functionality
function addCopyButtons() {
  const codeBlocks = document.querySelectorAll("pre code");
  
  if (codeBlocks.length === 0) {
    console.log("Không có code block nào");
    return;
  }

  codeBlocks.forEach((codeBlock) => {
    const button = document.createElement("button");
    button.className = "copy-code-btn";
    button.textContent = "Copy";
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

    button.addEventListener("mouseenter", () => {
      button.style.opacity = "1";
    });

    button.addEventListener("mouseleave", () => {
      button.style.opacity = "0.8";
    });

    button.addEventListener("click", () => {
      const code = codeBlock.textContent;
      navigator.clipboard.writeText(code).then(() => {
        button.textContent = "Copied!";
        setTimeout(() => {
          button.textContent = "Copy";
        }, 2000);
      });
    });

    const pre = codeBlock.parentElement;
    pre.style.position = "relative";
    pre.appendChild(button);
  });
  
  console.log("Copy buttons added:", codeBlocks.length, "code blocks");
}

// Table of contents generator
function generateTableOfContents() {
  const headings = document.querySelectorAll(".article-content h2, .article-content h3");

  if (headings.length === 0) {
    console.log("Không có heading nào để tạo TOC");
    return;
  }

  const toc = document.createElement("div");
  toc.className = "table-of-contents";
  toc.style.cssText = `
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #0066cc;
  `;

  const tocTitle = document.createElement("h3");
  tocTitle.textContent = "Mục lục";
  tocTitle.style.cssText = `
    font-size: 18px;
    margin-bottom: 15px;
    color: #1a1a1a;
  `;
  toc.appendChild(tocTitle);

  const tocList = document.createElement("ul");
  tocList.style.cssText = `
    list-style: none;
    padding: 0;
    margin: 0;
  `;

  headings.forEach((heading, index) => {
    const id = `heading-${index}`;
    heading.id = id;

    const li = document.createElement("li");
    li.style.cssText = `
      margin-bottom: 8px;
      padding-left: ${heading.tagName === "H3" ? "20px" : "0"};
    `;

    const link = document.createElement("a");
    link.href = `#${id}`;
    link.textContent = heading.textContent;
    link.style.cssText = `
      color: #0066cc;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s;
    `;
    link.addEventListener("mouseenter", () => {
      link.style.color = "#004999";
    });
    link.addEventListener("mouseleave", () => {
      link.style.color = "#0066cc";
    });

    li.appendChild(link);
    tocList.appendChild(li);
  });

  toc.appendChild(tocList);

  const articleContent = document.querySelector(".article-content");
  if (articleContent) {
    articleContent.insertBefore(toc, articleContent.firstChild);
    console.log("TOC generated:", headings.length, "headings");
  }
}

// Social share functionality
function addSocialShare() {
  const shareButtons = document.querySelectorAll("[data-share]");
  
  if (shareButtons.length === 0) {
    console.log("Không có nút share nào");
    return;
  }

  shareButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      const platform = button.dataset.share;
      const url = encodeURIComponent(window.location.href);
      const title = encodeURIComponent(document.title);

      let shareUrl = "";

      switch (platform) {
        case "facebook":
          shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
          break;
        case "twitter":
          shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
          break;
        case "telegram":
          shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
          break;
      }

      if (shareUrl) {
        window.open(shareUrl, "_blank", "width=600,height=400");
      }
    });
  });
  
  console.log("Social share initialized");
}

// Scroll to top button
function createScrollToTop() {
  const button = document.createElement("button");
  button.className = "scroll-to-top";
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

  button.addEventListener("mouseenter", () => {
    button.style.transform = "scale(1.1)";
    button.style.background = "#004999";
  });

  button.addEventListener("mouseleave", () => {
    button.style.transform = "scale(1)";
    button.style.background = "#0066cc";
  });

  button.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });

  window.addEventListener("scroll", () => {
    if (window.pageYOffset > 300) {
      button.style.display = "flex";
    } else {
      button.style.display = "none";
    }
  });

  document.body.appendChild(button);
}

// Initialize scroll to top
createScrollToTop();

// Add animation on scroll
function animateOnScroll() {
  const elements = document.querySelectorAll(".post-item, .sidebar-section");

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "0";
          entry.target.style.transform = "translateY(20px)";
          entry.target.style.transition = "opacity 0.5s, transform 0.5s";

          setTimeout(() => {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
          }, 100);

          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  elements.forEach((el) => observer.observe(el));
}

// Initialize animations
animateOnScroll();

console.log("Blog detail page script loaded!");

// ==================================== fetch related blog ======================================

document.addEventListener("DOMContentLoaded", async () => { 
  const API_BASE = "http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/wp/v2";

  function getSlugFromPath() {
    const parts = location.pathname.split('/').filter(Boolean); 
    const slug = parts[parts.length - 1] || null;
    return slug;
  }

  const slug = getSlugFromPath(); 

  /* ================fetch related================== */
  const related = document.querySelector(".post-list");

  related.innerHTML = '<p style="text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin"></i> Đang tải bài viết mới nhất...</p>';

  try {
    const [allPostsResponse, currentPostResponse] = await Promise.all([
      fetch(`${API_BASE}/cpt_post?per_page=100`),
      fetch(`${API_BASE}/cpt_post?slug=${encodeURIComponent(slug)}`) 
    ]);

    const allPosts = await allPostsResponse.json();
    const currentPostArray = await currentPostResponse.json();

    const currentPost = currentPostArray[0];

    // filter
    const filteredPosts = allPosts.filter(post => post.id !== currentPost.id);
    

    // count by number blog
    function getRandomPosts(posts, count = 5) {
      const shuffled = [...posts].sort(() => 0.5 - Math.random());
      return shuffled.slice(0, Math.min(count, posts.length));
    }

    const relatedPosts = getRandomPosts(filteredPosts, 5);

    // Clear loading state
    related.innerHTML = '';

    // Render các bài viết
    relatedPosts.forEach((post) => {
      const title = post.title?.rendered || "No title";
      const postData = post.post_data || {};
      const thumbnail = postData.thumbnail || "https://via.placeholder.com/400x300";
      const date = postData.date || "Không có ngày";
      const link = post.link || '#';

      const postHTML = `
        <article class="post-item">
          <img src="${thumbnail}" alt="${title}">
          <div class="post-info">
            <h4><a href="${link}">${title}</a></h4>
            <span class="post-date-single-post">${date}</span>
          </div>
        </article>
      `;
      related.insertAdjacentHTML("beforeend", postHTML);
    });

  } catch (error) {
    console.error("Lỗi khi fetch:", error);
  }
});
// ==================================== FETCH RELATED BLOG (CÙNG CATEGORY) ======================================

document.addEventListener("DOMContentLoaded", async () => { 
  const API_BASE = "http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/wp/v2";

  // Lấy slug từ URL
  function getSlugFromPath() {
    const parts = location.pathname.split('/').filter(Boolean); 
    const slug = parts[parts.length - 1] || null;
    return slug;
  }

  const slug = getSlugFromPath(); 

  /* ================fetch related by category================== */
  const related = document.querySelector(".post-list-lq");
  related.innerHTML = '<p style="text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin"></i> Đang tải bài viết liên quan...</p>';

  try {
    const currentPostResponse = await fetch(`${API_BASE}/cpt_post?slug=${encodeURIComponent(slug)}`);

    const currentPostArray = await currentPostResponse.json();

    const currentPost = currentPostArray[0];
    const currentCategories = currentPost.post_category || [];
    const currentPostId = currentPost.id;

    const allPostsResponse = await fetch(`${API_BASE}/cpt_post?per_page=100`);
    

    const allPosts = await allPostsResponse.json();

    const sameCategoryPosts = allPosts.filter(post => {
      if (post.id === currentPostId) return false;
      const postCategories = post.post_category || [];
      const hasCommonCategory = currentCategories.some(catId => 
        postCategories.includes(catId)
      );
      
      return hasCommonCategory;
    });

    let relatedPosts = [];
    
    if (sameCategoryPosts.length >= 5) {
      relatedPosts = getRandomPostss(sameCategoryPosts, 5);
      
    } else if (sameCategoryPosts.length > 0) {
      relatedPosts = [...sameCategoryPosts];
      const sameCategoryIds = sameCategoryPosts.map(p => p.id);
      const otherPosts = allPosts.filter(post => 
        post.id !== currentPostId && !sameCategoryIds.includes(post.id)
      );
      
      const needed = 5 - relatedPosts.length;
      
      if (otherPosts.length > 0) {
        const additionalPosts = getRandomPostss(otherPosts, needed);
        relatedPosts = [...relatedPosts, ...additionalPosts];
      }
      
    } else {
      const otherPosts = allPosts.filter(post => post.id !== currentPostId);
      
      if (otherPosts.length > 0) {
        relatedPosts = getRandomPostss(otherPosts, 5);
      }
    }

    if (relatedPosts.length === 0) {
      related.innerHTML = '<p style="text-align:center;padding:20px;">Không có bài viết liên quan.</p>';
      return;
    }

    related.innerHTML = ''; // Clear loading

    relatedPosts.forEach((post) => {
      const title = post.title?.rendered || "No title";
      const postData = post.post_data || {};
      const thumbnail = postData.thumbnail || "https://via.placeholder.com/400x300";
      const date = postData.date || "Không có ngày";
      const link = post.link || '#';

      const postHTML = `
        <article class="post-item">
                    <img src="${thumbnail}" alt="Post thumbnail">
                    <div class="post-info">
                        <h4><a href="${link}">${title}</a></h4>
                        <span class="post-date-single-post">${date}</span>
                    </div>
                </article>
      `;
      related.insertAdjacentHTML("beforeend", postHTML);
    });

    console.log("[Related] Render thành công", relatedPosts.length, "bài viết!");

  } catch (error) {
    console.error("[Related] Lỗi khi fetch:", error);
    related.innerHTML = `
      <div style="text-align:center;padding:20px;">
        <p style="color:red;">Không thể tải bài viết liên quan</p>
        <p style="color:#999;font-size:14px;">${error.message}</p>
      </div>
    `;
  }
});

// Hàm lấy random posts
function getRandomPostss(posts, count) {
  const shuffled = [...posts].sort(() => 0.5 - Math.random());
  return shuffled.slice(0, Math.min(count, posts.length));
}



