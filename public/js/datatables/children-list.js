function reloadPersonStats() {
    var table = $('#people').DataTable();
    document.getElementById("countOfChildren").innerText = table.rows().data().length;
    document.getElementById("countOfGirls").innerText =
        table.rows().data().filter(function(row) {
            return row.sex === "F";
        }).length;
    document.getElementById("countOfBoys").innerText =
        table.rows().data().filter(function(row) {
            return row.sex === "M";
        }).length;
    document.getElementById("countOfMembers").innerText =
        table.rows().data().filter(function(row) {
            return row.member === true;
        }).length;
}

$(document).ready(function () {
    var table = $('#people').DataTable({
        "ajax": {
            "url": ajaxURL,
            "dataSrc": ""
        },
        responsive: true,
        dom: 'Bfrtipl',
        buttons: [
            {
                text: '<i class=\"fa fa-refresh\" id="tableRefresh"></i>',
                action: function (e, dt) {
                    dt.ajax.reload();
                    reloadPersonStats();
                }
            }, 'excel', 'csv'],
        "columns": columns,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[0, 'asc']],
        "fnInitComplete": reloadPersonStats
    });

});
