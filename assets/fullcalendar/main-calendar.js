import {Calendar} from "fullcalendar";

$(function () {
    var date = new Date();
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var calendar = new Calendar($("#calendar"), {
        plugins: ['bootstrap', 'dayGrid'],
        themeSystem: 'bootstrap3',
        initialView: 'dayGridMonth',
        weekNumbers: true,
        displayEventTime: false,
        locale: 'cs',
        selectable: true,
        header: {
            left: 'title',
            right: 'prev,today,next'
        },
        loading:
            function (isLoading) {
                if (isLoading)
                    $('#calendar-overlay').show();
                else
                    $('#calendar-overlay').hide();
            },
        events:
            function(start, end, timezone, callback) {
                $.ajax({
                    url: "/api/events?dateStart[after]=" +
                        start.format("YYYY-MM-DD 00:00:01") +
                        "&dateEnd[before]=" +
                        end.format("YYYY-MM-DD 00:00:01"),
                    type: "GET",
                    error: function(jqXHR, textStatus, errorThrown) {
                        customPopUpMin($("#failSubmit"));
                    },
                    success: function(jsondata){

                        var parseddata = [];
                        jsondata.forEach(event => {
                            let single = {};

                            single.id = event.id;
                            single.title = event.fullName;
                            single.start = event.dateStart;
                            single.end = new Date(event.dateEnd);
                            single.end.setDate(single.end.getDate()+1);
                            single.url = "/admin/akce/zobrazit/" + event.id;
                            single.display = "block"

                            switch (event.type) {
                                case "JA":
                                    single.backgroundColor = "#3c8dbc";
                                    single.borderColor = "#3c8dbc";
                                    break;
                                case "VA":
                                    single.backgroundColor = "#dd4b39";
                                    single.borderColor = "#dd4b39";
                                    break;
                                case "LT":
                                    single.backgroundColor = "#00a65a";
                                    single.borderColor = "#00a65a";
                                    break;
                                case "PV":
                                    single.backgroundColor = "#f39c12";
                                    single.borderColor = "#f39c12";
                                    break;
                                default:
                                    single.backgroundColor = "#23527c"
                                    single.borderColor = "#23527c";
                                    break;
                            }
                            parseddata.push(single);
                        });
                        callback(parseddata);
                    },
                    dataType: "json"
                });
            },
    });

    calendar.render();
});