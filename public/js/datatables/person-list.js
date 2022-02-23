$(document).ready(function () {
    $('#allPeople').DataTable({
        "ajax": {
            "url": "/api/people?deleted=false",
            "dataSrc": ""
        },
        responsive: true,
        dom: 'Bfrtipl',
        buttons: [
            {
                text: "<i class=\"fa fa-refresh\"></i>",
                action: function (e, dt) {
                    dt.ajax.reload();
                }
            }, 'excel', 'csv'],
        "columns": [
            {"data": "id"},
            {
                "data": null,
                render: function (data) {
                    return "<a href=\"/admin/seznamdite/" + data.id + "\">" +
                        data.name +
                        " " +
                        data.surname +
                        "</a>";
                }
            },
            {
                "data": null,
                render: function (data) {
                    return ajaxPrepare("email", data);
                }
            },
            {
                "data": null,
                render: function (data) {
                    return ajaxPrepare("phone", data);
                }
            },
            {
                "data": null,
                render: function (data) {
                    return ajaxPrepare("role", data.roles);
                }
            },

        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[0, 'asc']]
    });
});
