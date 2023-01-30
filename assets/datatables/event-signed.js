"use strict";
import {customPopUpMin, ajaxPrepare} from "../admin";
import $ from "jquery";

function unsignPerson(hash) {
    $.ajax({
        type: "DELETE",
        headers: {"Content-Type": "application/json"},
        url: "/api/applications/"+hash,
    }).done(function () {
        customPopUpMin($("#successSubmit"));
        $("#signedPeople").DataTable().ajax.reload();
    }).fail(function () {
        customPopUpMin($("#failSubmit"));
    });
}
window.unsignPerson = unsignPerson;

var columns =
    [{
        "data": null,
        render: function (data) {
            return '<a href="/prihlaska/'+data.hash+'">' + data.hash + '</a>';
        }
    },
    {
        "data": null,
        render: function (data) {
            if (data.person != null) { return ajaxPrepare("fullname", data.person); }
            return ajaxPrepare("fullname", data.child);
        }
    },
    {
        "data": null,
        render: function (data) {
            if (data.person != null) { return ajaxPrepare("role", data.person.roles); }
            return "<span class=\"label label-success\">Dítě</span>";
        }
    },
    {
        "data": null,
        render: function (data) {
            if (data.person != null) { return ajaxPrepare("age", [data.person.birthDate, event_dateStart]); }
            return ajaxPrepare("age", [data.child.birthDate, event_dateStart]);
        }
    },
    {
        "data": null,
        render: function (data) {
            return ajaxPrepare("signDateTime", data.signDate);
        }
    }];

var adminCols =
    [{
        "data": null,
        render: function (data) {
            return  '<div class="btn-group">' +
                '<button onclick="unsignPerson(\''+data.hash+'\')" class="btn btn-warning" title="Odhlásit dítě" ><i class="fa fa-sign-out fa-1x"></i> Odhlásit</button>' +
                '</div>';
        }
    }];

    $(document).ready(function () {
        $('#signedPeople').DataTable({
            "ajax": {
                "url": ajaxSignedList,
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
                }, 'print', 'csv'],
            columns: (admin) ? columns.concat(adminCols) : columns,
            order: [[2, 'desc'], [4, 'desc']]
        });

    });
