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

function reloadSignedPerson() {
    var table = $('#signedPeople').DataTable();
    document.getElementById("countOfSignedHelpers").innerText =
        table.rows().data().filter(function(row) {
            return (row.role.indexOf("ROLE_INSTRUCTOR") > -1);
        }).length;
    document.getElementById("countOfSignedLeaders").innerText =
        table.rows().data().filter(function(row) {
            return (row.role.indexOf("ROLE_LEADER") > -1);
        }).length;
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
                    reloadSignedPerson();
                }
            }, 'excel', 'csv'],
        "columns": signedChildrenColumns,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[2, 'desc'], [6, 'asc']],
        "fnInitComplete": reloadSignedPerson
    });

});
