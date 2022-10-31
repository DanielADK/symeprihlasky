import $ from "jquery";
import { ajaxPrepare } from "../admin";

$(document).ready(function() {

    ["sex", "member", "email", "phone", "birthDateWithAge", "parent", "role", "filled"]
    .forEach(function (item) {
        let object = $('#' + item);
        object.html(ajaxPrepare(item, object.text()));

    });
});
