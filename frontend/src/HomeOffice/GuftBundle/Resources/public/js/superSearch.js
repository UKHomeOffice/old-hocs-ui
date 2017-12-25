$('.form-search').submit(function() {
    // Remove all unset form values from the query
    $(this).find('input[type="text"][value=""], select:not(:has(option:selected[value!=""])), button, input[type="radio"]:checked[value=""]').attr('name', '');

    $(this).attr('action', $(this).data('action') + '/results');
});

$('.form-search select[name="businessUnit"]').change(function() {
    var form = $('.form-search');

    var businessUnit = $(this).val().toLowerCase();
    if (businessUnit === '') {
        businessUnit = 'all';
    }

    $.ajax({
        url: form.data('action') + '?businessUnit=' + businessUnit,
        type: 'GET',
        success: function(data) {
            $('.advanced-search').html(data);

            $('.datePicker').DatePicker();
            $('div.todayButton').addTodayButton();
            initializeChosenSelects();
        },
        cache: true,
        error: function(e) {
            //alert("Server failure! Is the server turned off?");
        }
    });
});

$('.form-search select[name="assignedUnit"]').change(function() {

    var form = $('.form-search');
    var assignedUnit = $(this).val().toLowerCase();

    if (assignedUnit !== '') {

        $.ajax({
            url: form.data('action') + '?assignedUnit=' + assignedUnit,
            type: 'GET',
            success: function(data) {

                var $teams = $('.form-search select[name="assignedTeam"]')

                $teams.find('option').remove();
                $teams.append($("<option></option>"));
                $.each(data, function(value, text) {
                    $teams.append($("<option></option>").attr('value', value).text(text));
                });
                $teams.trigger("chosen:updated");

                $teams.prop('disabled', $teams.children('option').length === 1);
                $teams.trigger("chosen:updated");


            },
            cache: true,
            error: function(e) {
                //alert("Server failure! Is the server turned off?");
            }
        });
    }

});