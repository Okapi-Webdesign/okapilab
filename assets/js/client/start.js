const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
        toast.addEventListener('click', Swal.close)
    }
});

function modal_open(modal_slug, attr) {
    $.ajax({
        url: '/assets/modal/ugyfel/' + modal_slug + '.php',
        type: 'POST',
        data: attr,
        success: function (re) {
            $('#modalContent').html(re);
            $('#modal').modal('show');
        }
    });
}

function loader_start() {
    $('#loader').css('display', 'flex');
    $('body').css('overflow', 'hidden');
}

function loader_stop() {
    $('#loader').css('display', 'none');
    $('body').css('overflow', 'auto');
}