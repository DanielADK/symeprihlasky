import $ from "jquery";
import { ajaxPrepare } from "../admin";

$(document).ready(function () {
    ["activeApplication", "typeOfEvent", "capacity", "priceMember", "priceOther"]
    .forEach(function (item) {
        let object = $('#' + item);
        object.html(ajaxPrepare(item, object.text()));

    });
});
