var HERCULE = {};
HERCULE.isIE8 = $('html').hasClass('ie8');
HERCULE.printFull = function() {
    $('body').removeClass('printminutes');
    window.print();
};
HERCULE.printMinutes = function() {
    $('body').addClass('printminutes');
    window.print();
    $('body').removeClass('printminutes');
};

/**
 * checks to see whether form fields within the given $scope have all been completed
 * @param $scope
 * @param items
 * @param exclusions
 * @returns {boolean}
 */
HERCULE.allFieldsCompleted = function($scope, items, exclusions) {
    $scope = $scope || $('form');
    items = items || 'select,input,textarea';
    exclusions = exclusions || '';

    var complete = true;
    $scope.find(items).not(exclusions).each(function() {
        if (this.name) {
            // controls inserted by chosen (and other js enhancements)
            // don't/shouldn't have name attributes
            complete = complete && !!$(this).val();
        }
    });
    return complete;
};

/**
 * due to having to support a browser that doesn't have :last-child or :last-of-type
 * the .last class needs to be added to the last (visible) div within a fieldset/page-chunk
 * @param $divs
 */
HERCULE.markLastVisible = function($divs) {
    $divs
        .removeClass('last')
        .filter(':visible')
        .last()
        .addClass('last');
};

/**
 * Remove unhelpful C:fakepath from file path, and just show the actual file name
 * @param filename_with_path
 */
HERCULE.sanitizeInputFileName = function(filename_with_path) {
    return filename_with_path.split(/[\\/]/).pop();
};

HERCULE.blockAjax = false;
/**
 * Display the (loading) message within $elt. Empty message removes the box
 * $elt defaults to $('body')
 * @param message string
 * @param $elt jQuery
 */
HERCULE.ajaxMessage = function(message, $elt) {
    $elt = $elt || $('body');
    $elt.find('.loading').remove();
    if(message) {
        $elt.prepend('<div class="loading">' + message + '<\/div>');
    }
};

HERCULE.ajaxStart = function(message, $elt) {
    if (HERCULE.blockAjax) {
        return false;
    }
    HERCULE.blockAjax = true;
    HERCULE.ajaxMessage(message || "loading...", $elt);
    return true;
};

HERCULE.ajaxEnd = function($elt) {
    HERCULE.ajaxMessage(null, $elt);
    HERCULE.blockAjax = false;
};

HERCULE.animationSpeeds = {
    tableAccordion: 30,
    panelSlide: 400,
    zero: 1 // repaint
};

/**
 * @param message string
 * @returns boolean
 */
HERCULE.confirm = function(message) {
    // TODO: replace with something nicer
    return confirm(message);
};

/**
 * 
 * @param caseTypes Array
 * @param animationSpeed int
 * @param clearFields boolean
 */
HERCULE.advancedSearch = function(caseTypes, animationSpeed, clearFields) {
    var selectedGroups = {},
        homogenousGroup = false, // whether selected types are clustered
        group,
        key,
        groupKeys,
        $panel;

    $('#gs_correspondenceType :selected').each(function () {
        key = $(this).parent().attr('label');
        selectedGroups[key] = true; // use object here to factor out repeats
    });

    groupKeys = Object.keys(selectedGroups);
    if (groupKeys.length == 1) {
        group = groupKeys[0];
        homogenousGroup = group == 'PQ' || ( group == 'UKVI' && (jQuery.inArray("UTEN", caseTypes) == -1) )
            || ( group == 'FOI' && (jQuery.inArray("FOI", caseTypes) == -1) );
    }
    //This can only be set to true and FOI if it is an FOI complaint, so update the group variable accordingly
    if (homogenousGroup && group == 'FOI') {
        group = 'FOI-complaint';
    }

    if (clearFields) {
        HERCULE.clearSearchFields('.advanced-search-fields', true);
    }

    $('#search_details #gs_searchButton').show();

    if (homogenousGroup) {
        $('.advanced-search-fields').not('.' + group + '-search').hide(animationSpeed);
    } else {
        $('.advanced-search-fields').hide(animationSpeed);
    }

    if (homogenousGroup) {
        $panel = $('.advanced-search-fields.' + group + '-search');
        animationSpeed = (caseTypes.length == 1 ? animationSpeed : HERCULE.animationSpeeds.zero); // already open
    } else if (caseTypes && caseTypes.length === 1) {
        $panel = $('.advanced-search-fields.'+caseTypes[0]+'-search');
    }

    if ($panel) {
        $panel
            .show(animationSpeed)
            .find('select')
            .eq(0)
            .trigger('click') // IE8 hasLayout bug. Oh yes.
            .end()
            .chosen();
        $('#search_details #gs_searchButton').hide();
    }
};

/**
 * 
 * @param selector string
 * @param refreshChosen boolean
 */
HERCULE.clearSearchFields = function(selector, refreshChosen) {
    var $form = $(selector);
    $form.find('input[type=text],select').val('');
    $form.find('input[type=checkbox]').prop('checked', false);
    $form.find("label:contains('Any')").each(function() {
        var checkId = '#'+$(this).attr('for');
        $(checkId).prop('checked', true);
        $(checkId).val('ANY_ALLOWED');
    });
    
    if (refreshChosen) {
        $form.find('select').trigger('chosen:updated');
    }
};

HERCULE.pdfSupport = (function() {
    // poise IE8 thinks that acrobat is installed but can't actually use it
    return !HERCULE.isIE8 && getAcrobatInfo().acrobat === "installed";
})();

HERCULE.showPreview = function(path, fileName) {
    var imageFileExtensions = Array('jpg', 'png', 'gif');
    var fileExtension = fileName ? fileName.split('.').pop().toLowerCase() : '',
        iviewerOptions = {'zoom': '100%', 'src': ''};
    $('#doc_preview').remove();
    $('<div id="doc_preview" class="medium"></div>').appendTo($('#doc_preview_container'));
    if ($.inArray(fileExtension, imageFileExtensions) > -1) {
        $("#doc_preview").addClass('img-preview');
        iviewerOptions.src = path;
        $("#doc_preview").iviewer(iviewerOptions);
        return;
    }
    if (HERCULE.pdfSupport) {
        $('<object id="doc_preview_box" data="'+path+'" type="application/pdf" width="100%" height="500em">Document preview</object>').appendTo($("#doc_preview"));
        return;
    }
    if (path.indexOf('downloadRendition') > -1) {
        imagePath = path.replace('downloadRendition', 'downloadImg');
        $("#doc_preview").addClass('img-preview');
        iviewerOptions.src = imagePath;
        $("#doc_preview").iviewer(iviewerOptions);
        return;
    }
};
