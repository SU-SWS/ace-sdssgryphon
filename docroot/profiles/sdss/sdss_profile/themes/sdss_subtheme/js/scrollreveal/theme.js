window.addEventListener('load', function() {
    const sr = ScrollReveal();
    sr.reveal('.su-wysiwyg-text h2', { 
        distance: '80px',
        origin: 'right',
        duration: 700,
        delay: 200,
        easing: 'ease-in-out',
    });
    sr.reveal('.ptype-stanford-lists img', { 
        distance: '0',
        delay: 2500,
        duration: 700,
        easing: 'ease-out',
    });
    /*sr.reveal('.layout--layout-paragraphs-two-column .su-card img, .layout--layout-paragraphs-two-column .su-card h2, .layout--layout-paragraphs-two-column .su-card .su-card__button', { 
        distance: '50px',
        duration: 1000,
        interval: 100,
        easing: 'ease-out',
    });*/
    sr.reveal('.layout--layout-paragraphs-three-column .su-card', { 
        distance: '80px',
        duration: 600,
        delay: 200,
        interval: 200,
        easing: 'ease-out',
    });
});