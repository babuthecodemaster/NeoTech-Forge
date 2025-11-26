/**
 * Cyberpunk Animations JavaScript
 * Handles welcome animation, page transitions, and other interactive effects
 */

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize welcome overlay animation if it exists
    initWelcomeOverlay();
    
    // Initialize page transitions
    initPageTransitions();
    
    // Initialize mobile menu toggle
    initMobileMenu();
    
    // Update cart count
    updateCartCount();
    
    // Initialize cyberpunk effects
    initCyberpunkEffects();
});

// Welcome overlay animation
function initWelcomeOverlay() {
    const welcomeOverlay = document.getElementById('welcomeOverlay');
    
    if (welcomeOverlay) {
        const loadingProgress = document.getElementById('loadingProgress');
        const loadingText = document.getElementById('loadingText');
        const welcomeAudio = document.getElementById('welcomeAudio');
        
        // Check if user has seen the welcome animation before
        const hasSeenAnimation = sessionStorage.getItem('hasSeenWelcomeAnimation');
        
        if (hasSeenAnimation) {
            welcomeOverlay.style.display = 'none';
            return;
        }
        
        // Display loading progress animation
        setTimeout(() => {
            loadingProgress.style.width = '30%';
            loadingText.textContent = 'SCANNING SYSTEM...';
        }, 500);
        
        setTimeout(() => {
            loadingProgress.style.width = '60%';
            loadingText.textContent = 'INITIALIZING INTERFACE...';
        }, 1500);
        
        setTimeout(() => {
            loadingProgress.style.width = '90%';
            loadingText.textContent = 'ACCESSING NEOTECH FORGE...';
        }, 2500);
        
        setTimeout(() => {
            loadingProgress.style.width = '100%';
            loadingText.textContent = 'ACCESS GRANTED';
        }, 3000);
        
        // Hide welcome overlay and play sound effect
        setTimeout(() => {
            welcomeOverlay.style.opacity = '0';
            
            if (welcomeAudio) {
                welcomeAudio.play().catch(error => {
                    console.log('Audio playback prevented: ', error);
                });
            }
            
            setTimeout(() => {
                welcomeOverlay.style.display = 'none';
                sessionStorage.setItem('hasSeenWelcomeAnimation', 'true');
            }, 500);
        }, 3500);
    }
}

// Page transitions
function initPageTransitions() {
    const pageTransition = document.getElementById('pageTransition');
    if (pageTransition) {
        const links = document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href.indexOf('#') !== 0 && href.indexOf('://') === -1) {
                    e.preventDefault();
                    pageTransition.style.opacity = 1;
                    
                    setTimeout(() => {
                        window.location.href = href;
                    }, 500);
                }
            });
        });
        
        // Hide transition overlay when page loads
        window.addEventListener('pageshow', function() {
            pageTransition.style.opacity = 0;
        });
    }
}

// Mobile menu toggle
function initMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
            
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        });
    }
}

// Update cart count
function updateCartCount() {
    const cartCountElement = document.getElementById('cartCount');
    
    if (cartCountElement) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cartCountElement.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        // Add glitch effect if cart is not empty
        if (cart.length > 0) {
            cartCountElement.classList.add('has-items');
        } else {
            cartCountElement.classList.remove('has-items');
        }
    }
}

// Show toast notification
function showToast(type, message) {
    const toast = document.getElementById('toast');
    
    if (toast) {
        const toastMessage = document.querySelector('.toast-message');
        const toastIcon = document.querySelector('.toast-icon');
        
        toastMessage.textContent = message;
        toast.className = 'toast';
        toast.classList.add('show');
        
        if (type === 'success') {
            toast.classList.add('toast-success');
            toastIcon.className = 'fas fa-check-circle toast-icon';
        } else if (type === 'error') {
            toast.classList.add('toast-error');
            toastIcon.className = 'fas fa-exclamation-circle toast-icon';
        } else if (type === 'warning') {
            toast.classList.add('toast-warning');
            toastIcon.className = 'fas fa-exclamation-triangle toast-icon';
        }
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
        
        // Close toast on click
        const toastClose = document.querySelector('.toast-close');
        if (toastClose) {
            toastClose.addEventListener('click', function() {
                toast.classList.remove('show');
            });
        }
    }
}

// Initialize additional cyberpunk effects
function initCyberpunkEffects() {
    // Add glitchy cursor effects
    document.body.addEventListener('mousemove', createGlitchTrail);
    
    // Add random glitch effects to hero elements
    initRandomGlitches();
    
    // Add hover effects to digital noise elements
    initDigitalNoiseHover();
}

// Create glitch trail effect on mouse move
function createGlitchTrail(e) {
    // Only create a glitch particle occasionally to prevent overwhelming effects
    if (Math.random() > 0.97) {
        const glitchParticle = document.createElement('div');
        glitchParticle.classList.add('glitch-particle');
        
        // Random size between 5px and 15px
        const size = 5 + Math.random() * 10;
        
        // Position at cursor
        glitchParticle.style.width = `${size}px`;
        glitchParticle.style.height = `${size}px`;
        glitchParticle.style.left = `${e.clientX}px`;
        glitchParticle.style.top = `${e.clientY}px`;
        
        // Random color (cyan or magenta with varying opacity)
        const color = Math.random() > 0.5 ? 
            `rgba(0, 240, 255, ${0.3 + Math.random() * 0.7})` : 
            `rgba(247, 0, 255, ${0.3 + Math.random() * 0.7})`;
        
        glitchParticle.style.backgroundColor = color;
        
        // Add to body
        document.body.appendChild(glitchParticle);
        
        // Animate and remove
        setTimeout(() => {
            glitchParticle.style.opacity = '0';
            glitchParticle.style.transform = `translate(${(Math.random() - 0.5) * 50}px, ${(Math.random() - 0.5) * 50}px) scale(0)`;
            
            setTimeout(() => {
                if (glitchParticle.parentNode) {
                    glitchParticle.parentNode.removeChild(glitchParticle);
                }
            }, 300);
        }, 50);
    }
}

// Initialize random glitch effects
function initRandomGlitches() {
    const glitchableElements = document.querySelectorAll('h1, h2, .btn-primary, .card-title');
    
    setInterval(() => {
        // Only glitch occasionally
        if (Math.random() > 0.6 && glitchableElements.length > 0) {
            // Pick a random element to glitch
            const randomElement = glitchableElements[Math.floor(Math.random() * glitchableElements.length)];
            
            // Add glitch class
            randomElement.classList.add('random-glitch');
            
            // Remove glitch class after animation
            setTimeout(() => {
                randomElement.classList.remove('random-glitch');
            }, 200 + Math.random() * 300);
        }
    }, 3000);
}

// Initialize digital noise hover effects
function initDigitalNoiseHover() {
    const digitalNoiseElements = document.querySelectorAll('.digital-noise');
    
    digitalNoiseElements.forEach(element => {
        const parent = element.parentNode;
        
        if (parent) {
            parent.addEventListener('mouseenter', () => {
                element.style.opacity = '0.1';
                element.style.animation = 'noiseAnimation 0.5s steps(2) infinite';
            });
            
            parent.addEventListener('mouseleave', () => {
                element.style.opacity = '0';
                element.style.animation = 'none';
            });
        }
    });
} 