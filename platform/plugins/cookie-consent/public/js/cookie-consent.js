(()=>{"use strict";$(document).ready((function(){window.canopyCookieConsent=function(){var e,i=$("div[data-site-cookie-name]").data("site-cookie-name"),o=$("div[data-site-cookie-domain]").data("site-cookie-domain"),t=$("div[data-site-cookie-lifetime]").data("site-cookie-lifetime"),n=$("div[data-site-session-secure]").data("site-session-secure");function a(){var e,a,s,d;e=i,a=1,s=t,(d=new Date).setTime(d.getTime()+24*s*60*60*1e3),document.cookie=e+"="+a+";expires="+d.toUTCString()+";domain="+o+";path=/"+n,c()}function c(){$(".js-cookie-consent").hide()}return e=i,-1!==document.cookie.split("; ").indexOf(e+"=1")&&c(),$(document).on("click",".js-cookie-consent-agree",(function(){a()})),{consentWithCookies:a,hideCookieDialog:c}}()}))})();