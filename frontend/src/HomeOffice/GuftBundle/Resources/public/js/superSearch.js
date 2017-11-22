$('.form-search').submit(function() {
    // Remove all unset form values from the query
    $(this).find('input[type="text"][value=""], select:not(:has(option:selected[value!=""])), button, input[type="radio"]:checked[value=""]').attr('name', '');

    $(this).attr('action', $(this).data('action') + '/results');
});

$('.form-search input[name="businessUnit"]').change(function() {
    var form = $('.form-search');

    var businessUnit = $(this).val().toLowerCase();
    if (businessUnit == '') {
        businessUnit = 'all';
    }

    $.ajax({
        url: form.data('action') + '/' + businessUnit,
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