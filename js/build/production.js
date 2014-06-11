/*global jQuery */
/*jshint browser:true */
/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */

(function( $ ){

    "use strict";

    $.fn.fitVids = function( options ) {
        var settings = {
            customSelector: null,
            ignore: null,
        };

        if(!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement('div');
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }

        if ( options ) {
            $.extend( settings, options );
        }

        return this.each(function(){
            var selectors = [
                "iframe[src*='player.vimeo.com']",
                "iframe[src*='youtube.com']",
                "iframe[src*='youtube-nocookie.com']",
                "iframe[src*='kickstarter.com'][src*='video.html']",
                "object",
                "embed"
            ];

            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }

            var ignoreList = '.fitvidsignore';

            if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }

            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not("object object"); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

            $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                    return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                    $this.attr('height', 9);
                    $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('id')){
                    var videoID = 'fitvid' + Math.floor(Math.random()*999999);
                    $this.attr('id', videoID);
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

    $(".entry-content").fitVids();
    $(".excerpt-content").fitVids();

    // use the tappy library here to remove 300ms click delay
    $('#toggle-navigation').bind('tap', onTap)

    function onTap() {
        // do work
        var menuWidth = $('#menu-primary').width();
        var newMenuHeight = $('#overflow-container').height();

        if ($('#site-header').hasClass('toggled')) {
            $('#site-header').removeClass('toggled')
            $('#main').css('transform', 'translateX(' + 0 + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + 0 + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + 0 + 'px)');
            $(window).unbind('scroll');
            // delayed so it isn't seen
            setTimeout(function() {
                $('#menu-primary').removeAttr('style');
            }, 400);
        } else {
            $('#site-header').addClass('toggled')
            $('#menu-primary').css('height', newMenuHeight);
            $('#main').css('transform', 'translateX(' + menuWidth + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + menuWidth + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + menuWidth + 'px)');
            $(window).scroll(onScroll);
        }
    }
    function onScroll() {
        var menuItemsBottom = $('#menu-primary-items').offset().top + $('#menu-primary-items').height();

        // keep updating var on scroll
        var topDistance = $(window).scrollTop();
        if (topDistance > menuItemsBottom) {
            $(window).unbind('scroll');
            onTap();
        }
    }

    /* allow keyboard access/visibility for dropdown menu items */
    $('.menu-item a, .page_item a').focus(function(){
        $(this).parent('li').addClass('focused');
        $(this).parents('ul').addClass('focused');
    });
    $('.menu-item a, .page_item a').focusout(function(){
        $(this).parent('li').removeClass('focused');
        $(this).parents('ul').removeClass('focused');
    });

    /* logo size tools */
    function displayLogoSizeInputs(){

        var logoSelector = $('#logo');
        var maxWidth = 156;
        var maxHeight = 59;

        // set max-width & max-height to initial values
        $(logoSelector).css({
            'max-width': maxWidth,
            'max-height': maxHeight
        });

        // if initially constrained by width, hide height input
        if($(logoSelector).width() == maxWidth){
            $('html', window.parent.document).find('#customize-control-logo_size_height_setting').hide();
            $('html', window.parent.document).find('#customize-control-logo_size_width_setting').show();

            // remove max-width applied to check constraint
            $(logoSelector).removeAttr('style');

            // reset max-height adjustment so it doesn't interfere. This will be save when the user saves.
            $('html', window.parent.document).find('#customize-control-logo_size_height_setting input').val(0);

            // return b/c height could also be true after adjustment
            return;
        }

        // if initially constrained by height, hide width input
        if($(logoSelector).height() == maxHeight){
            $('html', window.parent.document).find('#customize-control-logo_size_width_setting').hide();
            $('html', window.parent.document).find('#customize-control-logo_size_height_setting').show();

            // remove max-width applied to check constraint
            $(logoSelector).removeAttr('style');

            // reset max-width adjustment so it doesn't interfere. This will be save when the user saves.
            $('html', window.parent.document).find('#customize-control-logo_size_width_setting input').val(0);
        }
    }
    displayLogoSizeInputs();
});


/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }
        element.focus();
    }

}, false);
/* 
 * The MIT License
 *
 * Copyright (c) 2012 James Allardice
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

// Defines the global Placeholders object along with various utility methods
(function (global) {

    "use strict";

    // Cross-browser DOM event binding
    function addEventListener(elem, event, fn) {
        if (elem.addEventListener) {
            return elem.addEventListener(event, fn, false);
        }
        if (elem.attachEvent) {
            return elem.attachEvent("on" + event, fn);
        }
    }

    // Check whether an item is in an array (we don't use Array.prototype.indexOf so we don't clobber any existing polyfills - this is a really simple alternative)
    function inArray(arr, item) {
        var i, len;
        for (i = 0, len = arr.length; i < len; i++) {
            if (arr[i] === item) {
                return true;
            }
        }
        return false;
    }

    // Move the caret to the index position specified. Assumes that the element has focus
    function moveCaret(elem, index) {
        var range;
        if (elem.createTextRange) {
            range = elem.createTextRange();
            range.move("character", index);
            range.select();
        } else if (elem.selectionStart) {
            elem.focus();
            elem.setSelectionRange(index, index);
        }
    }

    // Attempt to change the type property of an input element
    function changeType(elem, type) {
        try {
            elem.type = type;
            return true;
        } catch (e) {
            // You can't change input type in IE8 and below
            return false;
        }
    }

    // Expose public methods
    global.Placeholders = {
        Utils: {
            addEventListener: addEventListener,
            inArray: inArray,
            moveCaret: moveCaret,
            changeType: changeType
        }
    };

}(this));

(function (global) {

    "use strict";

    var validTypes = [
            "text",
            "search",
            "url",
            "tel",
            "email",
            "password",
            "number",
            "textarea"
        ],

    // The list of keycodes that are not allowed when the polyfill is configured to hide-on-input
        badKeys = [

            // The following keys all cause the caret to jump to the end of the input value
            27, // Escape
            33, // Page up
            34, // Page down
            35, // End
            36, // Home

            // Arrow keys allow you to move the caret manually, which should be prevented when the placeholder is visible
            37, // Left
            38, // Up
            39, // Right
            40, // Down

            // The following keys allow you to modify the placeholder text by removing characters, which should be prevented when the placeholder is visible
            8, // Backspace
            46 // Delete
        ],

    // Styling variables
        placeholderStyleColor = "#ccc",
        placeholderClassName = "placeholdersjs",
        classNameRegExp = new RegExp("(?:^|\\s)" + placeholderClassName + "(?!\\S)"),

    // These will hold references to all elements that can be affected. NodeList objects are live, so we only need to get those references once
        inputs, textareas,

    // The various data-* attributes used by the polyfill
        ATTR_CURRENT_VAL = "data-placeholder-value",
        ATTR_ACTIVE = "data-placeholder-active",
        ATTR_INPUT_TYPE = "data-placeholder-type",
        ATTR_FORM_HANDLED = "data-placeholder-submit",
        ATTR_EVENTS_BOUND = "data-placeholder-bound",
        ATTR_OPTION_FOCUS = "data-placeholder-focus",
        ATTR_OPTION_LIVE = "data-placeholder-live",
        ATTR_MAXLENGTH = "data-placeholder-maxlength",

    // Various other variables used throughout the rest of the script
        test = document.createElement("input"),
        head = document.getElementsByTagName("head")[0],
        root = document.documentElement,
        Placeholders = global.Placeholders,
        Utils = Placeholders.Utils,
        hideOnInput, liveUpdates, keydownVal, styleElem, styleRules, placeholder, timer, form, elem, len, i;

    // No-op (used in place of public methods when native support is detected)
    function noop() {}

    // Avoid IE9 activeElement of death when an iframe is used.
    // More info:
    // http://bugs.jquery.com/ticket/13393
    // https://github.com/jquery/jquery/commit/85fc5878b3c6af73f42d61eedf73013e7faae408
    function safeActiveElement() {
        try {
            return document.activeElement;
        } catch (err) {}
    }

    // Hide the placeholder value on a single element. Returns true if the placeholder was hidden and false if it was not (because it wasn't visible in the first place)
    function hidePlaceholder(elem, keydownValue) {
        var type,
            maxLength,
            valueChanged = (!!keydownValue && elem.value !== keydownValue),
            isPlaceholderValue = (elem.value === elem.getAttribute(ATTR_CURRENT_VAL));

        if ((valueChanged || isPlaceholderValue) && elem.getAttribute(ATTR_ACTIVE) === "true") {
            elem.removeAttribute(ATTR_ACTIVE);
            elem.value = elem.value.replace(elem.getAttribute(ATTR_CURRENT_VAL), "");
            elem.className = elem.className.replace(classNameRegExp, "");

            // Restore the maxlength value
            maxLength = elem.getAttribute(ATTR_MAXLENGTH);
            if (parseInt(maxLength, 10) >= 0) { // Old FF returns -1 if attribute not set (see GH-56)
                elem.setAttribute("maxLength", maxLength);
                elem.removeAttribute(ATTR_MAXLENGTH);
            }

            // If the polyfill has changed the type of the element we need to change it back
            type = elem.getAttribute(ATTR_INPUT_TYPE);
            if (type) {
                elem.type = type;
            }
            return true;
        }
        return false;
    }

    // Show the placeholder value on a single element. Returns true if the placeholder was shown and false if it was not (because it was already visible)
    function showPlaceholder(elem) {
        var type,
            maxLength,
            val = elem.getAttribute(ATTR_CURRENT_VAL);
        if (elem.value === "" && val) {
            elem.setAttribute(ATTR_ACTIVE, "true");
            elem.value = val;
            elem.className += " " + placeholderClassName;

            // Store and remove the maxlength value
            maxLength = elem.getAttribute(ATTR_MAXLENGTH);
            if (!maxLength) {
                elem.setAttribute(ATTR_MAXLENGTH, elem.maxLength);
                elem.removeAttribute("maxLength");
            }

            // If the type of element needs to change, change it (e.g. password inputs)
            type = elem.getAttribute(ATTR_INPUT_TYPE);
            if (type) {
                elem.type = "text";
            } else if (elem.type === "password") {
                if (Utils.changeType(elem, "text")) {
                    elem.setAttribute(ATTR_INPUT_TYPE, "password");
                }
            }
            return true;
        }
        return false;
    }

    function handleElem(node, callback) {

        var handleInputsLength, handleTextareasLength, handleInputs, handleTextareas, elem, len, i;

        // Check if the passed in node is an input/textarea (in which case it can't have any affected descendants)
        if (node && node.getAttribute(ATTR_CURRENT_VAL)) {
            callback(node);
        } else {

            // If an element was passed in, get all affected descendants. Otherwise, get all affected elements in document
            handleInputs = node ? node.getElementsByTagName("input") : inputs;
            handleTextareas = node ? node.getElementsByTagName("textarea") : textareas;

            handleInputsLength = handleInputs ? handleInputs.length : 0;
            handleTextareasLength = handleTextareas ? handleTextareas.length : 0;

            // Run the callback for each element
            for (i = 0, len = handleInputsLength + handleTextareasLength; i < len; i++) {
                elem = i < handleInputsLength ? handleInputs[i] : handleTextareas[i - handleInputsLength];
                callback(elem);
            }
        }
    }

    // Return all affected elements to their normal state (remove placeholder value if present)
    function disablePlaceholders(node) {
        handleElem(node, hidePlaceholder);
    }

    // Show the placeholder value on all appropriate elements
    function enablePlaceholders(node) {
        handleElem(node, showPlaceholder);
    }

    // Returns a function that is used as a focus event handler
    function makeFocusHandler(elem) {
        return function () {

            // Only hide the placeholder value if the (default) hide-on-focus behaviour is enabled
            if (hideOnInput && elem.value === elem.getAttribute(ATTR_CURRENT_VAL) && elem.getAttribute(ATTR_ACTIVE) === "true") {

                // Move the caret to the start of the input (this mimics the behaviour of all browsers that do not hide the placeholder on focus)
                Utils.moveCaret(elem, 0);

            } else {

                // Remove the placeholder
                hidePlaceholder(elem);
            }
        };
    }

    // Returns a function that is used as a blur event handler
    function makeBlurHandler(elem) {
        return function () {
            showPlaceholder(elem);
        };
    }

    // Functions that are used as a event handlers when the hide-on-input behaviour has been activated - very basic implementation of the "input" event
    function makeKeydownHandler(elem) {
        return function (e) {
            keydownVal = elem.value;

            //Prevent the use of the arrow keys (try to keep the cursor before the placeholder)
            if (elem.getAttribute(ATTR_ACTIVE) === "true") {
                if (keydownVal === elem.getAttribute(ATTR_CURRENT_VAL) && Utils.inArray(badKeys, e.keyCode)) {
                    if (e.preventDefault) {
                        e.preventDefault();
                    }
                    return false;
                }
            }
        };
    }
    function makeKeyupHandler(elem) {
        return function () {
            hidePlaceholder(elem, keydownVal);

            // If the element is now empty we need to show the placeholder
            if (elem.value === "") {
                elem.blur();
                Utils.moveCaret(elem, 0);
            }
        };
    }
    function makeClickHandler(elem) {
        return function () {
            if (elem === safeActiveElement() && elem.value === elem.getAttribute(ATTR_CURRENT_VAL) && elem.getAttribute(ATTR_ACTIVE) === "true") {
                Utils.moveCaret(elem, 0);
            }
        };
    }

    // Returns a function that is used as a submit event handler on form elements that have children affected by this polyfill
    function makeSubmitHandler(form) {
        return function () {

            // Turn off placeholders on all appropriate descendant elements
            disablePlaceholders(form);
        };
    }

    // Bind event handlers to an element that we need to affect with the polyfill
    function newElement(elem) {

        // If the element is part of a form, make sure the placeholder string is not submitted as a value
        if (elem.form) {
            form = elem.form;

            // If the type of the property is a string then we have a "form" attribute and need to get the real form
            if (typeof form === "string") {
                form = document.getElementById(form);
            }

            // Set a flag on the form so we know it's been handled (forms can contain multiple inputs)
            if (!form.getAttribute(ATTR_FORM_HANDLED)) {
                Utils.addEventListener(form, "submit", makeSubmitHandler(form));
                form.setAttribute(ATTR_FORM_HANDLED, "true");
            }
        }

        // Bind event handlers to the element so we can hide/show the placeholder as appropriate
        Utils.addEventListener(elem, "focus", makeFocusHandler(elem));
        Utils.addEventListener(elem, "blur", makeBlurHandler(elem));

        // If the placeholder should hide on input rather than on focus we need additional event handlers
        if (hideOnInput) {
            Utils.addEventListener(elem, "keydown", makeKeydownHandler(elem));
            Utils.addEventListener(elem, "keyup", makeKeyupHandler(elem));
            Utils.addEventListener(elem, "click", makeClickHandler(elem));
        }

        // Remember that we've bound event handlers to this element
        elem.setAttribute(ATTR_EVENTS_BOUND, "true");
        elem.setAttribute(ATTR_CURRENT_VAL, placeholder);

        // If the element doesn't have a value and is not focussed, set it to the placeholder string
        if (hideOnInput || elem !== safeActiveElement()) {
            showPlaceholder(elem);
        }
    }

    Placeholders.nativeSupport = test.placeholder !== void 0;

    if (!Placeholders.nativeSupport) {

        // Get references to all the input and textarea elements currently in the DOM (live NodeList objects to we only need to do this once)
        inputs = document.getElementsByTagName("input");
        textareas = document.getElementsByTagName("textarea");

        // Get any settings declared as data-* attributes on the root element (currently the only options are whether to hide the placeholder on focus or input and whether to auto-update)
        hideOnInput = root.getAttribute(ATTR_OPTION_FOCUS) === "false";
        liveUpdates = root.getAttribute(ATTR_OPTION_LIVE) !== "false";

        // Create style element for placeholder styles (instead of directly setting style properties on elements - allows for better flexibility alongside user-defined styles)
        styleElem = document.createElement("style");
        styleElem.type = "text/css";

        // Create style rules as text node
        styleRules = document.createTextNode("." + placeholderClassName + " { color:" + placeholderStyleColor + "; }");

        // Append style rules to newly created stylesheet
        if (styleElem.styleSheet) {
            styleElem.styleSheet.cssText = styleRules.nodeValue;
        } else {
            styleElem.appendChild(styleRules);
        }

        // Prepend new style element to the head (before any existing stylesheets, so user-defined rules take precedence)
        head.insertBefore(styleElem, head.firstChild);

        // Set up the placeholders
        for (i = 0, len = inputs.length + textareas.length; i < len; i++) {
            elem = i < inputs.length ? inputs[i] : textareas[i - inputs.length];

            // Get the value of the placeholder attribute, if any. IE10 emulating IE7 fails with getAttribute, hence the use of the attributes node
            placeholder = elem.attributes.placeholder;
            if (placeholder) {

                // IE returns an empty object instead of undefined if the attribute is not present
                placeholder = placeholder.nodeValue;

                // Only apply the polyfill if this element is of a type that supports placeholders, and has a placeholder attribute with a non-empty value
                if (placeholder && Utils.inArray(validTypes, elem.type)) {
                    newElement(elem);
                }
            }
        }

        // If enabled, the polyfill will repeatedly check for changed/added elements and apply to those as well
        timer = setInterval(function () {
            for (i = 0, len = inputs.length + textareas.length; i < len; i++) {
                elem = i < inputs.length ? inputs[i] : textareas[i - inputs.length];

                // Only apply the polyfill if this element is of a type that supports placeholders, and has a placeholder attribute with a non-empty value
                placeholder = elem.attributes.placeholder;
                if (placeholder) {
                    placeholder = placeholder.nodeValue;
                    if (placeholder && Utils.inArray(validTypes, elem.type)) {

                        // If the element hasn't had event handlers bound to it then add them
                        if (!elem.getAttribute(ATTR_EVENTS_BOUND)) {
                            newElement(elem);
                        }

                        // If the placeholder value has changed or not been initialised yet we need to update the display
                        if (placeholder !== elem.getAttribute(ATTR_CURRENT_VAL) || (elem.type === "password" && !elem.getAttribute(ATTR_INPUT_TYPE))) {

                            // Attempt to change the type of password inputs (fails in IE < 9)
                            if (elem.type === "password" && !elem.getAttribute(ATTR_INPUT_TYPE) && Utils.changeType(elem, "text")) {
                                elem.setAttribute(ATTR_INPUT_TYPE, "password");
                            }

                            // If the placeholder value has changed and the placeholder is currently on display we need to change it
                            if (elem.value === elem.getAttribute(ATTR_CURRENT_VAL)) {
                                elem.value = placeholder;
                            }

                            // Keep a reference to the current placeholder value in case it changes via another script
                            elem.setAttribute(ATTR_CURRENT_VAL, placeholder);
                        }
                    }
                } else if (elem.getAttribute(ATTR_ACTIVE)) {
                    hidePlaceholder(elem);
                    elem.removeAttribute(ATTR_CURRENT_VAL);
                }
            }

            // If live updates are not enabled cancel the timer
            if (!liveUpdates) {
                clearInterval(timer);
            }
        }, 100);
    }

    Utils.addEventListener(global, "beforeunload", function () {
        Placeholders.disable();
    });

    // Expose public methods
    Placeholders.disable = Placeholders.nativeSupport ? noop : disablePlaceholders;
    Placeholders.enable = Placeholders.nativeSupport ? noop : enablePlaceholders;

}(this));
/* Respond.js: min/max-width media query polyfill. (c) Scott Jehl. MIT Lic. j.mp/respondjs  */
(function( w ){

    "use strict";

    //exposed namespace
    var respond = {};
    w.respond = respond;

    //define update even in native-mq-supporting browsers, to avoid errors
    respond.update = function(){};

    //define ajax obj
    var requestQueue = [],
        xmlHttp = (function() {
            var xmlhttpmethod = false;
            try {
                xmlhttpmethod = new w.XMLHttpRequest();
            }
            catch( e ){
                xmlhttpmethod = new w.ActiveXObject( "Microsoft.XMLHTTP" );
            }
            return function(){
                return xmlhttpmethod;
            };
        })(),

    //tweaked Ajax functions from Quirksmode
        ajax = function( url, callback ) {
            var req = xmlHttp();
            if (!req){
                return;
            }
            req.open( "GET", url, true );
            req.onreadystatechange = function () {
                if ( req.readyState !== 4 || req.status !== 200 && req.status !== 304 ){
                    return;
                }
                callback( req.responseText );
            };
            if ( req.readyState === 4 ){
                return;
            }
            req.send( null );
        },
        isUnsupportedMediaQuery = function( query ) {
            return query.replace( respond.regex.minmaxwh, '' ).match( respond.regex.other );
        };

    //expose for testing
    respond.ajax = ajax;
    respond.queue = requestQueue;
    respond.unsupportedmq = isUnsupportedMediaQuery;
    respond.regex = {
        media: /@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi,
        keyframes: /@(?:\-(?:o|moz|webkit)\-)?keyframes[^\{]+\{(?:[^\{\}]*\{[^\}\{]*\})+[^\}]*\}/gi,
        comments: /\/\*[^*]*\*+([^/][^*]*\*+)*\//gi,
        urls: /(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,
        findStyles: /@media *([^\{]+)\{([\S\s]+?)$/,
        only: /(only\s+)?([a-zA-Z]+)\s?/,
        minw: /\(\s*min\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/,
        maxw: /\(\s*max\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/,
        minmaxwh: /\(\s*m(in|ax)\-(height|width)\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/gi,
        other: /\([^\)]*\)/g
    };

    //expose media query support flag for external use
    respond.mediaQueriesSupported = w.matchMedia && w.matchMedia( "only all" ) !== null && w.matchMedia( "only all" ).matches;

    //if media queries are supported, exit here
    if( respond.mediaQueriesSupported ){
        return;
    }

    //define vars
    var doc = w.document,
        docElem = doc.documentElement,
        mediastyles = [],
        rules = [],
        appendedEls = [],
        parsedSheets = {},
        resizeThrottle = 30,
        head = doc.getElementsByTagName( "head" )[0] || docElem,
        base = doc.getElementsByTagName( "base" )[0],
        links = head.getElementsByTagName( "link" ),

        lastCall,
        resizeDefer,

    //cached container for 1em value, populated the first time it's needed
        eminpx,

    // returns the value of 1em in pixels
        getEmValue = function() {
            var ret,
                div = doc.createElement('div'),
                body = doc.body,
                originalHTMLFontSize = docElem.style.fontSize,
                originalBodyFontSize = body && body.style.fontSize,
                fakeUsed = false;

            div.style.cssText = "position:absolute;font-size:1em;width:1em";

            if( !body ){
                body = fakeUsed = doc.createElement( "body" );
                body.style.background = "none";
            }

            // 1em in a media query is the value of the default font size of the browser
            // reset docElem and body to ensure the correct value is returned
            docElem.style.fontSize = "100%";
            body.style.fontSize = "100%";

            body.appendChild( div );

            if( fakeUsed ){
                docElem.insertBefore( body, docElem.firstChild );
            }

            ret = div.offsetWidth;

            if( fakeUsed ){
                docElem.removeChild( body );
            }
            else {
                body.removeChild( div );
            }

            // restore the original values
            docElem.style.fontSize = originalHTMLFontSize;
            if( originalBodyFontSize ) {
                body.style.fontSize = originalBodyFontSize;
            }


            //also update eminpx before returning
            ret = eminpx = parseFloat(ret);

            return ret;
        },

    //enable/disable styles
        applyMedia = function( fromResize ){
            var name = "clientWidth",
                docElemProp = docElem[ name ],
                currWidth = doc.compatMode === "CSS1Compat" && docElemProp || doc.body[ name ] || docElemProp,
                styleBlocks	= {},
                lastLink = links[ links.length-1 ],
                now = (new Date()).getTime();

            //throttle resize calls
            if( fromResize && lastCall && now - lastCall < resizeThrottle ){
                w.clearTimeout( resizeDefer );
                resizeDefer = w.setTimeout( applyMedia, resizeThrottle );
                return;
            }
            else {
                lastCall = now;
            }

            for( var i in mediastyles ){
                if( mediastyles.hasOwnProperty( i ) ){
                    var thisstyle = mediastyles[ i ],
                        min = thisstyle.minw,
                        max = thisstyle.maxw,
                        minnull = min === null,
                        maxnull = max === null,
                        em = "em";

                    if( !!min ){
                        min = parseFloat( min ) * ( min.indexOf( em ) > -1 ? ( eminpx || getEmValue() ) : 1 );
                    }
                    if( !!max ){
                        max = parseFloat( max ) * ( max.indexOf( em ) > -1 ? ( eminpx || getEmValue() ) : 1 );
                    }

                    // if there's no media query at all (the () part), or min or max is not null, and if either is present, they're true
                    if( !thisstyle.hasquery || ( !minnull || !maxnull ) && ( minnull || currWidth >= min ) && ( maxnull || currWidth <= max ) ){
                        if( !styleBlocks[ thisstyle.media ] ){
                            styleBlocks[ thisstyle.media ] = [];
                        }
                        styleBlocks[ thisstyle.media ].push( rules[ thisstyle.rules ] );
                    }
                }
            }

            //remove any existing respond style element(s)
            for( var j in appendedEls ){
                if( appendedEls.hasOwnProperty( j ) ){
                    if( appendedEls[ j ] && appendedEls[ j ].parentNode === head ){
                        head.removeChild( appendedEls[ j ] );
                    }
                }
            }
            appendedEls.length = 0;

            //inject active styles, grouped by media type
            for( var k in styleBlocks ){
                if( styleBlocks.hasOwnProperty( k ) ){
                    var ss = doc.createElement( "style" ),
                        css = styleBlocks[ k ].join( "\n" );

                    ss.type = "text/css";
                    ss.media = k;

                    //originally, ss was appended to a documentFragment and sheets were appended in bulk.
                    //this caused crashes in IE in a number of circumstances, such as when the HTML element had a bg image set, so appending beforehand seems best. Thanks to @dvelyk for the initial research on this one!
                    head.insertBefore( ss, lastLink.nextSibling );

                    if ( ss.styleSheet ){
                        ss.styleSheet.cssText = css;
                    }
                    else {
                        ss.appendChild( doc.createTextNode( css ) );
                    }

                    //push to appendedEls to track for later removal
                    appendedEls.push( ss );
                }
            }
        },
    //find media blocks in css text, convert to style blocks
        translate = function( styles, href, media ){
            var qs = styles.replace( respond.regex.comments, '' )
                    .replace( respond.regex.keyframes, '' )
                    .match( respond.regex.media ),
                ql = qs && qs.length || 0;

            //try to get CSS path
            href = href.substring( 0, href.lastIndexOf( "/" ) );

            var repUrls = function( css ){
                    return css.replace( respond.regex.urls, "$1" + href + "$2$3" );
                },
                useMedia = !ql && media;

            //if path exists, tack on trailing slash
            if( href.length ){ href += "/"; }

            //if no internal queries exist, but media attr does, use that
            //note: this currently lacks support for situations where a media attr is specified on a link AND
            //its associated stylesheet has internal CSS media queries.
            //In those cases, the media attribute will currently be ignored.
            if( useMedia ){
                ql = 1;
            }

            for( var i = 0; i < ql; i++ ){
                var fullq, thisq, eachq, eql;

                //media attr
                if( useMedia ){
                    fullq = media;
                    rules.push( repUrls( styles ) );
                }
                //parse for styles
                else{
                    fullq = qs[ i ].match( respond.regex.findStyles ) && RegExp.$1;
                    rules.push( RegExp.$2 && repUrls( RegExp.$2 ) );
                }

                eachq = fullq.split( "," );
                eql = eachq.length;

                for( var j = 0; j < eql; j++ ){
                    thisq = eachq[ j ];

                    if( isUnsupportedMediaQuery( thisq ) ) {
                        continue;
                    }

                    mediastyles.push( {
                        media : thisq.split( "(" )[ 0 ].match( respond.regex.only ) && RegExp.$2 || "all",
                        rules : rules.length - 1,
                        hasquery : thisq.indexOf("(") > -1,
                        minw : thisq.match( respond.regex.minw ) && parseFloat( RegExp.$1 ) + ( RegExp.$2 || "" ),
                        maxw : thisq.match( respond.regex.maxw ) && parseFloat( RegExp.$1 ) + ( RegExp.$2 || "" )
                    } );
                }
            }

            applyMedia();
        },

    //recurse through request queue, get css text
        makeRequests = function(){
            if( requestQueue.length ){
                var thisRequest = requestQueue.shift();

                ajax( thisRequest.href, function( styles ){
                    translate( styles, thisRequest.href, thisRequest.media );
                    parsedSheets[ thisRequest.href ] = true;

                    // by wrapping recursive function call in setTimeout
                    // we prevent "Stack overflow" error in IE7
                    w.setTimeout(function(){ makeRequests(); },0);
                } );
            }
        },

    //loop stylesheets, send text content to translate
        ripCSS = function(){

            for( var i = 0; i < links.length; i++ ){
                var sheet = links[ i ],
                    href = sheet.href,
                    media = sheet.media,
                    isCSS = sheet.rel && sheet.rel.toLowerCase() === "stylesheet";

                //only links plz and prevent re-parsing
                if( !!href && isCSS && !parsedSheets[ href ] ){
                    // selectivizr exposes css through the rawCssText expando
                    if (sheet.styleSheet && sheet.styleSheet.rawCssText) {
                        translate( sheet.styleSheet.rawCssText, href, media );
                        parsedSheets[ href ] = true;
                    } else {
                        if( (!/^([a-zA-Z:]*\/\/)/.test( href ) && !base) ||
                            href.replace( RegExp.$1, "" ).split( "/" )[0] === w.location.host ){
                            // IE7 doesn't handle urls that start with '//' for ajax request
                            // manually add in the protocol
                            if ( href.substring(0,2) === "//" ) { href = w.location.protocol + href; }
                            requestQueue.push( {
                                href: href,
                                media: media
                            } );
                        }
                    }
                }
            }
            makeRequests();
        };

    //translate CSS
    ripCSS();

    //expose update for re-running respond later on
    respond.update = ripCSS;

    //expose getEmValue
    respond.getEmValue = getEmValue;

    //adjust on resize
    function callMedia(){
        applyMedia( true );
    }

    if( w.addEventListener ){
        w.addEventListener( "resize", callMedia, false );
    }
    else if( w.attachEvent ){
        w.attachEvent( "onresize", callMedia );
    }
})(this);
/*! Tappy! - a lightweight normalized tap event. Copyright 2013 @scottjehl, Filament Group, Inc. Licensed MIT */
(function( w, $, undefined ){

    // handling flag is true when an event sequence is in progress (thx androood)
    w.tapHandling = false;

    var tap = function( $els ){
        return $els.each(function(){

            var $el = $( this ),
                resetTimer,
                startY,
                startX,
                cancel,
                scrollTolerance = 10;

            function trigger( e ){
                $( e.target ).trigger( "tap", [ e, $( e.target ).attr( "href" ) ] );
                e.stopImmediatePropagation();
            }

            function getCoords( e ){
                var ev = e.originalEvent || e,
                    touches = ev.touches || ev.targetTouches;

                if( touches ){
                    return [ touches[ 0 ].pageX, touches[ 0 ].pageY ];
                }
                else {
                    return null;
                }
            }

            function start( e ){
                if( e.touches && e.touches.length > 1 || e.targetTouches && e.targetTouches.length > 1 ){
                    return false;
                }

                var coords = getCoords( e );
                startX = coords[ 0 ];
                startY = coords[ 1 ];
            }

            // any touchscroll that results in > tolerance should cancel the tap
            function move( e ){
                if( !cancel ){
                    var coords = getCoords( e );
                    if( coords && ( Math.abs( startY - coords[ 1 ] ) > scrollTolerance || Math.abs( startX - coords[ 0 ] ) > scrollTolerance ) ){
                        cancel = true;
                    }
                }
            }

            function end( e ){
                clearTimeout( resetTimer );
                resetTimer = setTimeout( function(){
                    w.tapHandling = false;
                    cancel = false;
                }, 1000 );

                if( e.ctrlKey || e.metaKey ){
                    return;
                }

                e.preventDefault();

                // this part prevents a double callback from touch and mouse on the same tap

                // if a scroll happened between touchstart and touchend
                if( cancel || w.tapHandling && w.tapHandling !== e.type ){
                    cancel = false;
                    return;
                }

                w.tapHandling = e.type;
                trigger( e );
            }

            $el
                .bind( "touchstart MSPointerDown", start )
                .bind( "touchmove MSPointerMove", move )
                .bind( "touchend MSPointerUp", end )
                .bind( "click", end );
        });
    };

    // use special events api
    if( $.event && $.event.special ){
        $.event.special.tap = {
            add: function( handleObj ) {
                tap( $( this ), true );
            },
            remove: function( handleObj ) {
                tap( $( this ), false );
            }
        };
    }
    else{
        // monkeybind
        var oldBind = $.fn.bind;
        $.fn.bind = function( evt ){
            if( /(^| )tap( |$)/.test( evt ) ){
                tap( this );
            }
            return oldBind.apply( this, arguments );
        };
    }

}( this, jQuery ));