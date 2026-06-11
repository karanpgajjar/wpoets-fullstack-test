(function () {
    'use strict';

    const carouselEl = document.getElementById('dlCarousel');
    const accordionItems = document.querySelectorAll('.dl-accordion-item');

    if (!carouselEl || !accordionItems.length) return;

    const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);

    function syncAccordion(index) {
        accordionItems.forEach(function (item, i) {
            const isActive = i === index;
            item.classList.toggle('is-active', isActive);
            item.setAttribute('aria-pressed', isActive ? 'true' : 'false');

            const plus = item.querySelector('.icon-plus');
            const minus = item.querySelector('.icon-minus');
            if (plus) plus.classList.toggle('d-none', isActive);
            if (minus) minus.classList.toggle('d-none', !isActive);

            const panel = item.querySelector('.dl-accordion-item__panel');
            if (panel) panel.classList.toggle('is-open', isActive);
        });
    }

    carouselEl.addEventListener('slid.bs.carousel', function (e) {
        syncAccordion(e.to);
    });


    accordionItems.forEach(function (item, i) {
        function activate() {
            bsCarousel.to(i);
            syncAccordion(i);
        }

        item.addEventListener('click', function (e) {
            // Only trigger on header clicks (not links inside the panel)
            if (e.target.closest('a')) return;
            activate();
        });

        item.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                activate();
            }
        });
    });

}());
