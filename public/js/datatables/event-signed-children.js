
function reloadEventsStats() {
    var table = $('#signedChildren').DataTable();
    document.getElementById("countOfSignedChildren").innerText = table.rows().data().length;
        table.rows().data().filter(function(row) {
            return row.activeApplication;
        }).length;
}

$(document).ready(function () {
    var table = $('#signedChildren').DataTable({
        "ajax": {
            "url": ajaxURLSignedChildren,
            "dataSrc": ""
        },
        responsive: true,
        dom: 'Bfrtipl',
        buttons: [
            {
                text: '<i class=\"fa fa-refresh\" id="tableRefresh"></i>',
                action: function (e, dt) {
                    dt.ajax.reload();
                    reloadEventsStats();
                }
            }, 'excel', 'csv'],
        "columns": columns,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[6, 'asc'], [3, 'asc']],
        "fnInitComplete": reloadEventsStats
    });

});
