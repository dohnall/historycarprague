/**
 * Dhakra.Fader.SlideShow v4.2
 * Copyright (c) 2012 Branislav Fabry - HeavenDesign.cz
 * @author Branislav Fábry alias Dhakra
 * @version 4.2 Looped Fader
 * @lastmod 31.02.2014
 * Based on jQuery - Special thanks to jQuery (www.jquery.com) for great job!
 */

$(document).ready(function() {
    // settings
    var df_buttons = true; /* turn on/off prev-next buttons - set true / false */
    var df_navbuttons = true; /* turn on/off navigation buttons - set true / false */
    var df_clickstop = false; /* stop loop on any buttons - set true / false */
    var df_loop = true; /* turn on/off loop after last slide - set true / false */
    var df_looptime = 3000; /* time to wait on next slide in miliseconds */ 
    var df_fadeintime = 1000; /* duration of fade effect in miliseconds */
    var df_startfadein = 1000;
    
    
    // variables - do not change
    var df_fadeouttime = 10;
    var df_stopanimation = false;
    var df_slides = true;
    var df_root = $("#root").attr("href");
    
    // onstart
    $("#df_fader div.slides div.slide").hide();
    $("#df_fader div.slides div.slide:first").show();
    $("#df_fader div.slidesbox").hide();
		$("#df_fader div.slidesbox").fadeOut(10);
		$("#df_fader div.loader").fadeIn(500);
		$("#df_fader div.loaderhider").fadeOut(10);
		$("#df_fader div.slidernav").hide();
		$("#df_fader div.slidernav").fadeOut(10);
		$("#df_fader a.sliderprev").hide();
		$("#df_fader a.sliderprev").fadeOut(10);
		$("#df_fader a.slidernext").hide();
		$("#df_fader a.slidernext").fadeOut(10);		
		$("#df_fader div.slidermask").fadeOut(200);
    $("#df_fader div.slides div.slide:first").css("z-index", "3");
    $("#df_fader div.slides div.slide .content").css("display", "none");
    $("#df_fader div.slides div.slide:first .content").css("display", "inline");  
    
    
		$("#df_fader div.special").fadeIn(df_startfadein+df_fadeintime);		
		
if(document.getElementById('df_fader')) { 
        var df_posnum = 1;
        var df_oldposnum = 1;
        var df_logos = $("#df_fader div.slides div.slide").length;
        
        /* generate navigation pages from slides */
        var df_navs=df_logos;
        for (i=2; i<=df_navs; i++){
            $("#df_fader div.slidernav").append('<a class="pos" href="javascript:;">&nbsp;</a>'); 
        }
        var df_poses = $("#df_fader div.slidernav a").length; /* number of navs */
        
        var df_slidecontent = "";
        //$("#df_fader div.info").html(df_logos+"/"+df_poses);

        
        if(df_logos > 1 && df_poses > 1) {
        
        /* button Prev click */
            $("#df_fader a.sliderprev").click(function() {
                 if(df_slides) {
                    df_slides = false;
                    if(df_clickstop==true) {df_loop = false;}
                    clearTimeout(df_settimer);
                    df_oldposnum = df_posnum;
                    df_posnum -= 1; if(df_posnum<1) {df_posnum=df_poses;}
                    df_posnumset();
                    
                    $("#df_fader div.slides div.slide").each(function(i) {
                    if(i+1 == df_oldposnum) {var df_slidecontent = $(this).html();}
                    $("#df_fader div.slideold").html(df_slidecontent);
                    });
                    $("#df_fader div.slides div.slide").each(function(i) {
                    $(this).find(".content").fadeOut(500);
                    $(this).delay(500).fadeOut(df_fadeouttime);
                    
                    if(i+1 == df_posnum) {                        
                       $(this).fadeIn(df_fadeintime);
                       $(this).find(".content").delay(1000).fadeIn(df_fadeintime/2); 
                       }
                     });
                    
                    df_slides = true;
                    if (df_loop) {df_settimer = setTimeout(df_slideplay, df_looptime);}
                }
            });
            
        /* button Next click */
            $("#df_fader a.slidernext").click(function() {
                if(df_slides) {
                    df_slides = false;
                    if(df_clickstop==true) {df_loop = false;}
                    clearTimeout(df_settimer);
                    df_oldposnum = df_posnum;
                    df_posnum += 1; if(df_posnum>df_poses) {df_posnum=1;} 
                    df_posnumset();

                    $("#df_fader div.slides div.slide").each(function(i) {
                    if(i+1 == df_oldposnum) {var df_slidecontent = $(this).html();}
                    $("#df_fader div.slideold").html(df_slidecontent);
                    });
                    $("#df_fader div.slides div.slide").each(function(i) {
                    $(this).find(".content").fadeOut(500);
                    $(this).delay(500).fadeOut(df_fadeouttime);
                    
                    if(i+1 == df_posnum) {                        
                       $(this).fadeIn(df_fadeintime);
                       $(this).find(".content").delay(1000).fadeIn(df_fadeintime/2); 
                       }
                     });
                    
                    df_slides = true;
                    if (df_loop) {df_settimer = setTimeout(df_slideplay, df_looptime);}
                }
            });
            
        /* buttons Navigation click */  
            $("#df_fader div.slidernav a").click(function() {
             if(df_slides) {
                    df_slides = false;
                    if(df_clickstop==true) {df_loop = false;}
                    //df_loop = false;                     
                    clearTimeout(df_settimer);
                    df_oldposnum = df_posnum;
                    df_posnum = $("#df_fader div.slidernav a").index(this)+1;
                    
                    $("#df_fader div.slides div.slide").each(function(i) {
                    if(i+1 == df_oldposnum) {var df_slidecontent = $(this).html();}
                    $("#df_fader div.slideold").html(df_slidecontent);
                    });
                    $("#df_fader div.slides div.slide").each(function(i) {
                    $(this).find(".content").fadeOut(500);
                    $(this).delay(500).fadeOut(df_fadeouttime);
                    
                    if(i+1 == df_posnum) {                        
                       $(this).fadeIn(df_fadeintime);
                       $(this).find(".content").delay(1000).fadeIn(df_fadeintime/2); 
                       }
                     });

                    df_posnumset();
                    df_slides = true;
                    if (df_loop) {df_settimer = setTimeout(df_slideplay, df_looptime);}
                }
        });
        /* buttons Navigation set active */
          var df_posnumset= function() {     
            $("#df_fader div.slidernav a").removeClass("active");
            $("#df_fader div.slidernav a").each(function(i) {
              if(i+1 == df_posnum) $(this).addClass("active");
            });   
          }
        df_posnumset(); //set firsttime active 
        
        /* */
        /*var df_setstopanimation = function(){
          if(df_loop==false) { df_stopanimation=true;}
        }*/
        
        /* loop function */
        df_slideplay = function(){
           if(df_slides) {
                    clearTimeout(df_settimer);
                    df_oldposnum = df_posnum;
                    
                    df_posnum += 1; 
                    if(df_loop==false) { 
                      if(df_posnum>df_poses) {df_stopanimation=true;}
                      }
                    if(df_stopanimation) {}
                    else {
                    df_slides = false;
                    if(df_posnum>df_poses) {df_posnum=1;}
                    df_posnumset();
                    
                    $("#df_fader div.slides div.slide").each(function(i) {
                    if(i+1 == df_oldposnum) {var df_slidecontent = $(this).html();}
                    $("#df_fader div.slideold").html(df_slidecontent);
                    });
                    $("#df_fader div.slides div.slide").each(function(i) {
                    $(this).find(".content").fadeOut(500);
                    $(this).delay(500).fadeOut(df_fadeouttime);
                    
                    if(i+1 == df_posnum) {                        
                       $(this).fadeIn(df_fadeintime);
                       $(this).find(".content").delay(1000).fadeIn(df_fadeintime/2).css("z-index","1000"); 
                       }
                     });
                    
                    df_slides = true;
                    } // stopanimation
                } 
                if(df_stopanimation) {}
                else {df_settimer = setTimeout(df_slideplay, df_looptime);}
           } // end slideplay
    
      
      
        } /* endif logos */


        /* */
        var df_startSlideAnimation = function(){
            var df_fadeinslidesbox = function(){	
              $("#df_fader div.slidesbox").fadeIn(df_fadeintime);
              if(df_buttons) {$("#df_fader a.sliderprev").fadeIn(df_fadeintime); $("#df_fader a.slidernext").fadeIn(df_fadeintime);}
              if(df_navbuttons) {$("#df_fader div.slidernav").fadeIn(df_fadeintime);}
              $("#df_fader div.loader").fadeOut(200);
              }
            df_fadeinslices = setTimeout(df_fadeinslidesbox, df_startfadein);
            df_settimer = setTimeout(df_slideplay, df_startfadein+df_looptime);
        }
        
        df_startSlideAnimation();
        /*settimer = setTimeout(slideplay, looptime+1000);*/
    }
});
