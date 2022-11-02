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
        reloadPersonStats();
    });
}