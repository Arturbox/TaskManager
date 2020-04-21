jQuery(document).ready(function($){
    // Clicking the save button on the open modal for both CREATE and UPDATE
    $("#SaveData").submit(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        var formMessages = $('#form-messages');
        var formData = getFormData($(this));
        var type = formData._method;
        var ajaxurl = '/tasks';
        if (type == "PUT") {
            ajaxurl = '/tasks/' + formData.id;
        }
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (response) {
                formMessages.removeClass('alert-danger');
                formMessages.addClass('alert-success');
                // Set the message text.
                $(formMessages).text(response.data.message);
                formMessages.show();
            },
            error: function (data) {
                formMessages.removeClass('alert-success');
                formMessages.addClass('alert-danger');
                $(formMessages).text(response.data.message);
                formMessages.show();
            }
        });
    });


    jQuery('.delete-task').click(function (e) {
        e.preventDefault();
        var formMessages = $('#form-messages');
        var formData = getFormData($(this));
        var id = formData.id;
        var $this = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "DELETE",
            url: 'tasks/' + id,
            success: function (response) {
                formMessages.removeClass('alert-danger');
                formMessages.addClass('alert-success');
                // Set the message text.
                $(formMessages).text(response.data.message);
                formMessages.show();
                $this.parent().parent().remove();

            },
            error: function (data) {
                formMessages.removeClass('alert-success');
                formMessages.addClass('alert-danger');
                $(formMessages).text(response.data.message);
                formMessages.show();
            }
        });
    });
});

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}
