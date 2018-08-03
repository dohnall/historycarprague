function loadStyleSheet(src) {
    if (document.createStyleSheet){
        document.createStyleSheet(src);
    }
    else {
       $("head").append($("<link rel='stylesheet' href='"+src+"' type='text/css' media='screen' />"));
    }
};
$(document).ready(function(){
loadStyleSheet("http://www.historycarprague.com/app/web1/design/jquery-ui.css}");
});