/**
 * @author Kwamelal
 * @copyright 2020 - present Kwamelal
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

"use strict";

if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}
  
if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        var el = this;

        do {
            if (Element.prototype.matches.call(el, s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

String.prototype.capitalize = function(){
    return this.replace(/\b\w/g, l => l.toUpperCase());
};

if (typeof Array.isArray === 'undefined') {
    Array.isArray = function(obj) {
      return Object.prototype.toString.call(obj) === '[object Array]';
    }
}