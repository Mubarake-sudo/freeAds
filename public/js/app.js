import './bootstrap';

// Price slider functionality
document.addEventListener('DOMContentLoaded', function() {
    const priceSlider = document.getElementById('priceSlider');
    const priceValue = document.getElementById('priceValue');
    
    if (priceSlider && priceValue) {
        priceSlider.addEventListener('input', function() {
            priceValue.textContent = this.value;
        });
    }
    
    // Filter functionality
    const categorySelect = document.querySelector('.filter-select');
    const locationSelect = document.querySelectorAll('.filter-select')[1];
    const conditionRadios = document.querySelectorAll('input[name="condition"]');
    
    // Add event listeners for filters
    categorySelect.addEventListener('change', filterAds);
    locationSelect.addEventListener('change', filterAds);
    conditionRadios.forEach(radio => {
        radio.addEventListener('change', filterAds);
    });
    
    // Filter function (placeholder for demo)
    function filterAds() {
        console.log('Filtering ads...');
        // In a real application, this would filter the displayed ads
        // For now, we'll just add a visual feedback
        const ads = document.querySelectorAll('.ad-card');
        ads.forEach(ad => {
            ad.style.opacity = '0.7';
            setTimeout(() => {
                ad.style.opacity = '1';
            }, 300);
        });
    }
    
    // Pagination functionality
    const pageNumbers = document.querySelectorAll('.page-number');
    
    pageNumbers.forEach(page => {
        page.addEventListener('click', function() {
            // Remove active class from all pages
            pageNumbers.forEach(p => p.classList.remove('active'));
            // Add active class to clicked page
            this.classList.add('active');
            
            // Add loading effect
            const adsContainer = document.querySelector('.ads-list');
            adsContainer.style.opacity = '0.5';
            adsContainer.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                adsContainer.style.opacity = '1';
                adsContainer.style.transform = 'translateY(0)';
            }, 300);
        });
    });
    
    // Add hover effects for ad cards
    const adCards = document.querySelectorAll('.ad-card');
    
    adCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Sign in button functionality
    const signInLink = document.querySelector('.signin-link');
    if (signInLink) {
        signInLink.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Sign in functionality would be implemented here');
        });
    }
    
    // Post ads button functionality
    const postAdsBtn = document.querySelector('.btn-primary');
    if (postAdsBtn) {
        postAdsBtn.addEventListener('click', function() {
            alert('Post ad functionality would be implemented here');
        });
    }
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    console.log('FreeAds marketplace loaded successfully!');
});
