!function(n){var r={};function u(e){if(r[e])return r[e].exports;var t=r[e]={i:e,l:!1,exports:{}};return n[e].call(t.exports,t,t.exports,u),t.l=!0,t.exports}u.m=n,u.c=r,u.d=function(e,t,n){u.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},u.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},u.t=function(t,e){if(1&e&&(t=u(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(u.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)u.d(n,r,function(e){return t[e]}.bind(null,r));return n},u.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return u.d(t,"a",t),t},u.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},u.p="",u(u.s=2)}([,,function(e,t,n){"use strict";n.r(t);n(3)},function(e,t){window.Drupal.behaviors.sdss_subtheme={attach:function(){!function(t){t(".su-brand-bar,.su-masthead").wrapAll('<div class="fixed-header">');var e=t("#block-sdss-subtheme-branding").attr("class");t(".fixed-header").addClass(e);var n=t(".sdss-newsroom--menu .menu-item--expanded > a");t(".menu-item--expanded > a").click(function(e){e.preventDefault(),t(n).removeClass("active"),t(this).addClass("active")})}(jQuery)},detach:function(){}}}]);
//# sourceMappingURL=scripts.js.map