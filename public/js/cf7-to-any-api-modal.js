function camelCaseToTitleCase(str) {
    // Insert a space before all caps and trim the resulting string
    str = str.replace(/([A-Z])/g, ' $1').trim();

    // Capitalize the first letter of each word
    return str.replace(/\b\w/g, function(l){ return l.toUpperCase() });
}

function call_apimarket_service(success, error, data) {
    $.ajax({
        url: apimarket_ajax.ajax_url,
        type: 'post',
        data: {
            action: 'apimarket_ajax_handler',
            nonce: apimarket_ajax.nonce,
            form: data
        },
        dataType   : 'json',
        success,
        error
    });
}

function async_apimarket_service(data) {
    return new Promise((resolve, reject) => call_apimarket_service(resolve, reject, data));
}

jQuery(document).ready(function($) {
    const apimarket_spinner = '#apimarket-modal-spinner';
    const apimarket_modal = '#apimarket-modal';

    $('#apimarket-modal-preview').on('click', function(event) {
        event.preventDefault();
        const form = $(this).closest('form');
        form.validate();
        if (!form.valid())
            return;
        $(apimarket_modal).show();
        $(apimarket_spinner).css("visibility", "visible");
        const $table = $("#apimarket-pretty-table");
        $(apimarket_spinner).css("visibility", "hidden");
        $table.empty();
        call_apimarket_service((response) => {
            Object.entries(
                response.data
            ).forEach(([key, value]) => {
                const row = "<tr><th>" + camelCaseToTitleCase(key.replaceAll("_", " ")) + "</th><td>" + value + "</td></tr>";
                $table.append(row);
            });
        }, () => $(apimarket_spinner).css("visibility", "hidden"), form.serialize());
    });

    $('.close-modal').on('click', function() {
        $(apimarket_modal).hide();
    });


});