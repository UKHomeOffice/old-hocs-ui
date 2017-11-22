(function($) {
    "use strict";

    /**
     * convert list of links into a select
     * @returns {*}
     */
    $.fn.link_list_to_selector = function() {
        var $elt = this,
            links = [],
            chain = $elt.find('li a').each(function() {
                var linkElt = this;
                links.push({
                    'text': linkElt.innerHTML,
                    'url' : linkElt.href,
                    'selected': $(linkElt)
                        .closest('li')
                        .hasClass('selected')
                });
            }),
            $newSelect = $('<select class="'+ $elt.attr('class') +'" id="'+ $elt.attr('id') +'_enhanced"><\/select>'),
            $oldLabel = $elt.prev(),
            $newLabel = $('<label for="'+ $newSelect.attr('id') +'">'+ $oldLabel.text() +'<\/label>'),
            j, $opt;
        $newSelect.addClass('link_selector');
        $oldLabel.replaceWith($newLabel);
        $elt.after($newSelect);
        $elt.remove();

        for (j=0; j<links.length; j++) {
            $opt = $('<option value="'+ links[j]['url'] +'">' + links[j]['text'] + '<\/option>');
            if (links[j]['selected']) {
                $opt.attr('selected', 'selected');
            }
            $newSelect.append($opt);
        }

        if (!$newSelect.val() && links.length > 0) {
            $newSelect.val(links[0]['url']);
        }
        $newSelect.enhanceSelect(function(elt) {
                var newUrl = elt.value;
                window.location.replace(newUrl);
            });

        return chain;
    };

    // http://www.themaninblue.com/experiment/AccessibleSelect/
    $.fn.enhanceSelect = function (callback) {
        function selectChanged(theElement) {
            var theSelect;

            if (theElement && theElement.value) {
                theSelect = theElement;
            } else {
                theSelect = this;
            }

            /* if (!theSelect.changed) {
                return false;
            }*/

            if (callback) {
                callback(theSelect);
            }
            return true;
        }

        function selectClicked() {
            this.changed = true;
        }

        function selectFocussed() {
            this.initValue = this.value;
            return true;
        }

        function selectKeyed(e) {
            var theEvent;
            var keyCodeTab = "9";
            var keyCodeEnter = "13";
            var keyCodeEsc = "27";

            if (e) {
                theEvent = e;
            } else {
                theEvent = event;
            }

            if ((theEvent.keyCode == keyCodeEnter || theEvent.keyCode == keyCodeTab) && this.value != this.initValue) {
                this.changed = true;
                selectChanged(this);
            } else if (theEvent.keyCode == keyCodeEsc) {
                this.value = this.initValue;
            } else {
                this.changed = false;
            }

            return true;
        }

        var chain = this.each(function() {
            this.changed = false;
            this.onfocus = selectFocussed;
            this.onchange = selectChanged;
            this.onkeydown = selectKeyed;
            this.onclick = selectClicked;
        });
        return chain;
    };

    $.fn.hideShow = function() {
        this.each(function() {
            var $elt = $(this),
                text = $elt.data('hide_show_text'),
                startOpen = $elt.hasClass('startOpen'),
                $revealer = $('<p class="revealer">' + text + '<button class="plusMinus"></button></p>'),
                slideSpeed = 300;
            $elt.before($revealer);
            if (startOpen) {
                $revealer.addClass('open');
            } else {
                $elt.hide();
            }
            
            $('.plusMinus').click(function(e) {
                e.preventDefault();
            });

            $revealer.on('click', function() {
                var $this = $(this);
                if ($this.hasClass('open')) {
                    $this.removeClass('open');
                    $elt.slideUp(slideSpeed);
                } else {
                    $this.addClass('open');
                    $elt.slideDown(slideSpeed);
                }
            });
        });
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
    
    $.fn.handleMemberPartyLists = function(memberPartyList) {
        var $fieldsets = this;

        $fieldsets.each(function() {
            var $fieldset = $(this),
                $select = $fieldset.find('select');

            // attach listener to select
            $select.on('change', function () {
                var value = $(this).val();

                $("#ctsPqCase_constituency").val(memberPartyList.memberConstituency[value]);
                $("#ctsPqCase_party").val(memberPartyList.memberParty[value]);
                
            });
        });
    };
    
    $.fn.handleMemberLists = function(memberList, memberSpecificDetails) {
        var $fieldsets = this;
        $fieldsets.each(function() {
            var $select = $(this).find('select');
            // attach listener to select
            $select.on('change', function () {
                $(".cts_case_member_name input").val($(this).val());
                if (memberSpecificDetails.isLords[$(this).val()] === '1') {
                    $(".cts_case_member_postcode input").val(memberList.lordsPostcode);
                    $(".cts_case_member_address_line_1 input").val(memberList.lordsAddressLine1);
                    $(".cts_case_member_address_line_2 input").val(memberList.lordsAddressLine2);
                    $(".cts_case_member_country input").val(memberList.lordsCountry);
                } else {
                    $(".cts_case_member_postcode input").val(memberList.memberPostcode);
                    $(".cts_case_member_address_line_1 input").val(memberList.memberAddressLine1);
                    $(".cts_case_member_address_line_2 input").val(memberList.memberAddressLine2);
                    $(".cts_case_member_country input").val(memberList.memberCountry);
                }
            });
        });
    };
    
    $.fn.addTodayButton = function() {
        this.prevAll('.hasDatepicker').on('change', function() {
            var $this = $(this),
                today = new Date(),
                bits = $this.val().split('/'),
                chosenDateIsToday = today.getDate() == parseInt(bits[0]) && today.getMonth() == parseInt(bits[1]) - 1 && today.getFullYear() == parseInt(bits[2]),
                $checkbox = $this.nextAll('.tinyCheckbox').find('input');
            $checkbox.attr('checked', chosenDateIsToday).addClass('setAutomatically');
        });
        this.each(function(i) {
            var $elt = $(this),
                $todayButton = $('<span class="button"><button class="todayButton">Today<\/button><\/span>');
            $elt.after($todayButton);
        });

        function pad(num) {
            // return a string of the number, p[adding with a leading zero if necessary
            var n = '0' + num.toString();
            return n.slice(-2);
        }

        $('body').on('click', '.todayButton', function(e) {
            e.preventDefault();
            var $dateField = $(this).parent().prevAll('.hasDatepicker'),
                today = new Date(),
                todayAsString = pad(today.getDate()) + '/' + pad(today.getMonth() + 1) + '/' + today.getFullYear();
            $dateField.val(todayAsString).trigger('change');
        });
    };

    /**
     * Turn on toggling of select active status based on radio button
     * @param greyoutValue string value of radio which causes selects to be deactivated
     * @param button_selector string
     * @param noDefault bool
     * @returns {$.fn}
     */
    $.fn.toggleGreyout = function(greyoutValue, button_selector, noDefault) {
        if (this.length == 0) return this;
        var $form = this,
            $greyoutControl = $form.find('input[value="' + greyoutValue + '"]'),
            alreadyHit;

        /**
         * Enhance multiselects so that only relevant options are available
         * @param button_selector string
         * @param noDefault bool
         * @param forceShowAllocate bool
         */
        function setSelectVisibility(button_selector, noDefault, forceShowAllocate) {
            var $selects = $form.find('select'),
                i, j, $options, $select;

            for (i=$selects.length - 1; i>=0; i--) {
                $select = $selects.eq(i);
                $options = $select.find('option');
                if ((noDefault && $options.length == 0) || $options.length == 1) {

                    if (!forceShowAllocate && i==1) {
                        toggleButton(false);
                    }

                    $select
                        .attr('disabled', 'disabled')
                        .trigger("chosen:updated");

                } else {
                    for (j=i-1; j>=0; j--) {
                        $select = $selects.eq(j);
                    }
                    break;
                }
            }
            if ($selects.eq(0).val()) {
                toggleButton(true);
            }
        }

        /**
         * Make all selects diasabled/enabled by boolean
         * @param toggle
         */
        function greyOut(toggle) {
            if (toggle) {
                $form
                    .find('select')
                    .attr('disabled', 'disabled')
                    .trigger("chosen:updated");

                toggleButton(true);
            } else {
                $form
                    .find('select')
                    .removeAttr('disabled')
                    .trigger("chosen:updated");

                setSelectVisibility(button_selector);
            }
        }

        /**
         * Show/hide allocate button and adjust margin on div above by boolean
         * @param onOff
         */
        function toggleButton(onOff) {
            $form
                .find(button_selector)
                .toggle(onOff)
                .closest('div')
                .prev()
                .toggleClass('last', !onOff);
        }

        alreadyHit = ($greyoutControl.length == 0) ? // no control, probably because user is assigned to multiple teams
            false : $greyoutControl.get(0).checked;

        $form
            .on('change', 'input[type="radio"]', function(){
                greyOut($(this).val() == greyoutValue);
            })
            .on('change', 'select', function() {
                var $select = $(this),
                    $followingDivs = $select.closest('div').nextAll().not('#allocation_allocateTo');

                $followingDivs.each(function() {
                    var $select = $(this).find('select');
                    $select.find('option').not(':first-child').remove();
                    $select.val('');
                    $select.trigger("chosen:updated");
                });

                setSelectVisibility(button_selector);
            });

        $(document).on('updateAllocatePanel', function() {
            $('#allocation_SetUnit, #allocation_SetTeam').css('display', 'none');
            $('#allocate select').chosen();
            setSelectVisibility(button_selector);
        });

        // set initial state
        greyOut(alreadyHit);
        setSelectVisibility(button_selector, noDefault, alreadyHit);
        return $form;
    };
    
    $.fn.setShowHideCondition = function(controlledBySelector, controlledByName, controlledByValue) {
        var $conditionalElem = this;        
        showHide(controlledBySelector, controlledByName, controlledByValue, 0);
        
        $(controlledBySelector).on('change', function() {
            showHide(controlledBySelector, controlledByName, controlledByValue, 200);
        });
        
        function showHide(parentSelector, showName, showValue, speed) {
            var $value = $(parentSelector + " input[name='"+showName+"']:checked").val();
            if ($value === showValue) {
                $conditionalElem.show(speed);
            } else {
                $conditionalElem.hide(speed);
            }
        }
    };
    
    $.fn.clearSelectWidget = function(blankValue) {
        $(this).html('');
        $(this).append("<option value=\"\">" + blankValue + "</option>");
    };
    
    $.fn.setSelectedValue = function(value) {
        if (value !== undefined && value !== '') {
            $(this).val(value);
        }
    };
    
    $.fn.caseTopicListFilter = function(retainSelected) {
        var $selectedTopic, $selectedSecondaryTopic, $unitValue = $(this).val();
        if (retainSelected) {
            $selectedTopic = $('select.markup-topic').val();
            $selectedSecondaryTopic = $('select.markup-secondary-topic').val();
        }
        $('select.markup-topic').clearSelectWidget('Select topic');
        $('select.markup-secondary-topic').clearSelectWidget('Select secondary topic');
        if (topicList.units[$unitValue] !== undefined) {
            var optionsAsString = "";
            for(var i = 0; i < topicList.units[$unitValue].length; i++) {
                optionsAsString += "<option value='" + topicList.units[$unitValue][i] + "'>" + topicList.units[$unitValue][i] + "</option>";
            }
            $('select.markup-topic').append(optionsAsString);
            $('select.markup-secondary-topic').append(optionsAsString);
        }

        if (retainSelected) {
            $('select.markup-topic').setSelectedValue($selectedTopic);
            $('select.markup-secondary-topic').setSelectedValue($selectedSecondaryTopic);
        }
        $("select.markup-topic").trigger("chosen:updated");
        $("select.markup-secondary-topic").trigger("chosen:updated");
        if (window.stopLists) {
            $('.stop_list').handleStopLists(window.stopLists);
        }
    };

    /**
     * replace file upload widget with browse button
     * looks for label and autoupload data attributes on button and form respectively
     */
    $.fn.styleUpload = function() {
        var $upload = this,
            label = $upload.data('label') || 'Browse',
            $form = $upload.closest('form'),
            autosubmit = $form.data('autosubmit') || false,
            $replacement = $('<span><button>' + label + '<\/button><\/span>'),
            $filename = $('<span class="filename">&nbsp;<\/span>'),
            pathlessFilename, errorHeight = 15;

        $upload
            .parent()
            .css('position', 'relative');
        $replacement.addClass('button replacement');
        $upload
            .after($replacement)
            .css({
                'cursor' : 'pointer',
                'height' : $replacement.height() + 'px',
                'left' : '0',
                'opacity' : 0,
                'position' : 'relative',
                'top' : '0',
                'width' : $replacement.width() + 'px',
                'z-index' : 1000
            })
            .on('mouseover focus', function() {
                $replacement.addClass('fake-hover');
            })
            .on('mouseout blur', function () {
                $replacement.removeClass('fake-hover');
            });
        if ($upload.siblings('.error').length) {
            $upload.css('top', '-'+errorHeight+'px');
        }
        $replacement
            .after($filename)
            .css({
                'margin-left' : '-' + $replacement.width() + 'px'
            });

        if (HERCULE.isIE8) {
            $upload
                .css({
                    left: $replacement.width() - $upload.width()
                });
        }
        if ($upload.get(0).value) {
            pathlessFilename = HERCULE.sanitizeInputFileName($upload.get(0).value);
            $filename.text(pathlessFilename);
        }
        $upload
            .on('change', function() {
                // show filename
                var files = $(this).prop('files'),
                    fileCount = files ? files.length : 1;
                if (fileCount === 1) {
                    $filename.text(HERCULE.sanitizeInputFileName(this.value));
                } else {
                    $filename.text(fileCount + ' files');
                }
                if (autosubmit) {
                    $(this)
                        .closest('form')
                        .find('*[type=submit]')
                        .show()
                        .trigger('click')
                        .hide();
                }
            });
    };
    
    $.fn.standardLineTopicListFilter = function() {
        var $unitValue = $(this).val();
        $('select.associated-topic').clearSelectWidget('Select topic');
        if ($unitValue === '') {
            for (var unit in topicList.units) {
                for(var i = 0; i < topicList.units[unit].length; i++) {
                    optionsAsString += "<option value='" + topicList.units[unit][i] + "'>" + topicList.units[unit][i] + "</option>";
                }
            }
            $('select.associated-topic').append(optionsAsString);
        } else {
            if (topicList.units[$unitValue] !== undefined) {
                var optionsAsString = "";
                for(var i = 0; i < topicList.units[$unitValue].length; i++) {
                    optionsAsString += "<option value='" + topicList.units[$unitValue][i] + "'>" + topicList.units[$unitValue][i] + "</option>";
                }
                $('select.associated-topic').append(optionsAsString);
            }
        }
        $("select.associated-topic").trigger("chosen:updated");
    };
    
    $.fn.ogdNameFilter = function() {
        var $ogdNameValue = $(this).val();
        $('.ogd-name-field').hide();

        if ($ogdNameValue === 'Refer to OGD') {
            $('.ogd-name-field').show();
        }
    };
    
    $.fn.hmpoCorrespondentTypeFilter = function() {
        var $correspondentTypeValue = $(this).val();
        $('#applicant_details').hide();
        $('#complainant_details').hide();
        $('.hmpo-type-of-complainant').hide();
        $('.hmpo-type-of-representative').hide();
        $('.hmpo-type-of-third-party').hide();
        $('.hmpo-consent-attached').hide();
        if ($correspondentTypeValue === 'Complainant') {
            $('#applicant_details').show();
            $('.hmpo-type-of-complainant').show();
        }
        if ($correspondentTypeValue === 'Third party') {
            $('#applicant_details').show();
            $('.hmpo-type-of-third-party').show();
            $('.hmpo-consent-attached').show();
        }
        if ($correspondentTypeValue === 'Rep. of complainant') {
            $('#applicant_details').show();
            $('#complainant_details').show();
            $('.hmpo-type-of-representative').show();
        }
    };
    
    $.fn.foiPitShowHide = function() {
        var $pitExtension = $(this);
        if ($pitExtension.is(':checked')) {
            $('#foi-pit .foi_pit_conditional_fields').show();
            $(this).parent('div').removeClass('last');
        } else {
            $('#foi-pit .foi_pit_conditional_fields').hide();
            $(this).parent('div').addClass('last');
        }
    };

    /**
     * hijax the submit event and update its contents by AJAX
     * @param selector string the form to watch for submit events
     * @param updateArea string CSS selector for parts of page to be updated
     * @param updateAction function callback on success
     * @param handleBlank function callback on failure
     */
    $.fn.ajaxifyForm = function(selector, updateArea, updateAction, handleBlank, useClick) {
        var $container = this,
            processId = Math.random().toString().split('.')[1],
            $iframe,
            defaultMessage = 'Working...',
            handleDispatch = function(e, $form, message) {
                //no e.preventDefault() - allow submit to go through normally to iframe
                if (HERCULE.blockAjax) {
                    e.preventDefault();
                    cleanForm();
                    return;
                }
                HERCULE.ajaxStart(message);
                $iframe.removeClass('virgin');
                $form
                    .attr('target', 'fu' + processId)
                    .append('<input type="hidden" name="ajax" value="true">');

                // fall through and submit as normal, but to iframe
            },
            handleResponse = function() {
                cleanForm();
                if ($iframe.hasClass('virgin')) { // nonexistent initial document has "loaded" into iframe :/
                    return;
                }

                // update areas on page with HTML from corresponding nodes in iframe
                var areaSelectors = updateArea.split(/[,\s]+/),
                    $newNode, i, ua, emptyResponse = true;
                for (i=0; i<areaSelectors.length; i++) {
                    ua = areaSelectors[i];
                    if (ua === '') {
                        continue;
                    }
                    $newNode = $iframe.contents().find(ua);
                    if ($newNode.length > 0) {
                        emptyResponse = false;
                        $(ua).each(function(i) {
                            $(this).replaceWith($newNode.eq(i));
                        });
                    }
                }
                if (emptyResponse) {
                    if (handleBlank) {
                        handleBlank($iframe);
                    } else {
                        HERCULE.ajaxMessage($iframe.contents().find('title').text());
                    }
                } else {
                    if (updateAction) {
                        updateAction($newNode);
                    }
                    HERCULE.ajaxEnd();
                }
            },
            initIframe = function() {
                $iframe = $('#pid' + processId);
                if ($iframe.length === 0) {
                    $iframe= $('<iframe id="pid'+processId+'" name="fu'+processId+'" class="virgin formUploader hidden-by-js"><\/iframe>');
                    $iframe
                        .appendTo('body')
                        .on('load', handleResponse);
                }
            },
            cleanForm = function() {
                // get rid of ajax specific stuff to allow normal submission of form
                $('form').removeAttr('target');
                $('#thisWasTheButton').remove();
            },
            eventType = useClick ? 'click' : 'submit';

        $container.on(eventType, selector, function(e) {
            var $form = $(this).closest('form'),
                button,
                ajaxMessage,
                confirmationMessage = $form.data('confirmation-message');

            if (confirmationMessage) {
                if (!HERCULE.confirm(confirmationMessage)) {
                    e.preventDefault();
                    return;
                }
            }

            initIframe();

            if (useClick) {
                // add button name to form
                button = e.target;
                $form.append('<input type="hidden" name="'+button.name+'" id="thisWasTheButton">');
                ajaxMessage = $(button).data('ajax-message') || defaultMessage;
            } else {
                ajaxMessage = $form.data('ajax-message') || defaultMessage;
            }
            handleDispatch(e, $form, ajaxMessage);
        });

        return $container;
    };

    /**
     *  chain collapse of table rows for accordion like effect
     */
    $.fn.collapseTableRows = function() {

        function collapseRow($r, i) {
            // animate each cell separately as rows can't be
            $r.find('td').not(':first-child').slideUp(HERCULE.animationSpeeds.tableAccordion);
            $r.find('td:first-child').slideUp(HERCULE.animationSpeeds.tableAccordion, function() {
                if (i > 0) {
                    collapseRow($rows.eq(i - 1), i - 1);
                } else {
                    $rows.hide();
                }
            });
        }

        var $rows = this,
            rowCount = $rows.length;
        collapseRow($rows.eq(rowCount - 1), rowCount - 1);
    };

    $.fn.expandTableRows = function() {

        function expandRow($r, i) {
            $r.find('td').not(':first-child').slideDown(HERCULE.animationSpeeds.tableAccordion);
            $r.find('td:first-child').slideDown(HERCULE.animationSpeeds.tableAccordion, function() {
                if (i < rowCount - 1) {
                    expandRow($rows.eq(i + 1), i + 1);
                }
            });
        }

        var $rows = this,
            rowCount = $rows.length;
        $rows.show();
        expandRow($rows.eq(0), 0);
    };

}(jQuery));
