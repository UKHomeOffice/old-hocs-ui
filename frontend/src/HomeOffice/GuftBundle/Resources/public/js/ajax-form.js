$.fn.ajaxForm = function(selector) {
    var $form;

    var handleSuccess = function(data) {
        clearErrors();
        redirectForm(data);

        if (typeof data.message !== 'undefined' && data.message !== '') {
            $form.prepend('<div class="success-summary"><p>' + data.message + '</p></div>');
        }

        if (typeof data.callback !== 'undefined' && data.callback !== '') {
            var func = new Function(data.callback);
            func();
        }

        loadingOverlay('off');
    };

    var redirectForm = function (data) {
        if (typeof data !== 'undefined' && typeof data.redirect !== 'undefined') {
            if (data.redirect.charAt(0) == '#') {
                $('html, body').scrollTop($("a[name='" + data.redirect.substring(1) + "']").offset().top)
            } else {
                window.location.href = data.redirect;
            }
        }
    };

    var handleFail = function(jqXHR, status) {
        // Todo: all ajax requests need to be extracted out and made neater.
        if (status === 'timeout') {
            $('#timeOutError').html();
            $('form.form-ajax').prepend(
                '<div class="error-summary" role="group" tabindex="-1" id="timeOutError">' +
                '   <h1>Network timeout</h1>' +
                '   <p>The network has timed out, please try again or if the problem persists, contact your systems ' +
                '       administrator.</p>' +
                '</div>'
            );

            loadingOverlay('off');
            $('html, body').scrollTop($("a[name='content_top']").offset().top);
            return;
        }

        if (jqXHR.status === 500) {
            $('#500Error').html();
            $('form.form-ajax').prepend(
                '<div class="error-summary" role="group" tabindex="-1" id="500Error">' +
                '   <h1>' + jqXHR.statusText + '</h1>' +
                '   <p>A system error has occured, please try again or contact your systems ' +
                '       administrator.</p>' +
                '</div>'
            );

            loadingOverlay('off');
            $('html, body').scrollTop($("a[name='content_top']").offset().top);
            return;
        }




        if (jqXHR.status === 403) {
            window.location.reload();
        }

        clearErrors();
        redirectForm(jqXHR.responseJSON);

        if (! $form.hasClass('form-modal')) {
            $form.prepend($.templates("#error-summary-template").render(jqXHR.responseJSON));
        }

        if (typeof jqXHR.responseJSON.errors !== 'undefined') {
            if (typeof jqXHR.responseJSON.errors.fields !== 'undefined') {
                var fieldErrors = jqXHR.responseJSON.errors.fields;
                for (var fieldError in fieldErrors) {
                    if (fieldErrors.hasOwnProperty(fieldError)) {
                        $('#' + fieldError).closest('.form-group').addClass('error');
                        $('#' + fieldError).before('<span class="error-message"><a name="error_' + fieldError + '"></a>' + fieldErrors[fieldError] + '</span>');
                    }
                }
            }
        }

        loadingOverlay('off');
    };

    var clearErrors = function() {
        $form.find('.error').removeClass('error');
        $form.find('.error-message').remove();
        $form.find('.error-summary').remove();
        $form.find('.success-summary').remove();
        $form.find('.form-success').remove();
        $form.find('#uploadFileName').empty();
        $form.find('input[type=file]').val('');
        $form.find('.reset').val('');
    };

    var maybeYouWantedToUploadADocumentWarning = function() {
        var caseType = $form.data('casetype');



        if (window.submitButton && window.submitButton.primary !== false && // You're clicking a green button
            window.submitButton.value !== 'Link' && window.submitButton.value !== 'Group' && // You're not clicking Link or Group
            (caseType == 'IMCB' || caseType == 'IMCM' || caseType ==  'UTEN') && // The case wants to see warnings
            $('.uploaddocumentTrigger').length && // We can upload documents
            $('.no-documents-warning').length && // We have not already uploaded a document
            !$('.document-upload-summary').length // We have not already alerted the user
        ) {
            $('html, body').scrollTop($("a[name='content_top']").offset().top);
            $form.find('.success-summary').remove();
            $form.prepend('<div class="notice-summary document-upload-summary"><h2>You have not uploaded a document</h2><p>You can still continue and submit this form.</p></div>');

            return true;
        }

        return false;
    };

    this.on('submit', selector, function(e) {
        e.preventDefault();

        var formData = new FormData(this)
        if (typeof window.submitButton != "undefined") {
            if (typeof window.submitButton.value == "undefined") {
                window.submitButton.value = "";
            }
            if (typeof window.submitButton.name != "undefined") {
                formData.append(window.submitButton.name, window.submitButton.value)
            }
        }

        $form = $(this);

        if (maybeYouWantedToUploadADocumentWarning()) {
            return;
        }

        loadingOverlay('on');
        $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                timeout: 30000
            })
            .done(handleSuccess)
            .fail(handleFail, status);
    });


    return this;
};
