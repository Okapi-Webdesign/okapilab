$('document').ready(function () {
    $('.select2').not('.modal .select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
    });

    $('.select2-api').not('.modal .select2-api').each(function () {
        target = $(this).data('api-target');

        $(this).select2({
            ajax: {
                url: '/assets/ajax/select/' + target + '.php',
                type: 'GET',
                delay: 250,
                data: function (param) {
                    return {
                        q: param.term
                    };
                },
                processResults: function (data) {
                    console.log(data); // Logolja a visszaérkezett adatokat a konzolra
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: 'Válasszon egy lehetőséget!',
            language: 'hu',
            allowClear: true,
            theme: 'bootstrap-5',
            width: '100%',
        });
    });

    $('.modal').on('show.bs.modal', function () {
        $('.modal .select2').each(function () {
            $(this).select2({
                dropdownParent: $('.modal'),
                theme: 'bootstrap-5',
                width: '100%',
            });
        });

        $('.modal .select2-api').each(function () {
            target = $(this).data('api-target');

            $(this).select2({
                dropdownParent: $('.modal'),
                ajax: {
                    url: '/assets/ajax/select/' + target + '.php',
                    type: 'GET',
                    delay: 250,
                    data: function (param) {
                        return {
                            q: param.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Válasszon egy lehetőséget!',
                language: 'hu',
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%',
            });
        });
    });
});