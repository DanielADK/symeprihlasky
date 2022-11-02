import $ from "jquery";
import "../datatables"
import {ajaxPrepare} from "../admin";

export function reloadPersonStats() {
    var table = $('#people').DataTable();
    document.getElementById("countOfUsers").innerText = table.rows().data().length;
    document.getElementById("countOfAdmins").innerText =
        table.rows().data().filter(function(row) {
            return (row.roles.indexOf("ROLE_ADMIN") > -1);
        }).length;
    document.getElementById("countOfInstructors").innerText =
        table.rows().data().filter(function(row) {
            return (row.roles.indexOf("ROLE_INSTRUCTOR") > -1);
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

let columns = [
    {
        "data": null,
        render: function (data) {
            return data.name + " " + data.surname;
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("email", data.email);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("phone", data.phone);
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
            return ajaxPrepare("role", data.roles);
        }
    }];
let adminCols = [
    {
        "data": null,
        render: function(data) {
            return '<div class="btn-group-vertical">' +
                '<a href="/admin/dospeli/zobrazit/' + data.id + '" class="btn btn-block btn-sm btn-info"><i class="fa fa-fw fa-info"></i>Informace</a>' +
                '<a href="/admin/dospeli/upravit/' + data.id + '" class="btn btn-block btn-sm btn-success"><i class="fa fa-fw fa-wrench"></i>Upravit</a>' +
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
    $('#people').DataTable({
        "ajax": {
            "url": ajaxURL,
            "dataSrc": ""
        },
        buttons: [
            {
                text: '<i class=\"fa fa-refresh\" id="tableRefresh"></i>',
                action: function (e, dt) {
                    dt.ajax.reload();
                    reloadPersonStats();
                }
            }, 'excel', 'csv'],
        "columns": (admin) ? columns.concat(adminCols) : columns,
        "order": [[0, 'asc']],
        "fnInitComplete": reloadPersonStats
    });
});