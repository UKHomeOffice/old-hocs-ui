(function ($) {
    'use strict';

    $.fn.DatePicker = function () {
        this.hide();
        $(this).removeClass('datePicker');

        this.each(function() {
            var originalDateTime = $(this);
            var originalDateDay = $("select[name$='[day]']", originalDateTime);
            var originalDateMonth = $("select[name$='[month]']", originalDateTime);
            var originalDateYear = $("select[name$='[year]']", originalDateTime);

            var dateInput = $("<input type=\"text\" class=\"datejs form-control\" />");

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

            var dateTimeOptions = { dateFormat: "dd/mm/yy" };

            if (originalDateTime.hasClass('futureOnly')) {
                dateTimeOptions.minDate = 0;
            }

            if (originalDateTime.hasClass('pastOnly')) {
                dateTimeOptions.maxDate = 0;
            }

            dateInput.datepicker(dateTimeOptions);

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

    $.fn.addTodayButton = function() {
        $(this).removeClass('todayButton');

        this.prevAll('.hasDatepicker').on('change', function() {
            var $this = $(this),
                today = new Date(),
                bits = $this.val().split('/'),
                chosenDateIsToday = today.getDate() == parseInt(bits[0]) && today.getMonth() == parseInt(bits[1]) - 1 && today.getFullYear() == parseInt(bits[2]),
                $checkbox = $this.nextAll('.tinyCheckbox').find('input');
            $checkbox.attr('checked', chosenDateIsToday).addClass('setAutomatically');
        });
        this.each(function() {
            var $todayButton = $('<button class="todayButton button-tertiary">Today<\/button>');
            $(this).next('input').next().after($todayButton);
        });

        function pad(num) {
            // return a string of the number, padding with a leading zero if necessary
            var n = '0' + num.toString();
            return n.slice(-2);
        }

        $('body').on('click', '.todayButton', function(e) {
            e.preventDefault();
            var $dateField = $(this).prevAll('.hasDatepicker'),
                today = new Date(),
                todayAsString = pad(today.getDate()) + '/' + pad(today.getMonth() + 1) + '/' + today.getFullYear();
            $dateField.val(todayAsString).trigger('change');
        });
    };

    return this;
})(jQuery);
