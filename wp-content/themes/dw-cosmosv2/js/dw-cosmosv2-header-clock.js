function dw_cosmosv2_get_date_time() {

    const DateString = document.querySelector('.date-date');
    const TimeString = document.querySelector('.time-time');

    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    if (month.toString().length == 1) {
        month = '0' + month;
    }
    if (day.toString().length == 1) {
        day = '0' + day;
    }
    if (hour.toString().length == 1) {
        hour = '0' + hour;
    }
    if (minute.toString().length == 1) {
        minute = '0' + minute;
    }
    if (second.toString().length == 1) {
        second = '0' + second;
    }
    // var dateTime = 'Date:&nbsp;' + month + '/' + day + '/' + year + '&nbsp;&nbsp;-&nbsp;&nbsp;Time:&nbsp;' + hour + ':' + minute + ':' + second;
    // return dateTime;	
    DateString.innerHTML = month + '/' + day + '/' + year;
    TimeString.innerHTML = hour + ':' + minute + ':' + second;
    return;
}

// example usage: realtime clock
setInterval(function() {
    //     dw_cosmosv2_current_Time = dw_cosmosv2_get_date_time();
    //     document.getElementById("dw-cosmosv2-digital-clock").innerHTML = dw_cosmosv2_current_Time;
    dw_cosmosv2_get_date_time();
}, 1000);