gsap.registerPlugin(ScrollTrigger);
gsap.registerPlugin(ScrollToPlugin);

let mm = gsap.matchMedia();

let wrapper;

window.addEventListener('load', function() {

    const pinnedSection = document.querySelector('.su-departments-slider');

    if(pinnedSection) {

        const sliderParent = pinnedSection.closest('.paragraph-item');

        const allPinnedElements = getNextSiblings(sliderParent, 1);

        wrapper = document.createElement('div');
        wrapper.id = "paragraph-pin-wrapper";

        document.querySelector('.stanford-page').insertBefore(wrapper, sliderParent);

        wrapper.appendChild(sliderParent);

        let twoColIndex = 0;
        let index = 1;

        for(const el of allPinnedElements) {
            if(el.querySelectorAll('.layout--layout-paragraphs-two-column')) {
                twoColIndex = index;
            }
            wrapper.appendChild(el);
            index++;
        }

    }

    mm.add({
        isShort: "(max-height: 600px)",
        isTall: "(min-height: 601px)"
    }, 
    (context) => {

        const backgroundSections = document.querySelectorAll('.su-pinned-background-section');

        if(backgroundSections) {

            for(const section of backgroundSections) {
                    
                let slideTLs = [];

                const pinnedSection = document.getElementById(section.getAttribute('id'));
                const topNegativeMargin = section.classList.contains('top-negative-margin');
                const slides = pinnedSection.querySelectorAll('.su-pinned-slide');
                const headerHeight = document.querySelector('.su-masthead').offsetHeight;

                if(section.getAttribute('first')) {
                    isFirstSection = true;
                }
                
                let { isShort, isTall } = context.conditions;

                let x = 1;

                for(const slide of slides) {

                    if(slide.getAttribute('data-type') == 'video') {

                        bgPlayer = slide.querySelector('.su-pinned-slide__bg-video-wrapper video');

                        slide.querySelector('.su-pinned-slide__play_pause').addEventListener('click', (e) => playPause(e, slide, bgPlayer));
                        slide.querySelector('.su-pinned-slide__play_pause').addEventListener('keyup', (e) => playPause(e, slide, bgPlayer));

                    }

                    let end;

                    if(x < slides.length && x > 1) {
                        end = "+=130%";
                    } else if(x == 1) {
                        end = "+=100%";
                    } else {
                        end = "+=50%";
                    }

                    slide.setAttribute('index', (x - 1));

                    let slideTL;
                    if(isTall) {
                        if(topNegativeMargin) {
                            pinnedSection.style.marginTop = -headerHeight + 'px';
                        } else {
                            pinnedSection.style.marginTop = 0 + 'px';
                        }
                        slideTL = gsap.timeline({
                            scrollTrigger: {
                                trigger: slide,
                                start: "top top",
                                end: end,
                                toggleActions: "play none none none",
                                pin: section.classList.contains('not-pinned') ? false : true,
                                pinSpacing: x < slides.length ? false : true // important so the next slide overlaps instead of pushing
                            }
                        });
                    } else {
                        pinnedSection.style.marginTop = 0 + 'px';
                        slideTL = gsap.timeline({
                            scrollTrigger: {
                                trigger: slide,
                                start: "top top",
                                toggleActions: "play none none none",
                                pin: false,
                            }
                        });
                    }

                    let initTL = gsap.timeline({});

                    slideTL.timeScale(0.5);
                    initTL.timeScale(0.5);

                    slideTLs.push(slideTL);

                    ScrollTrigger.config({autoRefreshEvents: "visibilitychange,DOMContentLoaded,load"})

                    if(slide.classList.contains('first')) {
                        let slideContent = slide.querySelector('.su-pinned-slide__inner__content');
                        initTL.fromTo(slideContent, { x: '30%', opacity: 0 }, { x: 0, opacity: 1, duration: 0.4, ease: 'power2.inOut' });
                        initTL.fromTo(slideContent, { clipPath: 'inset(0% 99% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.5, ease: 'power2.inOut' }, '-=0.1');

                        let elToSlide = [];

                        let title = slide.querySelector('h2');

                        if(title) {
                            elToSlide.push(title);
                        }

                        let p = slide.querySelector('p');

                        if(p) {
                            elToSlide.push(p);
                        }

                        let btn = slide.querySelector('.su-pinned-slide__button');

                        if(btn) {
                            elToSlide.push(btn);
                        }
                        initTL.fromTo(elToSlide, { x: 40 }, { x: 0, stagger: 0.05, duration: 0.4, ease: 'power2.out' }, '-=0.4');
                    } else {
                        let slideContent = slide.querySelector('.su-pinned-slide__inner__content');
                        slideTL.fromTo(slideContent, { x: '30%', opacity: 0 }, { x: 0, opacity: 1, duration: 0.4, ease: 'power2.inOut' }, '+=0');
                        slideTL.fromTo(slideContent, { clipPath: 'inset(0% 99% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.5, ease: 'power2.inOut' }, '-=0.1');

                        let elToSlide = [];

                        let title = slide.querySelector('h2');

                        if(title) {
                            elToSlide.push(title);
                        }

                        let p = slide.querySelector('p');

                        if(p) {
                            elToSlide.push(p);
                        }

                        let btn = slide.querySelector('.su-pinned-slide__button');

                        if(btn) {
                            elToSlide.push(btn);
                        }
                        slideTL.fromTo(elToSlide, { x: 40 }, { x: 0, stagger: 0.05, duration: 0.4, ease: 'power2.out' }, '-=0.4');
                    }

                    // add event listener for focus element inside the section
                    section.addEventListener('focusin', (event) => {
                        let focusedElement = event.target;
                        let slide = focusedElement.closest('.su-pinned-slide');

                        if(slide) {
                            let index = slide.getAttribute('index');

                            gsap.to(window, { 
                                duration: 0.5,
                                scrollTo: {
                                    y: slideTLs[index].scrollTrigger.start
                                },
                                ease: "power2.inOut"
                            });
                        }
                    });

                    x++; 

                }

            }

        }

        if(pinnedSection) {

            const slides = pinnedSection.querySelectorAll('.su-department-slide');
            const track = pinnedSection.querySelector('.su-departments-slider__track');
            let pinnedTL, trackOffsetTween;

            let isLandscapeMobile = window.innerHeight < 550 && window.innerHeight < window.innerWidth;

            console.log('isLandscapeMobile', isLandscapeMobile)
            
            pinnedTL = gsap.timeline({
                scrollTrigger: {
                    trigger: isLandscapeMobile ? track : pinnedSection,
                    pin: wrapper, // pin the trigger element while active
                    start: isLandscapeMobile ? 'top-=20 top' : 'top top', // when the top of the trigger hits the top of the viewport
                    end: `+=${(250 * slides.length) + 150}`, // end after scrolling 500px beyond the start
                    scrub: 1, // smooth scrubbing, takes 1 second to "catch up" to the scrollbar
                    pinSpacing: true,
                    invalidateOnRefresh: true
                }
            });

            let x = 1;

            for(const slide of slides) {
                slide.setAttribute('index', x);

                x++;
            }

            const trackOffset = () => {

                let trackRect = track.getBoundingClientRect();
                let lastSlideRect = document.querySelector('.su-department-slide:last-child').getBoundingClientRect();

                // return the distance outside the track
                return lastSlideRect.right - trackRect.right;
            }
            
            trackOffsetTween = pinnedTL.to(track, { x: () => -trackOffset(), duration: slides.length, ease: 'none', overwrite: "auto" }, '+=0');

            pinnedTL.to({}, { duration: 0.6 }, '+=0');

            window.addEventListener('resize', resizeDepartments);
            
            // add event listener for focus element inside the section
            pinnedSection.addEventListener('focusin', (event) => {
                let focusedElement = event.target;
                let slide = focusedElement.closest('.su-department-slide');

                if(slide) {
                    let index = slide.getAttribute('index');
                    let pos = 0;
                    if(index == slides.length) {
                        pos = pinnedTL.scrollTrigger.end + 1;
                    } else if(index > 1) {
                        pos = pinnedTL.scrollTrigger.start + (250 * index);
                    } else {
                        pos = pinnedTL.scrollTrigger.start;
                    }

                    gsap.to(window, { 
                        duration: 0.5,
                        scrollTo: {
                            y: pos,
                        },
                        ease: "power2.inOut"
                    });
                }
            });

        }

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
                            once: true
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
                            once: true
                        }
                    }
                );
            }
        }
        for(const section of document.querySelectorAll('.layout--layout-paragraphs-three-column')) {
            if(section.querySelectorAll('.su-card').length) {
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
                            once: true
                        }
                    }
                );
            }
        }
            
        for(const section of document.querySelectorAll(".layout--layout-paragraphs-two-column")) {
            if(section.querySelectorAll('.su-card').length) {
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
                            once: true
                        }
                    }
                );
            }
        }

    });

});

function playPause(e, slide, bgPlayer) {
    if(e.type === 'click') {
        var icon = e.target;
        if(icon.classList.contains('pause')) {
            icon.classList.remove('pause');
            icon.querySelector('.pause').style.display = "block";
            icon.querySelector('.play').style.display = "none";
            icon.setAttribute('aria-label', 'Pause the header video');
            bgPlayer.play();
        } else {
            icon.classList.add('pause');
            icon.querySelector('.play').style.display = "block";
            icon.querySelector('.pause').style.display = "none";
            icon.setAttribute('aria-label', 'Play the header video');
            bgPlayer.pause();
        }
    }
}

function resizeDepartments() {

    ScrollTrigger.refresh();

}

function getNextSiblings(elem, limit = 0) {
    var sibs = [];
    let x = 0;
    while (elem = elem.nextSibling) {
        if (elem.nodeType === 3) continue; // text node
        if(limit > 0 && x >= limit) break;
        sibs.push(elem);
        x++;
    }
    return sibs;
}