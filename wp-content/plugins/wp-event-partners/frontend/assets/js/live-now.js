function clockTick() {
    var date = [];
    var startingdistance = [];
    var enddistance = [];
    var totalCount = jQuery('#totalCount').html();
    if(totalCount){
        for (var i = 0; i < totalCount; i++){  

    // var today = jQuery('#today_date').html();
    var time_zone = jQuery('#time_zone').html();
    //console.log(time_zone);

    if(time_zone){
        var d = new Date(new Date().toLocaleString("en-UK",{timeZone:time_zone}));
        var now = new Date(d).getTime();
    } else {
        var now = new Date().getTime();
    }
    // var now = new Date().getTime();
    // var d = new Date(new Date().toLocaleString("en-UK",{timeZone:time_zone}));
    // var dd = new Date(d).getTime();
   

    var startdate = jQuery('#event_startdate'+i).html();
    var endDate = jQuery('#event_enddate'+i).html();
    var starttime = jQuery('#starting'+i).html();
    var endtime = jQuery('#ending'+i).html();

    //console.log(endDate);

    var startdatetime = startdate + ' ' + starttime;
    var starting = new Date(startdatetime).getTime();
    startingdistance.push(starting - now);
    
    var ending = new Date(endDate).getTime();
    enddistance.push(now - ending);

    // console.log(startdatetime);
    // startingdistance[i] < 0 && enddistance[i] < 0
        if (startingdistance[i] < 0 && enddistance[i] < 0) {
            document.getElementById('live_nw'+i).innerHTML = "Live now";
        }
         else {
            document.getElementById('live_nw'+i).innerHTML = "";
        }
   
        }
    }
}
// here we run the clockTick function every 1000ms (1 second)
setInterval(clockTick, 1000);
