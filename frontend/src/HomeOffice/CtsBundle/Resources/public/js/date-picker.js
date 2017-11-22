(function ($) {
    'use strict';

    $.fn.DatePicker = function () {
        this.hide();

        this.each(function() {
            var originalDateTime = $(this);
            var originalDateDay = $("select[name$='[day]']", originalDateTime);
            var originalDateMonth = $("select[name$='[month]']", originalDateTime);
            var originalDateYear = $("select[name$='[year]']", originalDateTime);

            var dateInput = $("<input type=\"text\" class=\"datejs text short\" />");

            var currentDay = originalDateDay.val();
            var currentMonth = originalDateMonth.val();
            var currentYear = originalDateYear.val();

            if (currentDay.length == 1) {
                currentDay = "0" + currentDay;
            }

            if (currentMonth.length == 1) {
                currentMonth = "0" + currentMonth;
            }

            var currentDate = currentDay + "/" + currentMonth + "/" + currentYear;

            if (currentDate.match(/^\d\d\/\d\d\/\d\d\d\d$/)) {
                dateInput.val(currentDate);
            }

            dateInput.datepicker({ dateFormat: "dd/mm/yy" });

            dateInput.change(function(eventObject) {
                $([originalDateDay, originalDateMonth, originalDateYear]).each(function() {
                    $(this).val("");
                });

                if (dateInput.val().match(/^\d\d\/\d\d\/\d\d\d\d$/)) {
                    var splitDate = dateInput.val().split("/");

                    // These are forced to be parsed in base 10 because otherwise dates with a text bigger than 8
                    // but less than the 10 are returned as 0, meaning they aren't saved
                    $(originalDateDay).val(parseInt(splitDate[0], 10));
                    $(originalDateMonth).val(parseInt(splitDate[1], 10));
                    $(originalDateYear).val(parseInt(splitDate[2], 10));
                }
            });

            originalDateTime.after(dateInput);
            $('<button class="calendar-button" title="date picker"></button>').insertAfter(dateInput).on('click', function(e) {
                e.preventDefault();
                $(this).prev('input').focus();
            });
        });
    };

    return this;
})(jQuery);