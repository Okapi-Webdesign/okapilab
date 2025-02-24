/* global bootstrap: false */
(() => {
    'use strict'
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })
})();

$('a').click(function () {
    if ($(this).attr('href') === '#') {
        return;
    }

    if ($(this).attr('target') === '_blank' || $(this).attr('target') === 'blank') {
        return;
    }

    if ($(this).attr('href').includes('javascript')) {
        return;
    }

    setTimeout(() => {
        loader_start();
    }, 100);
});
