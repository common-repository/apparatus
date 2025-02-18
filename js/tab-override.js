/*global window */
/*jslint
    white: true, browser: true, onevar: true, undef: true, nomen: true,
    eqeqeq: true, plusplus: true, bitwise: true, regexp: true, newcap: true,
    immed: true
*/

/**
 * @fileOverview The JavaScript component of the Tab Override MediaWiki extension
 * @author Bill Bryant
 * @version 2.0.1-MediaWiki
 */

/*
Modified to work without jQuery in Apparatus
*/

// the only global variable created by this script
var appTOAddListener;

/**
 * Registers an event listener in a cross-browser compatible way.
 * For simplicity, do not fall back on the DOM Level 0 method of event
 * registration. Using the DOM Level 2 method allows multiple event
 * listener functions to be registered at once, allowing this plugin to
 * coexist with other plugins that may also register event listeners on
 * the same object. The lack of support for the DOM Level 0 method
 * means that this plugin will not work in some very old browsers.
 * Note that, for simplicity, this function also does not address IE
 * memory leaks.
 */
if (document.addEventListener) { // W3C standard method
    appTOAddListener = function (obj, type, fn) {
        obj.addEventListener(type, fn, false);
    };
} else if (document.attachEvent) { // IE proprietary method
    appTOAddListener = function (obj, type, fn) {
        obj.attachEvent('on' + type, function () {
            // fix the "this" keyword and the event argument
            // propagate the return value of the fn function
            return fn.call(obj, window.event);
        });
    };
} else { // advanced event registration model not available
    appTOAddListener = function () {};
}

// register the keydown event listener on the content textarea element
// after the window is loaded to make sure that the element is accessible
// using the document's getElementById method
appTOAddListener(window, 'load', function () {
    var content = document.getElementById('app_code'); // the mediawiki page edit textarea
    
    // if there is no content textarea element on this page, do nothing
    if (!content) {
        return;
    }
    
    appTOAddListener(content, 'keydown', function (e) {
        var text, // initial text in the textarea
            range, // the IE TextRange object
            tempRange, // used to calculate selection start and end positions in IE
            preNewlines, // the number of newline (\r\n) characters before the selection start (for IE)
            selNewlines, // the number of newline (\r\n) characters within the selection (for IE)
            initScrollTop, // initial scrollTop value to fix scrolling in Firefox
            selStart, // the selection start position
            selEnd, // the selection end position
            sel, // the selected text
            startLine, // for multi-line selections, the first character position of the first line
            endLine, // for multi-line selections, the last character position of the last line
            numTabs, // the number of tabs inserted / removed in the selection
            startTab, // if a tab was removed from the start of the first line
            preTab; // if a tab was removed before the start of the selection
        
        // tab key - insert / remove tab
        if (e.keyCode === 9) {
            
            // initialize variables
            text = this.value;
            initScrollTop = this.scrollTop; // scrollTop is supported by all modern browsers
            numTabs = 0;
            startTab = 0;
            preTab = 0;
            
            if (typeof this.selectionStart !== 'undefined') {
                selStart = this.selectionStart;
                selEnd = this.selectionEnd;
                sel = text.slice(selStart, selEnd);
            } else if (document.selection) { // IE
                range = document.selection.createRange();
                sel = range.text;
                tempRange = range.duplicate();
                tempRange.moveToElementText(this);
                tempRange.setEndPoint('EndToEnd', range);
                selEnd = tempRange.text.length;
                selStart = selEnd - sel.length;
                // whenever the value of the textarea is changed, the range needs to be reset
                // IE (and Opera) use both \r and \n for newlines - this adds an extra character
                // that needs to be accounted for when doing position calculations
                // these values are used to offset the selection start and end positions
                preNewlines = text.slice(0, selStart).split('\r\n').length - 1;
                selNewlines = sel.split('\r\n').length - 1;
            } else {
                // cannot access textarea selection - do nothing
                return;
            }
            
            // special case of multi-line selection
            if (selStart !== selEnd && sel.indexOf('\n') !== -1) {
                // for multiple lines, only insert / remove tabs from the beginning of each line
                
                // find the start of the first selected line
                if (selStart === 0 || text.charAt(selStart - 1) === '\n') {
                    // the selection starts at the beginning of a line
                    startLine = selStart;
                } else {
                    // the selection starts after the beginning of a line
                    // set startLine to the beginning of the first partially selected line
                    // subtract 1 from selStart in case the cursor is at the newline character,
                    // for instance, if the very end of the previous line was selected
                    // add 1 to get the next character after the newline
                    // if there is none before the selection, lastIndexOf returns -1
                    // when 1 is added to that it becomes 0 and the first character is used
                    startLine = text.lastIndexOf('\n', selStart - 1) + 1;
                }
                
                // find the end of the last selected line
                if (selEnd === text.length || text.charAt(selEnd) === '\n') {
                    // the selection ends at the end of a line
                    endLine = selEnd;
                } else {
                    // the selection ends before the end of a line
                    // set endLine to the end of the last partially selected line
                    endLine = text.indexOf('\n', selEnd);
                    if (endLine === -1) {
                        endLine = text.length;
                    }
                }
                
                // if the shift key was pressed, remove tabs instead of inserting them
                if (e.shiftKey) {
                    if (text.charAt(startLine) === '\t') {
                        // is this tab part of the selection?
                        if (startLine === selStart) {
                            // it is, remove it
                            sel = sel.slice(1);
                        } else {
                            // the tab comes before the selection
                            preTab = 1;
                        }
                        startTab = 1;
                    }
                    
                    this.value = text.slice(0, startLine) + text.slice(startLine + preTab, selStart) +
                        sel.replace(/\n\t/g, function () {
                            numTabs += 1;
                            return '\n';
                        }) + text.slice(selEnd);
                    
                    // set start and end points
                    if (range) { // IE
                        // setting end first makes calculations easier
                        range.collapse();
                        range.moveEnd('character', selEnd - startTab - numTabs - selNewlines - preNewlines);
                        range.moveStart('character', selStart - preTab - preNewlines);
                        range.select();
                    } else {
                        // set start first for Opera
                        this.selectionStart = selStart - preTab; // preTab is 0 or 1
                        // move the selection end over by the total number of tabs removed
                        this.selectionEnd = selEnd - startTab - numTabs;
                    }
                } else {
                    // no shift key
                    // insert tabs at the beginning of each line of the selection
                    this.value = text.slice(0, startLine) + '\t' + text.slice(startLine, selStart) +
                        sel.replace(/\n/g, function () {
                            numTabs += 1;
                            return '\n\t';
                        }) + text.slice(selEnd);
                    
                    // set start and end points
                    if (range) { // IE
                        range.collapse();
                        range.moveEnd('character', selEnd + 1 - preNewlines); // numTabs cancels out selNewlines
                        range.moveStart('character', selStart + 1 - preNewlines);
                        range.select();
                    } else {
                        // the selection start is always moved by 1 character
                        this.selectionStart = selStart + 1;
                        // move the selection end over by the total number of tabs inserted
                        this.selectionEnd = selEnd + 1 + numTabs;
                    }
                }
            } else {
                // "normal" case (no selection or selection on one line only)
                
                // if the shift key was pressed, remove a tab instead of inserting one
                if (e.shiftKey) {
                    // if the character before the selection is a tab, remove it
                    if (text.charAt(selStart - 1) === '\t') {
                        this.value = text.slice(0, selStart - 1) + text.slice(selStart);
                        
                        // set start and end points
                        if (range) { // IE
                            // collapses range and moves it by -1 character
                            range.move('character', selStart - 1 - preNewlines);
                            range.select();
                        } else {
                            this.selectionEnd = this.selectionStart = selStart - 1;
                        }
                    }
                } else {
                    // no shift key - insert a tab
                    if (range) { // IE
                        // if no text is selected and the cursor is at the beginning of a line
                        // (except the first line), IE places the cursor at the carriage return character
                        // the tab must be placed after the \r\n pair
                        if (text.charAt(selStart) === '\r') {
                            this.value = text.slice(0, selStart + 2) + '\t' + text.slice(selEnd + 2);
                            // collapse the range and move it to the appropriate location
                            range.move('character', selStart + 2 - preNewlines);
                        } else {
                            this.value = text.slice(0, selStart) + '\t' + text.slice(selEnd);
                            // collapse the range and move it to the appropriate location
                            range.move('character', selStart + 1 - preNewlines);
                        }
                        range.select();
                    } else {
                        this.value = text.slice(0, selStart) + '\t' + text.slice(selEnd);
                        this.selectionEnd = this.selectionStart = selStart + 1;
                    }
                }
            }
            
            // this is really just for Firefox, but will be executed by all browsers
            // whenever the textarea value property is reset, Firefox scrolls back to the top
            // this will reset it to the original scroll value
            this.scrollTop = initScrollTop;
            
            // prevent the default action
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.returnValue = false;
        }
    });
    
    // Opera (and Firefox) also fire a keypress event when the tab key is pressed
    // Opera requires that the default action be prevented on this event, or the
    // textarea will lose focus (preventDefault is enough, IE never fires this
    // for the tab key)
    appTOAddListener(content, 'keypress', function (e) {
        if (e.keyCode === 9 && e.preventDefault) {
            e.preventDefault();
        }
    });
});