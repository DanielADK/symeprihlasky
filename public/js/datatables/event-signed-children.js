$(document).ready(function () {
    var table = $('#signedPeople').DataTable({
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
