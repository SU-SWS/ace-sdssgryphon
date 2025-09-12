document.addEventListener('DOMContentLoaded', function() {
    const sr = ScrollReveal();
    sr.reveal('.su-intro-text, .su-wysiwyg-text h2, .su-wysiwyg-text h3, .su-wysiwyg-text p', { distance: '50px',
        origin: 'right',
        duration: 1000,
        easing: 'ease-out'
    });
});