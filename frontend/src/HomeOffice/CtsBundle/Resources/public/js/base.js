(function() {
    "use strict";
    var $minutesPanel, $button, $body = $('body');

    if ($('select[data-placeholder="Select Quality Review Outcome"]')) {

        var sel = '#' + $('select[data-placeholder="Select Quality Review Outcome"]').attr('id');

        $(sel).bind('change', function(){

            var options = $(this).val();

            if ('No errors' == options) {
                $(this).find('option').each(function(){
                    if (-1 == $.inArray($(this).val(), options)) {
                        $(this).attr('disabled', true);
                    }
                });
            } else {
                $(this).find('option').each(function(){
                    if ($(this).val() == options || -1 == $.inArray($(this).val(), options)) {
                        $(this).removeAttr('disabled');
                    }

                    if (null != options && 'No errors' == $(this).val()) {
                        $(this).attr('disabled', true);
                    }
                });
            }
            $(this).trigger('chosen:updated');
        });
    }

    if ($('input.filters-option').length) {
        $('input.filters-option').on('click', function() {
            var $filters = new Array();

            $.each($('input.filters-option'), function(index, element){
                if ($(element).is(':checked')) {
                    $filters.push('filters[]=' + $(element).val());
                }
            });

            if ($filters.length) {
                location.href = location.pathname + '?' + $filters.join('&') + '#filter_minutes';
            } else {
                //We hide the minutes and the print minutes buttons
                $('div#filter_minutes').next('ul').toggle();
                $('div#filter_minutes').parent().find('span.button').find('a.printMinutes').toggle();
            }
        });

        //If js is off, this will not execute
        $('.minutes-no-js').each(function(){
            $(this).hide();
        });
    }


    if ($('.allocation-link').length) {
        $('.allocation-link').on('click', function (e) {
            e.preventDefault();

            var $container = $(this).closest('span');

            var $params = {
                allocation :{
                    allocateTo: $container.data('allocate-to'),
                    assignedUnit: $container.data('allocate-unit'),
                    assignedTeam: $container.data('allocate-team'),
                    assignedUser: null,
                    id: $container.data('id'),
                    correspondenceType: $container.data('correspondence-type'),
                    statusChange: $container.data('status-change')
                }
            };

            $.ajax({
                url: $container.data('target'),
                method: 'POST',
                data : $params,
                success: function(){
                    document.location.href = $container.data('return-path');
                }
            })


        });
    }

    $('.dateSelect').DatePicker();

    // dropdowns
    $('ul.selector').link_list_to_selector();
    if ($body.hasClass('search')) {
        $('.global-search-fields').find('select').chosen(); // exclude case specific dropdowns for the sake of IE
    } else {
        $("#filter_field select, select:visible").chosen();
    }
    $('.hide_show').hideShow();

    // tooltips
    $('.results-table *[title]').tipsy({
        opacity: 1,
        offset: -8
    });

    // upload widget
    $('input[type=file]').not('.dont-replace').each(function() {
        $(this).styleUpload();
    });

    $('.calendar-button').addTodayButton();

    if ($body.hasClass('allocate')) {
        // hide Select buttons
        $('#allocation_SetUnit, #allocation_SetTeam').css('display', 'none');

        // radio button toggles whether controls are enabled/disabled
        $('form[name="allocation"]')
            .toggleGreyout('Me', '.allocate_submit_button')
            .on('submit', function(e) {
                //e.preventDefault();
                var $form = $(this);

                // actually send empty fields to prevent server error
                $form
                    .find('select')
                    .removeAttr('disabled');

            });

        // hide allocation select buttons and set the change of dropdowns to
        // activate the click of the relevant button
        $body.on('change', '#allocation_assignedUnit, #allocation_assignedTeam', function() {
            var $select = $(this),
                $button = $select.closest('div').find('button[type=submit]'),
                buttonName = $button.get(0).name,
                $form = $select.closest('form'),
                data;

            // no test here for HERCULE.blockAjax, as not firing the event seems more dangerous
            HERCULE.ajaxStart('Updating...', $form);

            $form
                .find('select')
                .removeAttr('disabled');

            data = $form.serializeArray();
            data.push({
                'name': buttonName
            });

            $.ajax({
                'url': $form.attr('action'),
                'method': 'post',
                'data': data,
                'success': function(html) {
                    $('#allocate').replaceWith($(html).find('#allocate'));

                    // this event is handled inside the toggleGreyout plugin
                    $.event.trigger({
                        type: "updateAllocatePanel"
                    });
                    $('#allocation_SetUnit, #allocation_SetTeam').css('display', 'none');
                    $('#allocate select').chosen();
                    HERCULE.ajaxEnd($form);
                },
                'error': function() {
                    alert('error');
                }
            });
        });

    }

    if ($body.hasClass('detailed-document')) {
        if ($('[name$=ctsCaseDocument] .error').length === 0) {
            $('[name$=ctsCaseDocument]').hide();
        }
        $('#add_new_document_button').on('click', function(e) {
            e.preventDefault();
            $('[name$=ctsCaseDocument]').slideToggle(HERCULE.animationSpeeds.panelSlide);
        });

        // hide submit etc.
        $('.upload_version_button, .add_version_button, .add_new_document_button').addClass('hidden-by-js');

        $body
            .ajaxifyForm(
                'td.add-version form, td.delete form',
                'table, .global.error',
                function() {
                    $('.upload_version_button, .add_version_button, .add_new_document_button').addClass('hidden-by-js');
                },
                function() {
                    // put error handling here
                }
            )
            .ajaxifyForm(
                'form[name=ctsCaseDocument]',
                'table, .global.error, #document-form',
                function() {
                    $('.upload_version_button, .add_version_button, .add_new_document_button').addClass('hidden-by-js');
                    var $doc_form = $('form[name=ctsCaseDocument]');

                    // if successful, clear doc upload form and error
                    if ($doc_form.find('.error').length === 0) {
                        $doc_form.get(0).reset();
                        $doc_form.find('select')
                            .trigger('chosen:updated')
                            .end()
                            .find('.filename')
                            .html('');
                        $doc_form.slideUp(HERCULE.animationSpeeds.panelSlide);
                    }
                },
                function() {
                    // put error handling here
                }
            )
            .on('change', '.add_version_button', function() {
                $(this)
                    .closest('form')
                    .find('*[type=submit]')
                    .show()
                    .focus()
                    .click()
                    .hide(); // this bullshit is necessary to circumvent IE security measures against automatic form submission
            })
            .on('click', 'td.version-number a', function(e) {
                e.preventDefault();
                var $link = $(this),
                    $masterRow = $link.closest('tr'),
                    masterId = $masterRow.get(0).id,
                    $others = $('.' + masterId), // old versions already in HTML
                    closedLink = $link.data('closeVersions'),
                    url;

                if ($masterRow.hasClass('open')) {
                    $masterRow.removeClass('open').addClass('closed');
                    $others.collapseTableRows();
                } else {
                    if (HERCULE.blockAjax) {
                        return;
                    }
                    // close any that are open
                    $masterRow.closest('table').find('tr.open').each(function() {
                        $(this).removeClass('open').addClass('closed');
                        $('.' + this.id).collapseTableRows();
                    });

                    if ($others.length > 0) {
                        // some already there so just show them
                        // TODO: decide if we just want to throw these away and update anyway...
                        $masterRow.removeClass('closed').addClass('open');
                        $others.expandTableRows();
                    } else {
                        // otherwise, go get them
                        url = $link.data('showversions');
                        HERCULE.ajaxStart('Retrieving versions...');
                        $.ajax({
                            url: url,
                            method: 'get',
                            success: function(html) {
                                HERCULE.ajaxEnd();
                                $masterRow.removeClass('closed').addClass('open');
                                var $thoseRows = $(html).find('.' + masterId);
                                $masterRow.after($thoseRows);
                                $masterRow.find('.' + masterId)
                                    .hide()
                                    .expandTableRows();
                            }
                        });
                    }
                }

            });

    }

    if ($body.hasClass('view-case')) { // view screen
        // insert print button
        $button = $('<li><span class="subaction"><a href="" onclick="HERCULE.printFull();return false;">Print<\/a><\/span><\/li>');
        $('#header').find('.dropdown').append($button);
        //ajaxify doc upload
        $body.ajaxifyForm('form[name=ctsCaseDocument]', '#doc_list_title, #doc_list, #doc_upload, #minutes_history, .global.error');
        $body.ajaxifyForm('form[name=ctsCaseMinuteType]', '#minutes_history');
    }

    if ($body.hasClass('case')) {
        if ($body.hasClass('new')) { // new case screen
            // automatically progress when case type is chosen
            $('[id$=_SetType]').parent().hide();
            $('select').enhanceSelect(function() {
                $('[id$=_SetType]').trigger('click');
                // TODO: ajax!
            });

            // hide create case button until all fields are completed
            if (!HERCULE.allFieldsCompleted()) {
                $('[id$=_Create]').closest('div').hide();
                $('select,input,textarea').on('change', function() {
                    $('[id$=_Create]').closest('div').toggle(HERCULE.allFieldsCompleted());
                });
            }
        } else if (!$body.hasClass('view-case')) { // edit screen
            $body
                .ajaxifyForm(
                    '#doc_upload button[type=submit]',
                    '#doc_list_title, #doc_list, #doc_upload, #minutes_history, .global.error',
                    null,
                    null,
                    true
                )
                .ajaxifyForm(
                    '#add_minute button[type=submit]',
                    '#minutes_history',
                    null,
                    null,
                    true
                );
        }
    }

    if ($body.hasClass('bulk-create-case')) {
        // test feature is available
        if (!$('input[type=file]').prop('files')) {
            $('body').addClass('system-error');
            $('#form')
                .after(
                    $('<div><h2>Feature not supported</h2>' +
                        '<p>Unfortunately, your browser doesn\'t support bulk file uploading.</p>' +
                        '<p>' +
                        '<span class="button">' +
                        '<a href="/">Home<\/a>' +
                        '<\/span>' +
                        '<\/p>' +
                        '<\/div>'
                    )
                )
                .remove();

        // hide create case button until all fields are completed
        } else if (!HERCULE.allFieldsCompleted($('#form fieldset'), 'select,input')) {
            $('[id$=_UploadAndCreate]').closest('div').hide();
            $('select,input,textarea').on('change', function () {
                $('[id$=_UploadAndCreate]').closest('div').toggle(HERCULE.allFieldsCompleted($('#form fieldset'), 'select,input'));
            });
        }
    }

    if ($body.hasClass('standard-lines')) {
        // hide upload version button until standard line file has been selected
        if (!$('#ctsCaseStandardLine_file').val()) {
            $('[id$=_uploadVersionButton]').closest('div').hide();
            $('#ctsCaseStandardLine_file').on('change', function() {
                $('[id$=_uploadVersionButton]').closest('div').toggle($('#ctsCaseStandardLine_file').val());
            });
        }
    }

    if ($body.hasClass('upload')) {
        if (!HERCULE.allFieldsCompleted()) {
            $('[id$=_uploadButton]').closest('div').hide();
            $('input[type=file]').on('change', function() {
                $('[id$=_uploadButton]').closest('div').toggle(HERCULE.allFieldsCompleted());
            });
        }
    }

    $minutesPanel = $('#minutes_history');
    if ($minutesPanel.length > 0) {
        $minutesPanel.on('click', '.printMinutes', function(e) {
            e.preventDefault();
            HERCULE.printMinutes();
        });
    }

    if ($body.hasClass('search')) {
        $('#search_details').on('click', '#gs_clear', function(e) {
            e.preventDefault();
            HERCULE.clearSearchFields('.advanced-search-fields', false);
            HERCULE.clearSearchFields('.global-search-fields', true);
            HERCULE.advancedSearch(
                $('#gs_correspondenceType').val(),
                HERCULE.animationSpeeds.panelSlide,
                false
            );
        });

        $("#search_details").on('click', "[id^='gs_clear']", function(e) {
            e.preventDefault();
            HERCULE.clearSearchFields('.advanced-search-fields', true);
        });

        HERCULE.advancedSearch($('#gs_correspondenceType').val(), HERCULE.animationSpeeds.zero, false);
        $('#gs_correspondenceType').on('change', function() {
            HERCULE.advancedSearch($('#gs_correspondenceType').val(), HERCULE.animationSpeeds.panelSlide, true);
        });
    }

    $('.transfer_department_name').setShowHideCondition('#ctsPqCase_receivedType', 'ctsPqCase[receivedType]', 'Transfer');
    $('.cabinet_office_guidance').setShowHideCondition('#ctsPqCase_roundRobin', 'ctsPqCase[roundRobin]', '1');

    if (window.stopLists) {
        $('.stop_list').handleStopLists(window.stopLists);
    }

    if (window.memberAddress && window.memberSpecificDetails) {
        $('.member_list').handleMemberLists(window.memberAddress, window.memberSpecificDetails);
    }

    if (window.memberPartyList) {
        $('.member_list').handleMemberPartyLists(window.memberPartyList);
    }

    if ($('.markup-unit').length) {
        $('.markup-unit').on('change', function(e) {
            $(this).caseTopicListFilter(false);
        });
        $('.markup-unit').caseTopicListFilter(true);
    }

    if ($('.associated-unit').length) {
        $('.associated-unit').on('change', function(e) {
            $(this).standardLineTopicListFilter();
        });
    }

    if ($('.markup-decision').length) {
        $('.markup-decision').on('change', function(e) {
            $(this).ogdNameFilter();
        });
        $('.markup-decision').ogdNameFilter();
    }

    if ($('.hmpo-type-of-correspondent').length) {
        $('.hmpo-type-of-correspondent').on('change', function(e) {
            $(this).hmpoCorrespondentTypeFilter();
        });
        $('.hmpo-type-of-correspondent').hmpoCorrespondentTypeFilter();
    }

    if ($('#hmpo_applicant_address_same_as_correspondent_com').length) {
        $('#hmpo_applicant_address_same_as_correspondent_com').click(function() {
            $('#ctsHmpoComCase_applicantPostcode').val($('#ctsHmpoComCase_correspondentPostcode').val());
            $('#ctsHmpoComCase_applicantAddressLine1').val($('#ctsHmpoComCase_correspondentAddressLine1').val());
            $('#ctsHmpoComCase_applicantAddressLine2').val($('#ctsHmpoComCase_correspondentAddressLine2').val());
            $('#ctsHmpoComCase_applicantAddressLine3').val($('#ctsHmpoComCase_correspondentAddressLine3').val());
            $('#ctsHmpoComCase_applicantCountry').val($('#ctsHmpoComCase_correspondentCountry').val());
            $("#ctsHmpoComCase_applicantCountry").trigger("chosen:updated");
        });
    }

    if ($('#hmpo_complainant_address_same_as_correspondent').length) {
        $('#hmpo_complainant_address_same_as_correspondent').click(function() {
            $('#ctsHmpoComCase_complainantPostcode').val($('#ctsHmpoComCase_correspondentPostcode').val());
            $('#ctsHmpoComCase_complainantAddressLine1').val($('#ctsHmpoComCase_correspondentAddressLine1').val());
            $('#ctsHmpoComCase_complainantAddressLine2').val($('#ctsHmpoComCase_correspondentAddressLine2').val());
            $('#ctsHmpoComCase_complainantAddressLine3').val($('#ctsHmpoComCase_correspondentAddressLine3').val());
            $('#ctsHmpoComCase_complainantCountry').val($('#ctsHmpoComCase_correspondentCountry').val());
            $("#ctsHmpoComCase_complainantCountry").trigger("chosen:updated");
        });
    }

    if ($('#hmpo_applicant_address_same_as_correspondent_gen').length) {
        $('#hmpo_applicant_address_same_as_correspondent_gen').click(function() {
            $('#ctsHmpoGenCase_applicantPostcode').val($('#ctsHmpoGenCase_correspondentPostcode').val());
            $('#ctsHmpoGenCase_applicantAddressLine1').val($('#ctsHmpoGenCase_correspondentAddressLine1').val());
            $('#ctsHmpoGenCase_applicantAddressLine2').val($('#ctsHmpoGenCase_correspondentAddressLine2').val());
            $('#ctsHmpoGenCase_applicantAddressLine3').val($('#ctsHmpoGenCase_correspondentAddressLine3').val());
            $('#ctsHmpoGenCase_applicantCountry').val($('#ctsHmpoGenCase_correspondentCountry').val());
            $("#ctsHmpoGenCase_applicantCountry").trigger("chosen:updated");
        });
    }

    $('.display-with-script').each(function() {
        $(this).show();
    });

    if ($('.foi_pit_extension_checkbox').length) {
        $('.foi_pit_extension_checkbox').on('change', function(e) {
            $(this).foiPitShowHide();
        });
        $('.foi_pit_extension_checkbox').foiPitShowHide();
    }

    $("#delete-button").click(function() {
        if (HERCULE.confirm("Are you sure you want to delete this case?")) {
            $("#delete-button").attr("href", "query.php?ACTION=delete&ID='1'");
        }
        else {
            return false;
        }
    });

    $("#signout-button").click(function() {
        return HERCULE.confirm("Are you sure you want to sign out?");
    });

    $(".grouped_case_remove").click(function() {
        return HERCULE.confirm("Are you sure you want to ungroup this case?");
    });

    $('.doc-template-delete-form').submit(function(e) {
        var c = HERCULE.confirm('Deleting this template cannot be undone.\n\nAre you sure you would like to continue?');
        c ? $(this).find("button#form_delete").prop('disabled', true) : null;
        return c;
    });

    $('.bulk-document-delete-form').submit(function(e) {
        var c = HERCULE.confirm('Deleting this document cannot be undone.\n\nAre you sure you would like to continue?');
        c ? $(this).find("button#form_delete").prop('disabled', true) : null;
        return c;
    });

    if ($('#save_warning_message').length > 0) {
        var c = HERCULE.confirm("The following is unsaved: " + '\n' + $('#save_warning_message').text() + '\n' + "Do you wish to continue without saving this work?");
        if (c) {
            $('.linked-hrns-class').val("");
            $('.grouped-uins-class').val("");
            $('.minute-content-class').val("");
            $('.document-upload-class').val("");
            $("#save-button button").click();
        }
    }

    // preview image
    if ($('#doc_preview').length > 0 || $('#doc_preview_box').length > 0) {
        var $rows = $('#doc_case_table').find('tr'),
            $firstRow, path, name, j;

        // choose original
        for (j=$rows.length; j>0; j-- ) { // skip header
            $firstRow = $rows.eq(j);
            if (j==1 || $firstRow.find('td.type').text() == 'Original') {
                path = $firstRow.find('td.preview a').attr('href');
                name = $firstRow.find('td.name a').text();
                break;
            }
        }

        HERCULE.showPreview(path, name); // click first one

        $("body").on('click', ".preview-image", function(e) {
            e.preventDefault();
            var fileName = $(e.target)
                                .closest('td')
                                .prevAll('.name')
                                .find('a')
                                .text();
            HERCULE.showPreview(e.target.href, fileName);
        });
    }

    $('.dropdown')
        .find('a, button')
        .bind('blur', function(e) {
            console.log('left '+ e.target);
            $(e.target).closest('.dropdown').removeClass('open');
        })
        .bind('focus', function(e) {
            console.log('hit '+ e.target);
            $(e.target).closest('.dropdown').addClass('open');
        })
        .end()
        .find('.toggler')
        .bind('click', function(e) {
            console.log('clicked toggler');
            e.preventDefault();
            var $dropdown = $(e.target).closest('.dropdown');
            $dropdown.toggleClass('latch');
            if ($dropdown.hasClass('latch')) {
                $dropdown.addClass('open');
            } else {
                $dropdown.removeClass('open');
            }
        });
        $('form[name="view_case_type"]').one('submit', function(){
            $(this).find('button[id^="view_case_type_NextStateButton"]').attr('onclick','this.style.opacity = "0.6"; return false;');
        });

        /* PQ Quick Search */
        $('#search-results-head').hide();
        $('#gs_pqPageNext').hide();
        $('#gs_pqPagePrevious').hide();

        /* Search Click */
        $('#gs_pqSearchButton').click(function() {

            clearResultsTable();
            $('#search-results-head').hide();
            $('#gs_pqPageNext').hide();
            $('#gs_pqPagePrevious').hide();
            $('.search-no-results').hide();

            /* set skip back to 0 */
            var skip = 0
            $('#gs_skipValue').val(skip);

            var params = getSearchParams(),
                container = $(this).closest('span');

            /* Set hidden input as the uin value */
            $('#uinValue').val($($('#gs_uin').val()).val());

            $.ajax({
                url: container.data('target'),
                method: 'POST',
                data: params,
                success: function(data) {
                    searchComplete(data, skip);
                }
            });

        });

        /* Next Click */
        $('#gs_pqPageNext').click(function() {

            /* set skip to + 20 */
            var skip = parseInt($('#gs_skipValue').val());
            skip += 20;
            $('#gs_skipValue').val(skip);

            /* if uin is different to previously searched when next is clicked,
             * turn the uin back to the value it was */
            if($('#gs_uin').val() !== $('#uinValue').val()) {
                $('#gs_uin').val($('#uinValue').val());
            }

            var params = getSearchParams(),
                container = $(this).closest('span');

            $.ajax({
                url: container.data('target'),
                method: 'POST',
                data: params,
                success: function(data) {
                    searchComplete(data, skip);
                }
            });

        });

        /* Previous Click */
    $('#gs_pqPagePrevious').click(function() {

        /* set skip to - 20 */
        var skip = parseInt($('#gs_skipValue').val());
        skip -= 20;
        $('#gs_skipValue').val(skip);

        /* if uin is different to previously searched when next is clicked,
         * turn the uin back to the value it was */
        if($('#gs_uin').val() !== $('#uinValue').val()) {
            $('#gs_uin').val($('#uinValue').val());
        }

        var params = getSearchParams(),
            container = $(this).closest('span');

        $.ajax({
            url: container.data('target'),
            method: 'POST',
            data: params,
            success: function(data) {
                searchComplete(data, skip);
            }
        });

    });



})();

function searchComplete(data, skip){
    /* Remove old results */
    clearResultsTable();

    /* Get target path for UIN link to case */
    var path = '/cts/cases/view/';

    if (data.length > 0){

        /* Show table header */
        $('#search-results-head').show();

        /* Add the results to table */
        $.each(data, function(index, value) {

            if(index < 20) {
                var nodeRef = value.NodeId;

                $('.results-table').append("<tr id='results-row-" + index + "'>" +
                    "<td>" +
                    "<span>" +
                    "<a href='" + path + nodeRef + "'>" + value.UIN + "</a>" +
                    "</span>" +
                    "</td>" +
                    "<td>" + getDeadline(value) + "</td>" +
                    "<td>" + getMember(value) + "</td>" +
                    "<td>" + getUnit(value) + "</td>" +
                    "<td>"+ getTopic(value) + "</td>" +
                    "<td>" + getOwner(value) + "</td>" +
                    "<td>" + getTask(value) + "</td>" +
                    "</tr>");
            }
        });
    } else {
        /* No Results Found */
        $('.results-table').append("<div class='search-no-results'>" +
                "<span>No results found.</span>" +
                "</div>");
    }

    /* show Next button if more than 20 results */
    if(data.length > 20) {
        $('#gs_pqPageNext').show();
    } else {
        $('#gs_pqPageNext').hide();
    }

    /* show Previous button if skipped */
    if(skip > 0) {
        $('#gs_pqPagePrevious').show();
    } else {
        $('#gs_pqPagePrevious').hide();
    }

}

function getSearchParams(){
    var pqTypeRadio = $('#gs_pqType').children(':input'),
        pqType = '',
        uin = $('#gs_uin').val(),
        skip = $('#gs_skipValue').val(),
        pageSize = 21;

    /* Gets PQ Type */
    $.each(pqTypeRadio, function(index, value) {
        if ($('#gs_pqType_' + index).is(':checked')) {
            pqType = $('#gs_pqType_' + index).val();
            return false;
        }
    });

    /* Create JSON params to POST */
    var params = {
            params: {
                correspondenceType: pqType,
                uin: uin,
                skip: skip,
                pageSize: pageSize
            }
        };

    return params;
};

function clearResultsTable(){
    $('.results-table > tbody > tr').each(function(index, value) {
        /* Don't delete header */
        if ($(this).attr("id") == 'search-results-head') {
            return true;
        } else {
            var row = index - 1;
            $('#results-row-' + row).remove();
        }
    });
}

function getUIN(value) {if(value.UIN) {return value.UIN} else {return ''} }

function getDeadline(value) {if(value.Deadline) {return value.Deadline} else {return ''} }

function getUnit(value) {if(value.Unit) {return value.Unit} else {return ''} }

function getOwner(value) {if(value.Owner) {return value.Owner} else {return ''} }

function getTask(value) {if(value.Task) {return value.Task} else {return ''} }

function getMember(value) {if(value.Member) {return value.Member} else {return ''} }

function getTopic(value) {if(value.Topic) {return value.Topic} else {return ''} }

