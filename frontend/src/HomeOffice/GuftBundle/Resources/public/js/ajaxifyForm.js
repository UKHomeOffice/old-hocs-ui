var blockAjax = false;

ajaxMessage = function(message, $elt) {
    $elt = $elt || $('body');
    $elt.find('.loading').remove();
    if(message) {
        $elt.prepend('<div class="loading">' + message + '<\/div>');
    }
};

ajaxStart = function(message, $elt) {
    var overlay = ('<div id="ajax-overlay"><img id="ajax-image" src="/bundles/guft/images/ajax-loader.gif" /></div>');
    $(overlay).appendTo('body');
    if (blockAjax) {
        return false;
    }
    blockAjax = true;
    // ajaxMessage(message || "loading...", $elt);
    return true;
};

ajaxEnd = function($elt) {
    $('#ajax-overlay').remove();
    // ajaxMessage(null, $elt);
    blockAjax = false;
};

/**
 * @param message string
 * @returns boolean
 */
confirm = function(message) {
    // TODO: replace with something nicer
    return confirm(message);
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
            if (blockAjax) {
                e.preventDefault();
                cleanForm();
                return;
            }
            ajaxStart(message);
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
                    ajaxMessage($iframe.contents().find('title').text());
                }
            } else {
                if (updateAction) {
                    updateAction($newNode);
                }
                ajaxEnd();
            }
        },
        initIframe = function() {
            $iframe = $('#pid' + processId);
            if ($iframe.length === 0) {
                $iframe= $(
                    '<iframe id="pid'+processId+'" name="fu'+processId+'" class="virgin formUploader hidden-by-js" style="display:none"><\/iframe>'
                );
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
            if (! confirm(confirmationMessage)) {
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
