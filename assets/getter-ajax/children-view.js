import $ from "jquery";
import { ajaxPrepare } from "../admin";

$(document).ready(function() {

    ["sex", "member", "birthDateWithAge", "parent"]
        .forEach(function (item) {
            let object = $('#' + item);
            object.html(ajaxPrepare(item, object.text()));

        });
});
