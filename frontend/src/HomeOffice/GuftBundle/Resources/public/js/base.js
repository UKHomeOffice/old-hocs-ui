function ShowHideContent() {
    var self = this;

    self.escapeElementName = function(str) {
        return str.replace(/\[/g, '\\[').replace(/\]/g, '\\]');
    };

    self.showHideRadioToggledContent = function () {
        $(".block-label input[type='radio']").each(function () {

            var $radio = $(this);
            var $radioGroupName = $radio.attr('name');
            var $radioLabel = $radio.parent('label');

            var dataTarget = $radioLabel.attr('data-target');

            // Add ARIA attributes

            // If the data-target attribute is defined
            if (dataTarget) {

                // Set aria-controls
                $radio.attr('aria-controls', dataTarget);

                $radio.on('click', function () {

                    // Select radio buttons in the same group
                    $radio.closest('form').find(".block-label input[name=" + self.escapeElementName($radioGroupName) + "]").each(function () {
                        var $this = $(this);

                        var groupDataTarget = $this.parent('label').attr('data-target');
                        var $groupDataTarget = $('#' + groupDataTarget);

                        // Hide toggled content
                        $groupDataTarget.addClass('js-hidden');
                        // Set aria-expanded and aria-hidden for hidden content
                        $this.attr('aria-expanded', 'false');
                        $groupDataTarget.attr('aria-hidden', 'true');
                    });

                    var $dataTarget = $('#' + dataTarget);
                    $dataTarget.removeClass('js-hidden');
                    // Set aria-expanded and aria-hidden for clicked radio
                    $radio.attr('aria-expanded', 'true');
                    $dataTarget.attr('aria-hidden', 'false');

                });

            } else {
                // If the data-target attribute is undefined for a radio button,
                // hide visible data-target content for radio buttons in the same group

                $radio.on('click', function () {

                    // Select radio buttons in the same group
                    $(".block-label input[name=" + self.escapeElementName($radioGroupName) + "]").each(function () {

                        var groupDataTarget = $(this).parent('label').attr('data-target');
                        var $groupDataTarget = $('#' + groupDataTarget);

                        // Hide toggled content
                        $groupDataTarget.addClass('js-hidden');
                        // Set aria-expanded and aria-hidden for hidden content
                        $(this).attr('aria-expanded', 'false');
                        $groupDataTarget.attr('aria-hidden', 'true');
                    });

                });
            }

        });
    }
    self.showHideCheckboxToggledContent = function () {

        $(".block-label input[type='checkbox']").each(function() {

            var $checkbox = $(this);
            var $checkboxLabel = $(this).parent();

            var $dataTarget = $checkboxLabel.attr('data-target');

            // Add ARIA attributes

            // If the data-target attribute is defined
            if (typeof $dataTarget !== 'undefined' && $dataTarget !== false) {

                // Set aria-controls
                $checkbox.attr('aria-controls', $dataTarget);

                // Set aria-expanded and aria-hidden
                $checkbox.attr('aria-expanded', 'false');
                $('#'+$dataTarget).attr('aria-hidden', 'true');

                // For checkboxes revealing hidden content
                $checkbox.on('click', function() {

                    var state = $(this).attr('aria-expanded') === 'false' ? true : false;

                    // Toggle hidden content
                    $('#'+$dataTarget).toggleClass('js-hidden');

                    // Update aria-expanded and aria-hidden attributes
                    $(this).attr('aria-expanded', state);
                    $('#'+$dataTarget).attr('aria-hidden', !state);

                });
            }

        });
    }
}

allFieldsCompleted = function($scope, items, exclusions) {
    $scope = $scope || $('form');
    items = items || 'select,input,textarea';
    exclusions = exclusions || '';

    var complete = true;
    $scope.find(items).not(exclusions).each(function() {
        if (this.name) {
            // controls inserted by chosen (and other js enhancements)
            // don't/shouldn't have name attributes
            if (this.type === 'radio') {
                complete = complete && $('input[name="'+this.name+'"]:checked').length > 0;
            } else {
                complete = complete && !!$(this).val();
            }
        }
    });

    return complete;
};

copyAddress = function () {
    if ($('.copyAddress').length) {
        $('.copyAddress').on('click', function(e) {
            e.preventDefault();

            var form = $(this).data('form');
            var source = $(this).data('source');
            var target = $(this).data('target');

            copySourceToTarget($('#'+form+'_'+source+'Postcode'), $('#'+form+'_'+target+'Postcode'));
            copySourceToTarget($('#'+form+'_'+source+'AddressLine1'), $('#'+form+'_'+target+'AddressLine1'));
            copySourceToTarget($('#'+form+'_'+source+'AddressLine2'), $('#'+form+'_'+target+'AddressLine2'));
            copySourceToTarget($('#'+form+'_'+source+'AddressLine3'), $('#'+form+'_'+target+'AddressLine3'));
            copySourceToTarget($('#'+form+'_'+source+'Country'), $('#'+form+'_'+target+'Country'));

            $('#'+form+'_'+target+'Country').trigger('chosen:updated');
        });
    }
};

copySourceToTarget = function ($source, $target) {
    if ($source.length && $target.length) {
        if ($source.is('select')) {
            $target.find('option')
                .filter(function (){return this.value === $source.find(":selected").text()})
                .attr('selected', true);
        } else {
            $target.val($source.val());
        }
    }
};

skipToDispatch = function () {
    if ($('.button-skipToDispatch').length) {
        $('.button-skipToDispatch').on('click', function(e) {
            e.preventDefault();

            $(this).prev().trigger('click');
        });
    }
};

togglePhoneResponse = function () {
    var $input = $('input.hmpo-response');
    var $toggle = $('.phoneResponseToggle');

    if ($input.length == 0 || $toggle.length === 0) {
        return;
    }

    $input.on('change', function () {
        var $selected = $('input.hmpo-response:checked');

        if ($selected.val() === 'Phone') {
            $('.phoneResponseToggle').each(function () {
                $(this).show('fast');
                $(this).removeClass('hidden');
            });
            $('.standardResponseToggle').each(function () {
                $(this).hide('fast');
                $(this).addClass('hidden');
            });
        } else {
            $('.phoneResponseToggle').each(function () {
                $(this).hide('fast');
                $(this).addClass('hidden');
            });
            $('.standardResponseToggle').each(function () {
                $(this).show('fast');
                $(this).removeClass('hidden');
            });
        }
    }).trigger('change');
};

toggleOnInputChange = function (selector, values, element, radio, disable) {
    var $input = $(selector);

    if ($input.length == 0) {
        $(element).addClass('hidden');
        return;
    }

    $input.on('change', function () {
        $input = $(selector + (radio === true || $input.attr('type') === 'radio' ? ':checked' : ''));
        if (($input.length == 0 && values.indexOf('') != -1) || values.indexOf($input.val()) != -1 ) {
            $(element).each(function () {
                $(element).show('fast');
                $(element).removeClass('hidden');
                if (disable === true) {
                    $(element).find('input').removeAttr('disabled');
                    $(element).find('select').removeAttr('disabled');
                    $(element).find('textarea').removeAttr('disabled');
                }
            });
        } else {
            $(element).each(function () {
                $(element).hide('fast');
                $(element).addClass('hidden');
                if (disable === true) {
                    $(element).find('input').attr('disabled', 'disabled');
                    $(element).find('select').attr('disabled', 'disabled');
                    $(element).find('textarea').attr('disabled', 'disabled');
                }
            });
        }
        $(element).find('.chosen').trigger("chosen:updated");
        $(element).find('.chosen-full').trigger("chosen:updated");
    }).trigger('change');
};

toggleOnClick = function (selector, element) {
    var $input = $(selector);
    var $element = $(element);

    $input.addClass('button-toggle')
    $input.prepend('<i class="fa fa-plus-square" aria-hidden="true"></i> ');
    $input.on('click', function(e) {
        e.preventDefault();

        toggleElement($element, $input);
    });
};

toggleElement = function($selector, $original) {
    $selector.each(function() {
        var $element = $(this);
        var $icon = $original.find('i');

        if ($element.hasClass('hidden')) {
            $icon.removeClass('fa-plus-square').addClass('fa-minus-square');
            $element.show('fast').removeClass('hidden');
        } else {
            $icon.removeClass('fa-minus-square').addClass('fa-plus-square');
            $element.hide('fast').addClass('hidden');
        }
    });
};

removeCase = function (type, nodeId) {
    var $target = $('.' + type + 'Case_' + nodeId);

    if ($target.siblings().length == 0) {
        $target.closest('div').remove();
    } else {
        $target.remove();
    }
    refreshCaseOverview();
};

updateNode = function(node, content) {
    $(node).html(content);

    refreshCaseOverview();
};

uploadDocumentRefresh = function(formName) {
    if ($('.document-upload-summary').length) {
        $('.document-upload-summary').remove();
    }

    var uploadTrigger = $('.upload'+formName+'Trigger');
    if (uploadTrigger.length) {
        uploadTrigger.trigger('click');
    }

    var documentTable = $('.'+formName+'Table');
    if (documentTable.length) {
        $.ajax({
            type: 'GET',
            url: documentTable.data('refresh-url'),
            data: {
                filterTypes: documentTable.data('filter-types')
            }
        })
        .success(function (data) {
            documentTable.find('tbody tr').remove();
            $.each(data, function (index) {
                documentTable.find('tbody').append(
                    '<tr data-node-ref="' + data[index].nodeRef + '">' +
                    '<td><a href="' + data[index].link + '" target="_blank">' + data[index].name + '</a><br>' + data[index].type + '</td>' +
                    '<td>' + data[index].created + '</td>' +
                    (documentTable.data('allow-remove') ? '<td><a href="#" class="document-remove" data-value="' + data[index].nodeRef + '">Remove</a></td>' : '') +
                    '<td>' +
                    '<a href="' + data[index].docPath + '" data-filename="' + data[index].name + '" class="activate-document-tab">' +
                    (documentTable.data('allow-remove') ? 'Tab' : 'Document tab') +
                    '</a><br>' +
                    '<a href="' + data[index].docPath + '" data-filename="' + data[index].name + '" class="activate-document-frame">Side bar</a>' +
                    '</td>' +
                    '</tr>'
                );
            });
        });
    }
};

topicsReload = function() {

};

removeDocument = function(nodeRef) {
    $('.table-document tr[data-node-ref="'+nodeRef+'"]').remove();
};

refreshCaseOverview = function () {
    $.ajax({
        type: 'GET',
        url: overviewRefreshRoute,
        contentType: false
    })
    .done(function(data) {
        $('#overview').replaceWith(data);
    });
};

closeModal = function () {
    $.modal.close();
    refreshCaseOverview();
};

approveOnBehalf = function() {
    $('.approveOnBehalf').each(function() {
        var $approveOnBehalf = $(this);
        $approveOnBehalf.find('input:checkbox').change(function(){
            if ($(this).is(':checked')) {
                $approveOnBehalf.find('.original button').prop('disabled', true);
                $approveOnBehalf.find('.onBehalf').show('fast');
                $approveOnBehalf.find('.onBehalf').removeClass('hidden');
            } else {
                $approveOnBehalf.find('.original button').prop('disabled', false);
                $approveOnBehalf.find('.onBehalf').hide('fast');
                $approveOnBehalf.find('.onBehalf').addClass('hidden');
            }
        });
    });
};

standardLinesForSelectedTopic = function() {
    $('.markup-topic, .markup-secondary-topic').change(function () {
        var $topic = $(this);
        var $download = $topic.parent().find('.topic-standard-line a');

        $download.attr('href', '#');
        $download.addClass('hidden');
        $download.hide();

        if ($download.length) {
            $download.parent().find('.standard-lines-error').remove();
            if ($topic.val()) {
                $.ajax({
                    type: 'GET',
                    url: $download.data('url'),
                    data: {
                        topic: $topic.val()
                    }
                })
                .success(function (data) {
                    $download.attr('href', '/cts/tools/standard-lines/download/' + data.id);
                    $download.removeClass('hidden');
                    $download.show();
                })
                .error(function () {
                    $download.parent().append("<div class=\"form-read standard-lines-error\">No standard lines linked to this topic</div>");
                });
            }
        }
    }).trigger('change');
}

$.fn.clearSelectWidget = function(blankValue) {
    $(this).html('');
    $(this).append("<option value=\"\">" + blankValue + "</option>");
};

$.fn.setSelectedValue = function(value) {
    if (value !== undefined && value !== '') {
        $(this).val(value);
    }
};

$.fn.handleStopLists = function(stopLists) {
    var $fieldsets = this,
        slideSpeed = 300;

    $fieldsets.each(function() {
        var $fieldset = $(this),
            $select = $fieldset.find('select'),
            $iconDiv = $fieldset.next('.stop_list_div'),
            type = $fieldset.data('stop_list_type'),
            stopList = stopLists[type];

        if ($iconDiv.hasClass('collapsed')) {
            $iconDiv.removeClass('collapsed').css('min-height', 0);
        }

        // browser may have set different value on reload
        showIcon($iconDiv, stopList[$select.val()], 0);

        // attach listener to select
        $select.on('change', function () {
            var value = $(this).val();
            showIcon($iconDiv, stopList[value], slideSpeed);
        });
    });

    function showIcon($iconDiv, flag, speed) {
        if (flag) {
            $iconDiv.animate({'height': '30px'}, speed);
        } else {
            $iconDiv.animate({'height': 0}, speed);
        }
    }
};

loadingOverlay = function(toggle) {
    switch (toggle) {
        case 'on':
            var overlay = ('<div id="ajax-overlay"><img id="ajax-image" src="/bundles/guft/images/ajax-loader.gif" /></div>');
            $(overlay).appendTo('body');
            break;
        case 'off':
            $('#ajax-overlay').remove();
            break;
        default:
            $('$ajax-overlay').remove();
    }
};

showPreview = function(path, fileName) {
    var imageFileExtensions = Array('jpg', 'png', 'gif');
    var fileExtension = fileName ? fileName.split('.').pop().toLowerCase() : '',
        iviewerOptions = {'zoom': '100%', 'src': ''};

    $('#doc_preview').remove();

    $('<div id="doc_preview"></div>').appendTo($('#doc_preview_container'));
    if ($.inArray(fileExtension, imageFileExtensions) > -1) {
        $("#doc_preview").addClass('img-preview');
        iviewerOptions.src = path;
        $("#doc_preview").iviewer(iviewerOptions);
        return;
    }

    $('<object id="doc_preview_box" data="'+path+'" type="application/pdf" width="100%" height="500em">Document preview</object>').
        appendTo($("#doc_preview"));
};

showFrame = function(path, fileName) {
    var imageFileExtensions = ['jpg', 'png', 'gif'];
    var fileExtension = fileName ? fileName.split('.').pop().toLowerCase() : '',
        iviewerOptions = {'zoom': '100%', 'src': ''};

    $('<div id="documentFrame"></div>').appendTo($('#documentFrameContainer'));
    if ($.inArray(fileExtension, imageFileExtensions) > -1) {
        $("#documentFrame").addClass('img-preview');
        iviewerOptions.src = path;
        $("#documentFrame").iviewer(iviewerOptions);
        return;
    }

    var height = $(window).height() - 150;
    $('<object id="documentFrameBox" data="'+path+'" type="application/pdf" width="100%" height="'+height+'px">Document preview</object>').
        appendTo($("#documentFrame"));

    $(window).on('scroll', function() {
       $('.column-document-preview').css('padding-top', $('body').scrollTop() - 180);
    }).trigger('scroll');
};

initializeChosenSelects = function() {
    $("select.chosen").chosen({
        width: "75%",
        disable_search_threshold: 10,
        allow_single_deselect: true
    });

    $("select.chosen-full").chosen({
        width: "100%",
        disable_search_threshold: 10,
        allow_single_deselect: true
    });

    // Chosen work around for adding extra options.
    $('select.flexible-chosen').each(function () {
        var select = $(this);

        select.chosen({no_results_text: 'Press Enter to add:', width: "75%"});
        var chosen = select.data('chosen');
        chosen.dropdown.find('input').on('keyup', function (e) {
            if (e.which == 13 && chosen.dropdown.find('li.no-results').length > 0) {
                select.prepend($("<option>").val(this.value).text(this.value).prop('selected', true))
                    .trigger("chosen:updated");
            }
        });
    });
};

// Workaround for Chrome 54 / FormData issues. TODO FIX
appendHiddenInputOnClick = function() {
    $('body').on('click', 'button', function() {
        var button = $(this);
        window.submitButton = {
            name: button.attr('name'),
            value: button.text(),
            primary: button.hasClass('button-secondary') ? false : true
        };
    });
};

resetChosen = function($element, defaultValue, data) {
    $element.empty();

    if (defaultValue) {
        $element.append('<option selected="selected" value>'+defaultValue+'</option>');
    }

    if (data !== null) {
        $.each(data, function (value, text) {
            $element.append($("<option></option>").attr('value', value).text(text));
        });
    }

    $element.prop('disabled', $element.children('option').length == (defaultValue ? 1 : 0));
    $element.trigger("chosen:updated");
};

applyManualTransition = function(assignedUnit, assignedTeam, assignedUser, transition)
{
    while ($.modal.isActive()) {
        $.modal.close();
    }

    $('.ManualAllocation .assignedUnit').val(assignedUnit);
    $('.ManualAllocation .assignedTeam').val(assignedTeam);
    $('.ManualAllocation .assignedUser').val(assignedUser);

    $('.transition-'+transition).removeClass('button-modal').click()
}

loadDocumentTemplateTables = function() {
    if ($('.documentTemplateTable').length) {
        $('.documentTemplateTable').each(function () {
            var documentTemplateTable = $(this);

            documentTemplateTable.find('tbody tr.placeholder')
                .html('<td colspan="2"><em>Updating...</em></td>')
                .show();

            $.ajax({
                type: 'GET',
                url: documentTemplateTable.data('url')
            })
            .success(function (data) {
                if (data.length == 0) {
                    documentTemplateTable.find('tbody tr.placeholder')
                        .html('<td colspan="2"><em>There are no document templates</em></td>')
                        .show();
                } else {
                    documentTemplateTable.find('tbody tr.placeholder').hide();
                    $.each(data, function (index) {
                        documentTemplateTable.find('tbody').append(
                            '<tr>' +
                            '<td>' + data[index].name + '</td>' +
                            '<td><a href="' + data[index].url + '">Download</a></td>' +
                            '</tr>'
                        );
                    });
                }
            });
        });
    }
};

updatePidExtensionDeadline = function () {
    if ($('.pidDeadline').length) {
        $('.pidDeadline').prevAll('input').on('change', function () {
            // var dateParts = $('.pidDeadline').prevAll('input').val().split("/");
            // var date = new Date(parseInt(dateParts[2]), parseInt(dateParts[1])-1, parseInt(dateParts[0])+20);
            // var deadline = date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear();

            $('.pidDeadline').html('A new deadline date will be set');
        });
    }
};

refreshMarkupDecisions = function () {
    $("body").on('click', "input.foiIsEir", function() {
        $.ajax({
            type: 'GET',
            url: $(this).data('markup-decisions'),
            data: {
                foiIsEir: $('input.foiIsEir:checked').val()
            }
        })
        .success(function (data) {
            var $markupDecision = $('.markup-decision');
            $markupDecision.find('option').remove();
            $markupDecision.append($("<option></option>"));
            $.each(data, function(value, text) {
                $markupDecision.append($("<option></option>").attr('value', value).text(text));
            });
            $markupDecision.trigger("chosen:updated");
        });
    });
};

activateDocumentTab = function(e) {
    e.preventDefault();

    closeDocumentFrame(e);

    $('#ui-id-3').trigger('click');
    $("html, body").animate({ scrollTop: 0 }, "slow");

    showPreview($(this).attr('href'), $(this).data('filename'));
};

activateDocumentFrame = function(e) {
    e.preventDefault();
    $('#documentFrameContainer').empty();
    $('body').addClass('document-preview');

    showFrame($(this).attr('href'), $(this).data('filename'));

    $(this).blur();
};

closeDocumentFrame = function (e) {
    e.preventDefault();

    $('#documentFrameContainer').empty();
    $('body').removeClass('document-preview');
};

handleButtonTarget = function() {
    $('body').on('click', '.button-target', function(e) {
        e.preventDefault();

        var $target = $('#'+$(this).data('target'));
        if ($target.length !== 0) {
            $target.click();
        }
    });
};

refreshTopics = function () {
    $("body").on('change', "select.associatedUnit", function() {
        var $selectedUnit = $('select.associatedUnit option:selected');

        var $topics = $('select.associatedTopic');
        // Empty the topics chosen
        $topics.find('optgroup').remove();
        $topics.find('option').remove();
        $topics.prop('disabled', true);
        $topics.val('').trigger("chosen:updated");

        if ($selectedUnit.val() === "" && $(this).data('allow-empty-search') !== 1) {
            // nothing selected disable the topics chosen field
            return;
        }

        $.ajax({
            type: 'GET',
            url: $(this).data('refresh-url'),
            data: {unit: $selectedUnit.val()}
        })
            .success(function (data) {
                if (Object.keys(data).length > 0) {
                    // Populate the topics chosen
                    $.each(data, function (group, topic) {
                        var $optgroup = $('<optgroup></optgroup>').attr('label', group);
                        $.each(topic, function (value, text) {
                            $optgroup.append($('<option></option>').attr('value', value).text(text));
                        });

                        $topics.append($optgroup);
                    });
                } else {
                    // No topics found, show disabled option
                    $topics.append($('<option></option>').prop('disabled', true).text('There are no available topics'));
                }

                $topics.prop('disabled', false);
                $topics.val('').trigger("chosen:updated");
            });
    });
};

$(document).ready(function() {
    // Each() does feel nasty here, but something is blocking first() using a lambda
    $(".preview-image").each(function() {
        showPreview($(this).attr('href'), $(this).attr('data-filename'));
        return false;
    });

    $("body").on('click', ".preview-image", function() {
        showPreview($(this).attr('href'), $(this).attr('data-filename'));
        return false;
    });

    toggleOnInputChange('.memberParliamentTrigger', ['1'], '.memberParliamentToggle', true);
    toggleOnInputChange('.memberParliamentTrigger', ['0'], '.correspondentToggle', true);
    toggleOnInputChange('.receivedTypeTrigger', ['Transfer'], '.receivedTypeToggle', true);
    toggleOnInputChange('.roundRobinTrigger', ['1'], '.roundRobbinToggle', true);
    toggleOnInputChange('.markup-decision', [
        'Allocate to policy unit',
        'Send to draft',
        'Allocate to draft',
        'Policy response',
        'FAQ',
        'Already in public domain - S21',
        'Fee threshold invoked - S12',
        'Information not held',
        'Information released in full',
        'Information released in part',
        'Information withheld in full',
        'Neither confirm nor deny for all info',
        'Neither confirm nor deny for some info',
        'Repeat request - S14', 'Request unclear',
        'Request vexatious - S14',
        'Original decision upheld',
        'Original decision overturned',
        'Original decision upheld in part',
        'Internal review withdrawn',
    ], '.markupAllocateToggle', false, true);

    toggleOnInputChange('.markup-decision', ['Withdraw question', 'No reply needed'], '.markupCancelToggle', false, true);
    toggleOnInputChange('.markup-decision', ['Refer to OGD', 'Refer to DCU'], '.markupReferToggle', false, true);
    toggleOnInputChange('.markup-decision', ['Written response'], '.markupWrittenResponseToggle');
    toggleOnInputChange('.markup-decision', [
        'Information withheld in full',
        'Information released in part',
        'Neither confirm nor deny for all info',
        'Neither confirm nor deny for some info'
    ], '.markupExemptionsToggle');

    toggleOnInputChange('.signOffMinisterTrigger', ['1'], '.signOffMinisterToggle', true);
    togglePhoneResponse();
    toggleOnInputChange('.individualHouseholdTrigger', ['1'], '.leadersAddressAboardToggle', true);
    toggleOnInputChange('.deliveryTypeTrigger', ['1'], '.deliveryTypeToggle', true);

    toggleOnInputChange('.typeOfRepresentativeTrigger', ['Complainant', 'Rep. of complainant', 'Third party'], '.typeOfRepresentativeToggle');
    toggleOnInputChange('.typeOfRepresentativeTrigger', ['Third party'], '.consentToggle');

    toggleOnInputChange('.secondaryTypeOfRepresentativeTrigger', ['Complainant', 'Rep. of complainant', 'Third party'], '.secondaryTypeOfRepresentativeToggle');
    toggleOnInputChange('.secondaryTypeOfRepresentativeTrigger', ['Third party'], '.secondaryConsentToggle');

    toggleOnInputChange('.thirdPartyTypeOfRepresentativeTrigger', ['Complainant', 'Rep. of complainant', 'Third party'], '.thirdPartyTypeOfRepresentativeToggle');
    toggleOnInputChange('.thirdPartyTypeOfRepresentativeTrigger', ['Third party'], '.thirdPartyConsentToggle');

    toggleOnInputChange('input.newFileTrigger', ['1'], '.newFileToggle', true);

    toggleOnInputChange('input.bringUpDateTrigger', ['1'], '.bringUpDateOnToggle', true);
    toggleOnInputChange('input.bringUpDateTrigger', ['0'], '.bringUpDateOffToggle', true);
    toggleOnInputChange('.passportStatusTrigger', ['0'], '.bringUpDateToggle', true);
    toggleOnInputChange('.passportStatusTrigger', ['1'], '.generatePasswordToggle', true);

    toggleOnInputChange('input.hmpoRefundTrigger', ['1'], '.hmpoRefundToggle', true);

    toggleOnInputChange('.deferDispatchTrigger', ['1'], '.deferDispatchToggle', true);
    toggleOnInputChange('.amendmentsTrigger', ['1'], '.amendmentsToggle', true);
    if ($('.replyToEmailToggle').length) {
        toggleOnInputChange('input.hmpo-response', ['Email'], '.replyToEmailToggle', true);
    }
    toggleOnInputChange('.publicInterestTestTrigger', ['1'], '.publicInterestTestToggle', true);

    toggleOnClick('.editResponseTrigger', '.editResponseToggle');
    toggleOnClick('.uploaddocumentTrigger', '.uploaddocumentToggle');
    toggleOnClick('.uploaddocumentPassportTrigger', '.uploaddocumentPassportToggle');
    toggleOnClick('.uploadresponsePhoneTrigger', '.uploadresponsePhoneToggle');
    toggleOnClick('.replyToMemberTrigger', '.replyToMemberToggle');
    toggleOnClick('.secondTopicTrigger', '.secondTopicToggle');
    if ($('.markup-topic-secondary').length && $('.markup-topic-secondary option:selected').val() != "") {
        $('.secondTopicShow').trigger('click');
    }

    toggleOnClick('.replyToCorrespondentTrigger', '.replyToCorrespondentToggle', true);
    toggleOnClick('.replyToThirdPartyCorrespondentTrigger', '.replyToThirdPartyCorrespondentToggle', true);

    toggleOnClick('.consultationTrigger', '.consultationToggle', true);

    toggleOnClick('.dashboardTableTrigger', '.dashboardTableToggle');
    toggleOnClick('.secondaryCorrespondentTrigger', '.secondaryCorrespondentToggle');
    toggleOnClick('.tertiaryCorrespondentTrigger', '.tertiaryCorrespondentToggle');

    $('body').ajaxForm('.form-ajax');

    initializeChosenSelects();
    appendHiddenInputOnClick();

    toggleOnClick('.documentTemplateTrigger', '.documentTemplateToggle');
    toggleOnClick('.documentPassportTemplateTrigger', '.documentPassportTemplateToggle');
    loadDocumentTemplateTables();

    $('body').on('click', '.linkedCase-remove', function (e) {
        e.preventDefault();

        $('.linkedCaseToRemove').val($(this).data('value'));
        $('.removeLinkedCase').trigger('click');
    });

    $('body').on('click', '.groupedCase-remove', function (e) {
        e.preventDefault();

        $('.groupedCaseToRemove').val($(this).data('value'));
        $('.removeGroupedCase').trigger('click');
    });

    $('body').on('click', '.document-remove', function (e) {
        e.preventDefault();

        $('.documentToRemove').val($(this).data('value'));
        $('.removeDocument').trigger('click');
    });

    $('body').on('click', '.button-modal', function (e) {
        e.preventDefault();

        $.get($(this).data('href'), function (html) {
            $(html).appendTo('body').modal({
                clickClose: false,
                showClose: false
            });
            initializeChosenSelects();
        });
    });

    $('body').on('click', '.allocation-link', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var cell = $(this).closest('td');
        var originalHtml = cell.html();
        var username = $(this).data('username');
        cell.text("Allocating...");
        $.ajax({
                    'url': href,
                    'cache': false,
                    'success': function(data) {
                        cell.text(username);

                    },
                    'error': function() {
                        cell.html(originalHtml.replace(/Allocate to Me/,'[Allocation Failed]'));
                    }
                });
        });

    $('.member_list select').on('change', function () {
        var value = $(this).val();

        if (window.memberPartyList) {
            $(".member_constituency input").val(window.memberPartyList.memberConstituency[value]);
            $(".member_party input").val(window.memberPartyList.memberParty[value]);
        }

        if (window.memberAddress) {
            if (value) {
                if (memberSpecificDetails.isLords[value] === true) {
                    $(".member_postcode input").val(memberAddress.lordsPostcode);
                    $(".member_address1 input").val(memberAddress.lordsAddress1);
                    $(".member_address2 input").val(memberAddress.lordsAddress2);
                    $(".member_address3 input").val('');
                    $(".member_country select").val(memberAddress.lordsCountry);
                } else {
                    $(".member_postcode input").val(memberAddress.memberPostcode);
                    $(".member_address1 input").val(memberAddress.memberAddress1);
                    $(".member_address2 input").val(memberAddress.memberAddress2);
                    $(".member_address3 input").val('');
                    $(".member_country select").val(memberAddress.memberCountry);
                }
            }
        }
    });
    if (window.memberPartyList) {
        $('.member_list select').trigger('change');
    }

    $('.member_list select').on('change', function () {
        if (window.memberPartyList) {
            var value = $(this).val();

            $(".member_constituency input").val(window.memberPartyList.memberConstituency[value]);
            $(".member_party input").val(window.memberPartyList.memberParty[value]);
        }
    }).trigger('change');


    var memberListReadOnly = $('.member_list .form-read');
    if (memberListReadOnly.length == 1) {
        var value = memberListReadOnly.html();


        var memberConstituencyReadOnly = $(".member_constituency .form-read");
        if (memberConstituencyReadOnly.length == 1) {
            $(".member_constituency .form-read").html(window.memberPartyList.memberConstituency[value]);
            $(".member_party .form-read").html(window.memberPartyList.memberParty[value]);
        }
    }

    $('body').on('click', '.editResponseSave', function(e) {
        e.preventDefault();
        $('.answerTextDisplay').html($('.answerTextEdit').val());
        $('.editAnswer').trigger('click');
    });

    $('#tabs').tabs();
    $('#tabs').show();

    $('.datePicker').DatePicker();
    $('div.todayButton').addTodayButton();
    updatePidExtensionDeadline();
    handleButtonTarget();

    if (! allFieldsCompleted()) {
        $('#ctsCaseEntry_Create').closest('div').hide();
        $('select,input,textarea,button').on('change click keyup', function() {
            $('#ctsCaseEntry_Create').closest('div').toggle(allFieldsCompleted());
        });
        $('.datePicker').datepicker({
            onSelect: function() {
                $('#ctsCaseEntry_Create').closest('div').toggle(allFieldsCompleted());
            }
        });
    }

    $('.multiselect').multiselect({
        buttonClass: 'button-secondary down-arrow-after',
        buttonWidth: '100%',
        numberDisplayed: 2,
        enableClickableOptGroups: true,
        maxHeight: 250
    });

    $(document).on("click", '.todo_paginate li a', function() {
        var overlay = ('<div id="ajax-overlay"><img id="ajax-image" src="/bundles/guft/images/ajax-loader.gif" /></div>');
        $(overlay).appendTo('body');
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $.get($(this).attr('href'), function(data) {
            $('#ajax-todo').replaceWith(data.ctsQueue);
            $('#ajax-overlay').remove();
        });
        window.history.pushState('', '', $(this).attr('href'));
        return false;
    });

    $('body').on('click', '.expandTrigger', function() {
        $(this).parents().find('.expandTarget').slideToggle('fast');
        return false;
    });

    $('body').on('click', '.multiExpandTrigger', function(){
        var target = "." + $(this).data('target');
        $('.multiExpandTarget').not(target).hide();
        $('.multiExpandTarget').find(':radio').prop('checked', false);
        $('.multiExpandTrigger').closest('label').removeClass('selected');
        $(target).show();
    });

    $('.toggleFile').click(function() {
        var toggle = $(this);
        var target = $(this).attr('data-toggle');

        $(target).trigger('click');
        $(target).on('change', function() {
            toggle.next().html(
                $(target).val().replace(/C:\\fakepath\\/i, '')
            );
        });

        return false;
    });

    $('.initiateCaseSetType').click(function() {
        $('#ctsCaseEntry_correspondenceType').val($('input[name=correspondenceTypeFaux]:checked').val());
        $('.initiateCaseSetTypeTarget').trigger('click');
    });

    $('.onClickSubmit').click(function() {
        $(this).closest('form').submit();
    });

    $('.hideInitiateCaseSubForm').click(function() {
        $('#initiateCaseSubForm').hide();
    });

    if ($('.approveOnBehalf').length) {
        approveOnBehalf();
    }

    skipToDispatch();
    copyAddress();
    standardLinesForSelectedTopic();

    $('body').on('click', '.hideCaseAllocateColleague', function() {
        $('.caseAllocateColleague').hide();
    });

    // Use GOV.UK selection-buttons.js to set selected
    // and focused states for block labels
    var $blockLabels = $(".block-label input[type='radio'], .block-label input[type='checkbox']");
    new GOVUK.SelectionButtons($blockLabels);

    // Details/summary polyfill
    // See /javascripts/vendor/details.polyfill.js

    // Where .block-label uses the data-target attribute
    // to toggle hidden content
    var toggleContent = new ShowHideContent();
    toggleContent.showHideRadioToggledContent();
    toggleContent.showHideCheckboxToggledContent();

    $('.submitOnSelect').on('change', function() {
        $(this).closest('form').submit();
    });


    $('body').on('change', '.showOnSelect', function () {
        element = $(this).attr('data-toggle');
        if ($(this).is(':checked')) {
            $('.' + element).show();
        } else {
            $('.' + element).hide();
        }
    });

    var $markupUnit = $('.markup-unit');
    if ($markupUnit.length) {
        $('body').on('change', '.markup-unit', function () {
            var $this = $(this);

            var $markupTeam = $('.markup-team');
            if ($markupTeam.length == 0) {
                // We only care if the form has markup team
                return;
            }

            resetChosen($markupTeam, 'Select team', null);

            $.ajax({
                'url': '/cts/cases/parts/markup/teams',
                'method': 'GET',
                'data': {
                    'unit': $this.val()
                },
                'success': function (data) {
                    resetChosen($markupTeam, 'Select team', data);
                },
                'error': function () {
                    resetChosen($markupTeam, 'Select team', null);
                }
            });
        });
    }

    refreshMarkupDecisions();
    refreshTopics();


    $('body').on('click', '.activate-document-tab', activateDocumentTab);

    $('body').on('click', '.activate-document-frame', activateDocumentFrame);

    $('body').on('click', '.close-document-frame', closeDocumentFrame);

    $('body').on('change', '.caseAllocateFilter, .form-allocate-unit, .form-allocate-team', function() {
        var $select = $(this);

        if ($select.closest('.caseAllocateGroup').find('.caseAllocateFilter').prop("type") == 'hidden') {
            var $formAllocateFilter = $select.closest('.caseAllocateGroup').find('.caseAllocateFilter');
        } else {
            var $formAllocateFilter = $select.closest('.caseAllocateGroup').find('.caseAllocateFilter:checked');
        }

        var $formAllocateUnit = $select.closest('.caseAllocateGroup').find('.form-allocate-unit');
        var $formAllocateTeam = $select.closest('.caseAllocateGroup').find('.form-allocate-team');
        var $formAllocateUser = $select.closest('.caseAllocateGroup').find('.form-allocate-user');

        if ($select.hasClass('caseAllocateFilter')) {
            $formAllocateUnit.empty();
            $formAllocateUnit.append('<option selected="selected" value>Please select a unit</option>');
            $formAllocateUnit.prop('disabled', $formAllocateUnit.children('option').length == 1);
            $formAllocateUnit.trigger("chosen:updated");
        }
        if ($select.hasClass('form-allocate-unit') || $select.hasClass('caseAllocateFilter')) {
            $formAllocateTeam.empty();
            $formAllocateTeam.append('<option selected="selected" value>Please select a team</option>');
            $formAllocateTeam.prop('disabled', $formAllocateTeam.children('option').length == 1);
            $formAllocateTeam.trigger("chosen:updated");

            $formAllocateUser.empty();
            $formAllocateUser.append('<option selected="selected" value>Please select a user</option>');
            $formAllocateUser.prop('disabled', $formAllocateUser.children('option').length == 1);
            $formAllocateUser.trigger("chosen:updated");
        }

        $.ajax({
            'url': '/cts/cases/parts/allocate',
            'method': 'POST',
            'data': {
                filter: ($formAllocateFilter.val() == '0' ? 'colleague' : 'me'),
                unit: $formAllocateUnit.val(),
                team: $formAllocateTeam.val()
            },
            'success': function(data) {
                if (data.units !== 'undefined' && $select.hasClass('caseAllocateFilter')) {
                    $formAllocateUnit.empty();
                    $formAllocateUnit.append('<option selected="selected" value>Please select a unit</option>');
                    $.each(data.units, function(value, text) {
                        $formAllocateUnit.append($("<option></option>").attr('value', value).text(text));
                    });
                    $formAllocateUnit.prop('disabled', $formAllocateUnit.children('option').length == 1);
                    $formAllocateUnit.trigger("chosen:updated");
                }

                if (data.teams !== 'undefined' && $select.hasClass('form-allocate-unit')) {
                    $formAllocateTeam.empty();
                    $formAllocateTeam.append('<option selected="selected" value>Please select a team</option>');
                    $.each(data.teams, function(value, text) {
                        $formAllocateTeam.append($("<option></option>").attr('value', value).text(text));
                    });
                    $formAllocateTeam.prop('disabled', $formAllocateTeam.children('option').length == 1);
                    $formAllocateTeam.trigger("chosen:updated");
                }

                $formAllocateUser.empty();
                $formAllocateUser.append('<option selected="selected" value>Please select a user</option>');
                if (data.people !== 'undefined' && data.people.length != 0) {
                    $.each(data.people, function(value, text) {
                        $formAllocateUser.append($("<option></option>").attr('value', value).text(text));
                    });
                }
                $formAllocateUser.prop('disabled', $formAllocateUser.children('option').length == 1);
                $formAllocateUser.trigger("chosen:updated");
            },
            'error': function() {

            }
        });
    });

});

$(window).load(function() {

    // Only set focus for the error example pages
    if ($(".js-error-example").length) {

        // If there is an error summary, set focus to the summary
        if ($(".error-summary").length) {
            $(".error-summary").focus();
            $(".error-summary a").click(function(e) {
                e.preventDefault();
                var href = $(this).attr("href");
                $(href).focus();
            });
        }
        // Otherwise, set focus to the field with the error
        else {
            $(".error input:first").focus();
        }
    }
});

$.views.settings.delimiters("{$", "$}");
