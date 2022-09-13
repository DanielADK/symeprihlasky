"use strict";
function unsignPerson(hash) {
    $.ajax({
        type: "DELETE",
        headers: {"Content-Type": "application/json"},
        url: "/api/applications/"+hash,
    }).done(function() {
        customPopUpMin($("#successSubmit"));
        $("#signedPeople").DataTable().ajax.reload();
    }).fail(function () {
        customPopUpMin($("#failSubmit"));
    });
}

$(document).ready(function () {
    $('#signedPeople').DataTable({
        "ajax": {
            "url": ajaxSignedChildrenList,
            "dataSrc": ""
        },
        responsive: true,
        dom: 'Bfrtipl',
        buttons: [
            {
                text: '<i class=\"fa fa-refresh\" id="tableRefresh"></i>',
                action: function (e, dt) {
                    dt.ajax.reload();
                }
            }, 'excel', 'csv'],
        "columns": signedChildrenColumns,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[2, 'desc'], [6, 'asc']],
    });

});
