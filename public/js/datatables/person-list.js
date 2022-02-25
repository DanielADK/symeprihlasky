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
                }
            }, 'excel', 'csv'],
        "columns": columns,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[0, 'asc']],
        "fnInitComplete": function () {
            document.getElementById("countOfUsers").innerText = table.rows().data().length;
            document.getElementById("countOfAdmins").innerText =
                table.rows().data().filter(function(row) {
                    return (row.roles.indexOf("ROLE_ADMIN") > -1);
                    }).length;
            document.getElementById("countOfLeaders").innerText =
                table.rows().data().filter(function(row) {
                    return (row.roles.indexOf("ROLE_LEADER") > -1);
                }).length;
            document.getElementById("countOfParents").innerText =
                table.rows().data().filter(function(row) {
                    return (row.roles.indexOf("ROLE_PARENT") > -1);
                }).length;

        }
    });

});
