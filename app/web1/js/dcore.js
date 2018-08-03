//D.CORE_V4.0 SCRIPT by HeavenDesign.cz | Do not copy or use part of this script.
$(document).ready(function(){
//Target _blank
$("body .targetblank").click(function(){$(this).attr('target','_blank');});
$('#header div.logo span.over').fadeOut(250);
$('#header div.logo').hover(function(){$(this).find("span.over").fadeIn(250);}, function(){$(this).find("span.over").stop().fadeOut(250);})
$("#language li").hover(function(){
    if($(this).hasClass("on")){}
    else {
      $(this).find("a").stop().fadeOut(0).css("background", "#F62C30").css("color", "#FFFFFF").fadeIn(300);
      $("#language li.on").stop().fadeOut(0).css("background", "transparent").css("color", "#FFFFFF").fadeIn(300);
      }
}, function(){
    if($(this).hasClass("on")){}
    else {
      $(this).find("a").stop().fadeOut(0).css("background", "transparent").css("color", "#FFFFFF").fadeIn(300);
      $("#language li.on").stop().fadeOut(0).css("background", "#F62C30").css("color", "#FFFFFF").fadeIn(300);
      }
});
//Mainmenu Responsive
$("#mainmenu-toggle").click(function(){
    $(this).toggleClass("closemenu");
    if($(this).hasClass("closemenu")){
    $("#mainmenu").fadeIn(200);
    $(".mainmenu-overlay").fadeIn(200);
    $('html, body').animate({ scrollTop: $("body").offset().top }, 800);
    ismenuon=true;
    } else {
    $("#mainmenu").fadeOut(200);
    $(".mainmenu-overlay").fadeOut(200);
    ismenuon=false;
    }
});
$(window).resize(function() {
  if($("#mainmenu-toggle").is(":hidden")){
  $("#mainmenu-toggle").removeClass("closemenu");
  $("#mainmenu").fadeIn(200);
   $(".mainmenu-overlay").fadeOut(200);  
  }
  if($("#mainmenu-toggle").is(":visible")){
  $("#mainmenu-toggle").removeClass("closemenu");
  $("#mainmenu").hide(0); $(".mainmenu-overlay").fadeOut(200);
  }
});
/*
var productListNum = $('#productList .item').length;
//if(productListNum == 2) { $('#productList .item').css({width: "42%"});}
//if(productListNum == 1) { $('#productList .item').css({width: "90%"});}
if(productListNum == 2) { $('#productList .item').addClass("item50");}
if(productListNum == 1) { $('#productList .item').addClass("item100");}
*/

equalheight = function(container){
var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPostion = 0;
 $(container).each(function() {
   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;
   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0;
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}
var equalheightList = function(){
  equalheight('.cols .item');
  equalheight('.cols .itemContent');
  equalheight('.cols .itemText');
  equalheight('.cols .itemText ul');
  equalheight('#productList .item .image');
  equalheight('#productList .item .title');
  equalheight('#productList .item .pricebox');
  equalheight('#productList .item .price');
  equalheight('#productList .item .text');
  equalheight('#productList .item .bonuses');  
  equalheight('#productList .item');
  equalheight('#bonusList .item .image');
  equalheight('#bonusList .item .bonusbox');
  equalheight('#bonusList .item .bonus');
  equalheight('#bonusList .item .text');
  equalheight('#bonusList .item .info');
  equalheight('#bonusList .item');
  equalheight('#newsList .item');
  equalheight('#newsList .item .text');  
  equalheight('.topsites ');
  equalheight('#top20sites .sites .item');
}
$(window).load(function(){
  equalheightList();
});
$(window).resize(function(){
  equalheightList();
});
//Top
    $("#topbox").click(function() {
      $('html, body').animate({
      scrollTop: $("#page").offset().top
      }, 800);
    });
//ScrollTo
$(".anchor").click(function(){
    var getscrollto = $(this).attr("data-scrollto");
    $('html, body').animate({
    scrollTop: $("#"+getscrollto).offset().top
    }, 600);
});
$(".arrow-down").click(function(){
    var getscrollto = $(this).attr("scrollto");
    $('html, body').animate({
    scrollTop: $("#"+getscrollto).offset().top
    }, 600);
});
//Index - button-book
    $(".button-book-index, .offer").click(function(event) {
      event.preventDefault();
      $('html, body').animate({
      scrollTop: $("#productCats").offset().top
      }, 800);
    });
    
    
//animace
$('#cars .cars1').fadeOut(0);
$('#cars .cars2').delay(2500).fadeOut(1200);
$('#cars .cars1').delay(2500).fadeIn(1200);
//vuz
if($("#vuz")){
$(window).load(function() {
      $("#vuz .description div").hide(0);
      $("#vuz .description div").eq(0).show(0);
      $("#vuz .description").fadeIn(300);  
});
$("#vuz .points div").click(function() {
      getnum = $(this).index();
      $("#vuz .points div").removeClass("on"); 
      $(this).addClass("on");
      $("#vuz .description div").fadeOut(200); 
      $("#vuz .description div").eq(getnum).fadeIn(300);   
});
}

//top20
$("#top20buttons .bt").click(function(){  
    $("#top20buttons .bt").removeClass("on"); $(this).addClass("on");  
    var tindex = $("#top20buttons .bt").index(this);
    $("#top20sites .sites").fadeOut(250);
    $("#top20sites .sites").eq(tindex).fadeIn(250);
    equalheight('#top20sites .sites .item');
    /*alert(tindex);*/
});
$("#top20sites .sites").fadeOut(0);
$("#top20sites .sites").eq(0).fadeIn(250);
equalheight('#top20sites .sites .item');


// mapatrasy 
if($("#mapa-trasy")){
$(window).load(function() {
      $("#mapa-trasy .description").hide(0).html("");
      var gettitle = $(".topsites").eq(0).find("h2").html();
      var getimage = $(".topsites").eq(0).find("img").attr("src");
      $("#mapa-trasy .description").delay(150).append("<img src='"+getimage+"' class='w100' alt='' /><br /><span class='center big'>"+gettitle+"</span>").fadeIn(300);  
});
$("#mapa-trasy .points div").click(function() {
      getnum = $(this).index();
      $("#mapa-trasy .points div").removeClass("on"); 
      $(this).addClass("on");
      $("#mapa-trasy .description").fadeOut(0).html("");
      var gettitle = $(".topsites").eq(getnum).find("h2").html();
      var getimage = $(".topsites").eq(getnum).find("img").attr("src");
      $("#mapa-trasy .description").delay(150).append("<img src='"+getimage+"' class='w100' alt='' /><br /><span class='center big'>"+gettitle+"</span>").fadeIn(300);  
});
// mapatrasy - tlačítka
$("#mapa-trasy .trasa120minut").fadeOut(100);
$("#mapa-trasy .button60minut").click(function() {
  $("#mapa-trasy .bt").removeClass("on"); $(this).addClass("on");
  $("#mapa-trasy .trasa60minut").fadeIn(300); 
  $("#mapa-trasy .trasa120minut").fadeOut(100);
});
$("#mapa-trasy .button120minut").click(function() {
  $("#mapa-trasy .bt").removeClass("on"); $(this).addClass("on");
  $("#mapa-trasy .trasa120minut").fadeIn(300);
  $("#mapa-trasy .trasa60minut").fadeOut(100);
});
$("#mapa-trasy .buttontopsites").click(function() {
      //event.preventDefault();
      $('html, body').animate({
      scrollTop: $("#topsites").offset().top
      }, 600);
});
}
//galerie nabidek a bonusu
$("#bonusList .item .image a").hover(function(){
    $(this).find("span").animate({opacity: '1.0'}, 300);
}, function(){
    $(this).find("span").animate({opacity: '0.70'}, 300);
});
$("#productList .item .image a").hover(function(){
    $(this).find("span").animate({opacity: '1.0'}, 300);
}, function(){
    $(this).find("span").animate({opacity: '0.70'}, 300);
});
//Forms
var searchstring_placeholder_string = $("#searchstring").attr("placeholder");
$("#searchstring").focus(function() {
 $(this).attr("placeholder", "");
});
$("#searchstring").focusout(function () {
   $(this).attr("placeholder", searchstring_placeholder_string);
});
$("#companybox-enable").click(function() { 
if($("#companybox-enable").prop('checked')) {
      $("#companybox").show(300);
    } else {
      $("#companybox").hide(300);
    }
});
$("#deliverybox-enable").click(function() { 
if($("#deliverybox-enable").prop('checked')) {
      $("#deliverybox").show(300);
    } else {
      $("#deliverybox").hide(300);
    }
});
var enableCheckPassword = true;
if($("#c-unregistered").length){
if($("#c-unregistered").prop('checked')) { enableCheckPassword = true; } else { enableCheckPassword = false;}
}
$("input[name=customer]").click(function() {
if($("#c-unregistered").prop('checked')) {
      $("#repasswdbox").show(300); enableCheckPassword = true;
    } else {
      $("#repasswdbox").hide(300); enableCheckPassword = false; $("#repasswd").val("");
    }
});
$("input[name=payment]").click(function() { 
if($("#payment_coupon").prop('checked')) {
      $("#couponbox").show(300);
    } else {
      $("#couponbox").hide(300);
    }
});
$("#forms-comment").hide(0);
$('#forms-comment-enable').click(function() {
      //event.preventDefault();
      $("#forms-comment").show(500);
      $(this).hide(500);
});
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,6})+$/;
  return regex.test(email);
}
function IsGoodPasswd() {
  var passwd = $("#passwd").val();
  if(passwd.length >= 5) { return true } else { return false }
}
function IsSamePasswd() {
  var passwd = $("#passwd").val();
  var repasswd = $("#repasswd").val();
  if(passwd == repasswd) { return true } else { return false }
}
function IsGoodPhoneCode() {
  var testphone = true;
  var passwd = $("#phone_code").val();
  //if(passwd.length != 9) {testphone = false;} 
  if(isNaN(passwd)) {testphone = false; $("#phone_code").val("");} 
  if(testphone){ return true } else { return false }
}
function IsGoodPhone() {
  var testphone = true;
  var passwd = $("#phone").val();
  //if(passwd.length != 9) {testphone = false;} 
  if(isNaN(passwd)) {testphone = false; $("#phone").val("");} 
  if(testphone){ return true } else { return false }
}
function FormMark(mark) {
 $("#"+mark).css("border", "solid 2px #F00");
}
function FormUnMark(mark) {
 $("#"+mark).css("border", "solid 1px #CCC");
}
function FormMarkRadio(mark) {
 $(mark).css("background", "#F00");
}
function FormUnMarkRadio(mark) {
 $(mark).css("background", "#FFF");
}
function FormScrollTop() {
var headerh = $('#header').height();
$('html, body').animate({ scrollTop: headerh+25+"px" }, 500);
}
function FormSetCars(){
  //var maxpeople = parseInt($("#numpeople").attr('max')); console.log(maxpeople);
  var carload = parseInt($("#numpeople").attr('data-carload'));
  var getnumcars = Math.ceil($("#numpeople").val()/carload);
  $("#numcars").val(getnumcars); $("#numcars").attr("min", getnumcars);
  
  
}
function IsNum(mark) {
  if(isNaN(parseInt($(mark).val()))) {$(mark).val(1);}
  $(mark).val(Math.round(parseFloat($(mark).val())));
  var nummin = $(mark).attr("min"); if(parseInt($(mark).val())<nummin) {$(mark).val(nummin);}
  var nummax = $(mark).attr("max"); if(parseInt($(mark).val())>nummax) {$(mark).val(nummax);}
   console.log(nummax);
}
$("#numpeople").change(function() {
  IsNum(this);
  FormSetCars();
});
$("#numcars").change(function() { 
  IsNum(this);
});
$("#numhours").change(function() { 
  IsNum(this);
});
$("input.accessory").change(function() { 
  IsNum(this);
});
$("#form-email-info").hide();
$("#form-passwd-info").hide();
$("#form-repasswd-info").hide();
$("#form-phone-info").hide();
$("form").submit(function( event ) {
var formsubmit = true;
if($("#passwd").length){ if($("#passwd").val() != ""){ FormUnMark("passwd"); } else { FormMark("passwd"); formsubmit = false;}}
if($("#repasswd").length && enableCheckPassword){ if($("#repasswd").val() != ""){ FormUnMark("repasswd"); } else { FormMark("repasswd"); formsubmit = false;}}
if($("#phone").length){ if($("#phone").val() != ""){ FormUnMark("phone"); } else { FormMark("phone"); formsubmit = false;}}
if($("#passwd").length){
  if($("#passwd").val() != ""){
  if(IsGoodPasswd()) { FormUnMark("passwd"); $("#form-passwd-info").hide(); } else {FormMark("passwd"); $("#form-passwd-info").show().css("color", "#F00"); formsubmit = false;}
  }
}
if($("#repasswd").length && enableCheckPassword){
  if($("#repasswd").val() != ""){
  if(IsSamePasswd()) { FormUnMark("repasswd"); $("#form-repasswd-info").hide(); } else { FormMark("repasswd"); $("#form-repasswd-info").show().css("color", "#F00"); formsubmit = false;}
  }
}
if($('input[name="time"]').length){ if ($('input[name="time"]:checked').length > 0){ $('#time .radio').removeClass("not-checked"); } else { $('#time  .radio').addClass("not-checked"); formsubmit = false;}}
if($('input[name="payment"]').length){ if ($('input[name="payment"]:checked').length > 0){ $('#payment .radio').removeClass("not-checked"); } else { $('#payment  .radio').addClass("not-checked"); formsubmit = false;}}
if($("#fullname").length){ if($("#fullname").val() != ""){ FormUnMark("fullname"); } else { FormMark("fullname"); formsubmit = false;}}
if($("#email").length){ var validateemail = $("#email").val(); if(IsEmail(validateemail)){ FormUnMark("email"); $("#form-email-info").hide(); } else { FormMark("email"); $("#form-email-info").show().css("color", "#F00"); formsubmit = false;}}
if($("#phone_code").length){ 
  if($("#phone_code").val() != ""){ 
   if(IsGoodPhoneCode()) {FormUnMark("phone_code"); $("#form-phone-code-info").hide();} else { FormMark("phone_code"); $("#form-phone-code-info").show().css("color", "#F00"); formsubmit = false;}
   }
}
if($("#phone_cc").length){ 
  if($("#phone_cc").val() != ""){ FormUnMark("phone_cc"); } else { FormMark("phone_cc"); formsubmit = false;}
}
if($("#phone").length){
 if($("#phone").val() != ""){ 
   if(IsGoodPhone()) {FormUnMark("phone"); $("#form-phone-info").hide();} else { FormMark("phone"); $("#form-phone-info").show().css("color", "#F00"); formsubmit = false;}
   }
}
if($("#pickup_loc").length){ if($("#pickup_loc").val() != ""){ FormUnMark("pickup_loc"); } else { FormMark("pickup_loc"); formsubmit = false;}}
if($("#street").length){ if($("#street").val() != ""){ FormUnMark("street"); } else { FormMark("street"); formsubmit = false;}}
if($("#zip").length){ if($("#zip").val() != ""){ FormUnMark("zip"); } else { FormMark("zip"); formsubmit = false;}}
if($("#city").length){ if($("#city").val() != ""){ FormUnMark("city"); } else { FormMark("city"); formsubmit = false;}}
if($("#country").length){ if($("#country").val() != ""){ FormUnMark("country"); } else { FormMark("country"); formsubmit = false;}}
if($('#companybox-enable').is(':checked')) {
if($("#company").length){ if($("#company").val() != ""){ FormUnMark("company"); } else { FormMark("company"); formsubmit = false;}}
if($("#ic").length){ if($("#ic").val() != ""){ FormUnMark("ic"); } else { FormMark("ic"); formsubmit = false;}}
}
if($('#deliverybox-enable').is(':checked')) {
if($("#dfname").length){ if($("#dfname").val() != ""){ FormUnMark("dfname"); } else { FormMark("dfname"); formsubmit = false;}}
if($("#dlname").length){ if($("#dlname").val() != ""){ FormUnMark("dlname"); } else { FormMark("dlname"); formsubmit = false;}}
if($("#dstreet").length){ if($("#dstreet").val() != ""){ FormUnMark("dstreet"); } else { FormMark("dstreet"); formsubmit = false;}}
if($("#dzip").length){ if($("#dzip").val() != ""){ FormUnMark("dzip"); } else { FormMark("dzip"); formsubmit = false;}}
if($("#dcity").length){ if($("#dcity").val() != ""){ FormUnMark("dcity"); } else { FormMark("dcity"); formsubmit = false;}}
}

if($('input[name="souhlas"]').length){ if ($('input[name="souhlas"]:checked').length > 0){ $('#souhlasbox .checkbox').removeClass("not-checked"); } else { $('#souhlasbox .checkbox').addClass("not-checked"); formsubmit = false;}}

if(formsubmit == true) { return; } 
else { 
  event.preventDefault(); 
  $("#form-alert").show(500); $("#form-alert").delay(5000).hide(500); 
  FormScrollTop(); }
});
$("#select-country").hide();
$('body').click(function(){
  $("#select-country").hide();
});
$("#phone_cc, #select-country-flag").click(function(event){
 event.stopPropagation();
 $("#select-country").show();
});
$("#select-country div").prepend("<span></span>");
$("#select-country div").click(function(){
  var isvalue = $(this).attr('value');
  $("#phone_cc").attr('value','+'+isvalue);
  var isflag = $(this).attr('class');
  $("#select-country-flag").attr('class',''); $("#select-country-flag").addClass(''+isflag+'');
  $("#select-country").hide();
});
var InitFacebookDone = false;
if($('#facebook').hasClass("show")){ $('#facebook .facebook-content').fadeIn(0);} else { $('#facebook .facebook-content').delay(300).fadeOut(200);}
$('#facebook .facebook-label, #facebook .close').click(function(){
if(InitFacebookDone == false){InitFacebook(); InitFacebookDone = true;}
$('#facebook').toggleClass("show");
if($('#facebook').hasClass("show")){ $('#facebook .facebook-content').fadeIn(0);} else { $('#facebook .facebook-content').delay(300).fadeOut(200);}
});
var InitFacebook = function(){
(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v2.6&appId=637162662981233";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));
}
 WebFontConfig = {
    google: { families: [ 'Source+Sans+Pro:400,600,400italic,600italic:latin,latin-ext' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })();
});



// Cookie Info 
var webCookie = "historycarprague"+"Cookie" // přepsat dle domény
var webCookiePrepend = ".body" // prvek kde se má vyobrazit

function SetCookie(cookieName,cookieValue,nDays) {
 var today = new Date();
 var expire = new Date();
 if (nDays==null || nDays==0) nDays=1;
 expire.setTime(today.getTime() + 3600000*24*nDays);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString()+";path=/";
}
function ReadCookie(cookieName) {
 var theCookie=" "+document.cookie;
 var ind=theCookie.indexOf(" "+cookieName+"=");
 if (ind==-1) ind=theCookie.indexOf(";"+cookieName+"=");
 if (ind==-1 || cookieName=="") return "";
 var ind1=theCookie.indexOf(";",ind+1);
 if (ind1==-1) ind1=theCookie.length; 
 return unescape(theCookie.substring(ind+cookieName.length+2,ind1));
}
function DeleteCookie(cookieName) {
  document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
}
var cookieisok = ""+ReadCookie(webCookie);
var testCookie = ""; SetCookie("testCookie","ok",365); var testCookie = ""+ReadCookie("testCookie");  //test cookies availability
$(document).ready(function(){
if(cookieisok != "ok" && testCookie == "ok") {
$(".cookie-notice").fadeIn(300);
//$(webCookiePrepend).prepend("<div class='cookie-notice'>Pro účely analýzy návštěvnosti používáme nástroj Google Analytics, který využívá soubory cookies. Předpokládáme, že s použitím cookies souhlasíte. <a href='informace-cookies.html' class='underline'>Více informací</a> <a href='javascript:;' class='cookie-button'>SOUHLASÍM</a></div>")
}
$(".cookie-button").click(function(){
  $(".cookie-notice").fadeOut(300);
  SetCookie(webCookie,"ok",365);
});
/*
$(webCookiePrepend).prepend("<div class='cookie-test'><a href='javascript:;' class='cookie-delete'>DEL</a></div>");
$(".cookie-delete").click(function(){
  DeleteCookie(webCookie);
  $(".cookie-delete").fadeOut(300);
  $(".cookie-notice").fadeIn(300);
});*/
});