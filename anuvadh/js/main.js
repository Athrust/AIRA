// Authentication check
const currentPage = window.location.pathname.split('/').pop() || 'index.html';
const isSignupPage = currentPage === 'signup.html';
const isLoggedIn = localStorage.getItem('anuvadh_logged_in');

if (!isLoggedIn && !isSignupPage) {
    // Redirect to signup page if not logged in
    window.location.href = 'signup.html';
}

document.addEventListener('DOMContentLoaded', () => {
    // Reveal animations on page load
    const fadeElements = document.querySelectorAll('.fade-in');
    
    // Add the visible class after a short delay for a smooth staggered effect
    setTimeout(() => {
        fadeElements.forEach(element => {
            element.classList.add('visible');
        });
    }, 150);

    // Subtle parallax effect on the background blobs matching mouse movement
    document.addEventListener('mousemove', (e) => {
        // Run only for larger screens to conserve battery on mobile
        if(window.innerWidth > 768) {
            const blobs = document.querySelectorAll('.blob');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            blobs.forEach((blob, index) => {
                // Different speeds for depth effect
                const speed = (index + 1) * 35;
                const xOffset = (x * speed) - (speed / 2);
                const yOffset = (y * speed) - (speed / 2);
                
                // Shift slowly
                blob.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
            });
        }
    });

    // Optional: Interactive glow on cards
    const cards = document.querySelectorAll('.glass.card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });
    });
});
