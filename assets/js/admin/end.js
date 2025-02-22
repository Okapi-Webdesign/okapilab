/* global bootstrap: false */
(() => {
    'use strict'
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })
})();

$('.sidebarToggler').click(function () {
    $('#sidebar').toggleClass('sidebar-open');
});

$('a').click(function () {
    if ($(this).attr('href') === '#') {
        return;
    }

    if ($(this).attr('target') === '_blank') {
        return;
    }

    if ($(this).attr('href').includes('javascript')) {
        return;
    }

    setTimeout(() => {
        loader_start();
    }, 100);
});
