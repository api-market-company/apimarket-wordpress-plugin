function camelCaseToTitleCase(str) {
    // Insert a space before all caps and trim the resulting string
    str = str.replace(/([A-Z])/g, ' $1').trim();

    // Capitalize the first letter of each word
    return str.replace(/\b\w/g, function(l){ return l.toUpperCase() });
}

jQuery(document).ready(function($) {

    $('#preview').on('click', function(event) {
        event.preventDefault();
        var form = $(this).closest('form');
        form.validate();
        if (!form.valid())
            return;
        $('#apimarket-modal').show();
        const $table = $("#apimarket-pretty-table");
        $table.empty();
        $.ajax({
            url: apimarket_ajax.ajax_url,
            type: 'post',
            data: {
                action: 'apimarket_ajax_handler',
                nonce: apimarket_ajax.nonce,
                form: form.serialize()
            },
            dataType   : 'json',
            success: function(response) {
                Object.entries(
                    response.data
                ).forEach(([key, value]) => {
                    const row = "<tr><th>" + camelCaseToTitleCase(key) + "</th><td>" + value + "</td></tr>";
                    $table.append(row);
                });
            }
        });
    });

    // Close the Modal
    $('.close-modal').on('click', function() {
        $('#apimarket-modal').hide();
    });


});