import $ from 'jquery';
// import 'datatables.net-bs'
// let ajs = require("../admin");
import '../datatables';
import {ajaxPrepare} from "../admin";

function reloadEventsStats() {
    var table = $('#events').DataTable();
    document.getElementById("countOfEvents").innerText = table.rows().data().length;
    document.getElementById("countOfActive").innerText =
        table.rows().data().filter(function(row) {
            return row.activeApplication;
        }).length;
}

var columns = [
    {"data": "shortName"},
    {"data": "fullName"},
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("simpleDate", data.dateStart);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("simpleDate", data.dateEnd);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("type", data.type);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("address", data.address);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("activeApplication", data.activeApplication);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("capacity", data.capacity);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("priceMember", data.priceMember);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("priceOther", data.priceOther);
        }
    },
];

var adminCols = [
    // {% if is_granted("ROLE_EDIT_PERSON") or is_granted("ROLE_DELETE_PERSON") %}
    {
        "data": null,
        render: function(data) {
            return  '<div class="btn-group-vertical">' +
                '<a href="/admin/akce/zobrazit/'+data.shortName+'" class="btn btn-info" data-toggle="tooltip" title="Zobrazit informace o akci" ><i class="fa fa-fw fa-info"></i></a>' +
                '<a href="/admin/akce/upravit/'+data.shortName+'" class="btn btn-success" data-toggle="tooltip" title="Upravit akci" ><i class="fa fa-fw fa-wrench"></i></a>' +
                '</div>'
        }
    },
    {
        "data": null,
        render: function(data) {
            if (data.deleted) {
                return '<span class="label label-danger">SMAZÁN!</span>'
            } else {
                return '<span class="label label-default">Nikoliv</span>';
            }
        }
    }
];


$(document).ready(function () {
    $('#events').DataTable({
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
                    reloadEventsStats();
                }
            }, 'excel', 'csv'],
        "columns": (admin) ? columns.concat(adminCols) : columns,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json"
        },
        "order": [[7, 'asc'], [3, 'asc']],
        "fnInitComplete": reloadEventsStats
    });

});
