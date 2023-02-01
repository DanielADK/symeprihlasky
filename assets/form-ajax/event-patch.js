import $ from "jquery";
import "bootstrap-daterangepicker";
import {reloadEventsStats} from "../datatables/event-list";
import {customPopUpMin} from "../admin";
$(document).ready(function () {
    $("#dateRange").daterangepicker({
        "locale": {
            "format": "DD.MM.YYYY",
            "separator": " - ",
            "applyLabel": "Použít",
            "cancelLabel": "Zrušit",
            "fromLabel": "Od",
            "toLabel": "Do",
            "weekLabel": "T",
            "daysOfWeek": [
                "Ne",
                "Po",
                "Út",
                "St",
                "Čt",
                "Pá",
                "So"
            ],
            "monthNames": [
                "Leden",
                "Únor",
                "Březen",
                "Duben",
                "Květen",
                "Červen",
                "Červenec",
                "Srpen",
                "Září",
                "Říjen",
                "Listopad",
                "Prosinec"
            ],
            "firstDay": 1
        }
    });
})

function editEvent(event) {
    event.preventDefault();
    var sendData = $('#editEvent').serializeArray();
    if ((sendData.indexOf('=&') > -1)) {
        customPopUpMin($('#emptySubmit'));
        return;
    }

    $.ajax({
        type: "PATCH",
        headers: {'Content-Type': 'application/json'},
        url: "/api/event/"+sendData.id,
        data: JSON.stringify(sendData)
    }).done(function() {
        customPopUpMin($("#successSubmit"));
        $('#events').DataTable().ajax.reload(reloadEventsStats, false);
    }).fail(function () {
        customPopUpMin($("#failSubmit"));
        reloadEventsStats();
    });
}
window.editEvent = editEvent;