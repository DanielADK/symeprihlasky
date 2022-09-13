import $ from "jquery";
import { ajaxPrepare } from "../admin";

$(document).ready(function() {

    ["sex", "member", "birthDateWithAge", "parent"]
        .forEach(function (item) {
            let object = $('#' + item);
            console.log(object.text());
            object.html(ajaxPrepare(item, object.text()));

        });
});
