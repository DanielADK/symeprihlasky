import $ from 'jquery';
import "../datatables"
import {ajaxPrepare} from "../admin";

function reloadPersonStats() {
    let table = $('#people').DataTable();
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


let columns = [
    {"data": "id"},
    {
        "data": null,
        render: function (data) {
            return data.name + " " + data.surname;
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("parent", data.parent);
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
            return ajaxPrepare("birthDateWithAge", data.birthDate);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("sex", data.sex);
        }
    },
    {"data": "shirtSize"},
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("yesno", data.member);
        }
    }
];

let adminCols = [
    {
        "data": null,
        render: function(data) {
        return '<div class="btn-group-vertical">' +
            '<a href="/admin/dite/zobrazit/'+data.id+'" class="btn btn-block btn-sm btn-info"><i class="fa fa-fw fa-info"></i>Informace<i class="fa fa-fw fa-wrench"></i></a>' +
            '<a href="/admin/dite/upravit/'+data.id+'" class="btn btn-block btn-sm btn-success"><i class="fa fa-fw fa-wrench"></i>Upravit</a>' +
            '</div>';
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
            }, "print", 'csv'],
        "columns": (admin) ? columns.concat(adminCols) : columns,
        "order": [[0, 'asc']],
        "fnInitComplete": reloadPersonStats
    });

});
