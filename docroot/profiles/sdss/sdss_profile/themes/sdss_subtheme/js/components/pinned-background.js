gsap.registerPlugin(ScrollTrigger);
gsap.registerPlugin(ScrollToPlugin);

window.addEventListener('load', function() {

    const backgroundSections = document.querySelectorAll('.su-pinned-background-section');

    if(backgroundSections) {

        for(const section of backgroundSections) {
            
            let slideTLs = [];

            const pinnedSection = document.getElementById(section.getAttribute('id'));
            const slides = pinnedSection.querySelectorAll('.su-pinned-slide');
            const headerHeight = document.querySelector('.su-masthead').offsetHeight;

            if(section.getAttribute('first')) {
                isFirstSection = true;
            }

            pinnedSection.style.marginTop = -headerHeight + 'px';

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

            let slideTL = gsap.timeline({
                scrollTrigger: {
                    trigger: slide,
                    start: "top top",
                    end: end,
                    toggleActions: "play none none none",
                    pin: true,
                    pinSpacing: x < slides.length ? false : true // important so the next slide overlaps instead of pushing
                }
            });

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

            x++;

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

        }

    }

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