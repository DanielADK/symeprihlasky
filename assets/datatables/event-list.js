"use strict";
import $ from 'jquery';
import '../datatables';
import {ajaxPrepare} from "../admin";

function reloadEventsStats()
{
    var table = $('#events').DataTable();
    $("#countOfEvents").html(table.rows().data().length);
    $("#countOfActive").html(
        table.rows().data().filter(function (row) {
            return row.activeApplication;
        }).length);
}

let columns = [
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
    {
        "data": null,
        render: function (data) {
            return '<div class="btn-group-vertical">' +
                '<a href="/admin/akce/zobrazit/' + data.shortName + '" class="btn btn-block btn-sm btn-info"><i class="fa fa-fw fa-info"></i>Informace</a>' +
                '<a href="/admin/akce/upravit/' + data.shortName + '" class="btn btn-block btn-sm btn-success"><i class="fa fa-fw fa-wrench"></i>Upravit</a>' +
                '</div>';
        }
    },
    {
        "data": null,
        render: function (data) {
            if (data.deleted) {
                return '<span class="label label-danger">SMAZÁN!</span>'
            } else {
                return '<span class="label label-default">Nikoliv</span>';
            }
        }
    }];

    $(document).ready(function () {
        $('#events').DataTable({
            ajax: {
                url: ajaxURL,
                dataSrc: ""
            },
            buttons: [{
                text: '<i class=\"fa fa-refresh\" id="tableRefresh"></i>',
                action: function (e, dt) {
                    dt.ajax.reload();
                    reloadEventsStats();
                }
            }, "print", 'csv'],
            columns: (admin) ? columns.concat(adminCols) : columns,
            fnInitComplete: reloadEventsStats,
            order: [[7, 'asc'], [3, 'asc']]
        });

    });
