$(function() {
    $('.selectpicker').selectpicker({
        style: 'btn-select',
        iconBase: 'fa',
        tickIcon: 'fa-check',
    });
    $('.selectpicker-live').each(function() {
        if ($(this).data('live-url')) {
            var url = $(this).data('live-url')
            $(this).selectpicker({
                style: 'btn-select',
                iconBase: 'fa',
                tickIcon: 'fa-check',
                liveSearch: true
            }).ajaxSelectPicker({
                ajax: {
                    url: url,
                    data: function () {
                        var params = {
                            q: '{{{q}}}'
                        };
                        return params;
                    }
                },
                preserveSelected: false
            });            
        }
    });

});
