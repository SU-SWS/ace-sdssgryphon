window.addEventListener('load', function() {
    this.setTimeout(() => {
        for(const section of document.querySelectorAll('.su-wysiwyg-text')) {
            if(section.querySelector('h2')) {
                gsap.fromTo(section.querySelector('h2'), 
                    { opacity: 0, x: 80 }, 
                    { 
                        opacity: 1, 
                        x: 0, 
                        duration: .7, 
                        delay: .2, 
                        ease: 'power2.inOut',
                        scrollTrigger: {
                            trigger: section,
                            start: "top 80%", // later in the viewport
                            toggleActions: "play none none none",
                            once: true,
                        }
                    }
                );
            }
        }
        for(const section of document.querySelectorAll('.ptype-stanford-lists .su-card')) {
            if(section.querySelector('img')) {
                gsap.fromTo(section.querySelector('img'), 
                    { opacity: 0 }, 
                    { 
                        opacity: 1,
                        duration: .7, 
                        delay: .2, 
                        ease: 'power2.inOut',
                        scrollTrigger: {
                            trigger: section,
                            start: "top 80%", // later in the viewport
                            toggleActions: "play none none none",
                            once: true,
                        }
                    }
                );
            }
        }
        for(const section of document.querySelectorAll('.layout--layout-paragraphs-three-column')) {
            if(section.querySelectorAll('.su-card')) {
                gsap.fromTo(section.querySelectorAll('.su-card'), 
                    { opacity: 0, y: 80 }, 
                    { 
                        opacity: 1,
                        duration: .6, 
                        y: 0,
                        delay: .2, 
                        ease: 'power2.inOut',
                        stagger: .2,
                        scrollTrigger: {
                            trigger: section,
                            start: "top 80%", // later in the viewport
                            toggleActions: "play none none none",
                            once: true,
                        }
                    }
                );
            }
        }
    }, 1000);
});