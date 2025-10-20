gsap.registerPlugin(ScrollTrigger);
gsap.registerPlugin(SplitText);

window.addEventListener('load', function() {

    const pinnedSection = document.querySelector('.su-departments-slider');

    if(pinnedSection) {

        const slides = pinnedSection.querySelectorAll('.su-department-slide');
        const track = pinnedSection.querySelector('.su-departments-slider__track');
        let pinnedTL, trackOffsetTween;

        const sliderParent = pinnedSection.closest('.paragraph-item');

        const allPinnedElements = getNextSiblings(sliderParent, 3);

        let wrapper = document.createElement('div');
        wrapper.id = "paragraph-pin-wrapper";

        document.querySelector('.stanford-page').insertBefore(wrapper, sliderParent);

        wrapper.appendChild(sliderParent);

        for(const el of allPinnedElements) {
            wrapper.appendChild(el);
        }

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
        
        /*for(const twoCol of document.querySelectorAll(".layout--layout-paragraphs-two-column")) {
            let twoColTL = gsap.timeline({
                paused: true,
                scrollTrigger: {
                    trigger: pinnedSection,
                    start: `+=${(250 * slides.length) + 350}`,
                    toggleActions: "play none none none",
                    pin: false,
                }
            });
            twoColTL.fromTo(twoCol.querySelectorAll(".su-card"), { opacity: 0, y: 80 }, { opacity: 1, y: 0, duration: .6, delay: .2, stagger: 0.2, ease: 'power2.easeOut' });
        }*/

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

});