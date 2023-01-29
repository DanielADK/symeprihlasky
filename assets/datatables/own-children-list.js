import $ from 'jquery';
import "../datatables"
import {ajaxPrepare} from "../admin";

let columns2 = [
    {
        "data": null,
        render: function (data) {
            return data.name + " " + data.surname;
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
            return ajaxPrepare("yesno", data.member);
        }
    },
    {
        "data": null,
        render: function (data) {
            return '<a href="/admin/dite/zobrazit/'+data.id+'" class="btn btn-block btn-sm btn-info"><i class="fa fa-fw fa-info"></i>Informace</a>';
        }
    }
];

$(document).ready(function () {
    $('#own-children').DataTable({
        "ajax": {
            "url": ajaxURL2,
            "dataSrc": ""
        },
        buttons: [
            {
                text: '<i class=\"fa fa-refresh\" id="tableRefresh"></i>',
                action: function (e, dt) {
                    dt.ajax.reload();
                }
            }, "print", 'csv'],
        "columns": columns2,
        "order": [[0, 'asc']]
    });

});
