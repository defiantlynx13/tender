/*
 |  tail.select - The vanilla solution to make your HTML select fields AWESOME!
 |  @file       ./langs/tail.select-fr.js
 |  @author     SamBrishes <sam@pytes.net>
 |  @version    0.5.16 - Beta
 |
 |  @website    https://github.com/pytesNET/tail.select
 |  @license    X11 / MIT License
 |  @copyright  Copyright © 2014 - 2019 SamBrishes, pytesNET <info@pytes.net>
 */
/*
 |  Translator:     Anthony Rabine - (https://github.com/arabine)
 |  GitHub:         https://github.com/pytesNET/tail.select/issues/11
 */
;(function(factory){
   if(typeof(define) == "function" && define.amd){
       define(function(){
           return function(select){ factory(select); };
       });
   } else {
       if(typeof(window.tail) != "undefined" && window.tail.select){
           factory(window.tail.select);
       }
   }
}(function(select){
    select.strings.register("fa", {
        all: "همه",
        none: "هیچ کدام",
        empty: "خالی",
        emptySearch: "گزینه ای یافت نشد",
        limit: "محدودیت تعداد انتخاب",
        placeholder: "انتخاب کنید...",
        placeholderMulti: ":limit مورد انتخاب کنید",
        search: "جستجو ...",
        disabled: "غیرفعال"
    });
    return select;
}));
