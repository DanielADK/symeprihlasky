"use strict";
function customPopUpMin(object) {
    window.scrollTo(0,0);
    object.fadeTo(5000,500).slideUp(1000, function() {
        object.slideUp(1000);
    });
}

function numberToThousandsSep(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
function twoDigitDate(date) { return ("0" + date).slice(-2); }

const months = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
const days = ["Po", "Út", "St", "Čt", "Pá", "So", "Ne"];

function ajaxPrepare(typ, data, type) {
    if (typ === 'address') {
        return '<a target="_blank" href="https://www.google.com/maps?q=' +
            data.street.replace(' ', '+') + '+' +
            data.city.replace(' ', '+') + '+' +
            data.postcode +
            '">' +
            data.street +
            ', <br>' +
            data.city +
            ', <br>' +
            data.postcode +
            '</a>';
    } else if (typ === 'yesno') {
        return data ?
            '<i class=\"fa fa-check-circle fa-lg text-success\" aria-hidden=\"true\"></i> Ano' :
            '<i class=\"fa fa-times-circle fa-lg text-danger\" aria-hidden=\"true\"></i> Ne';
    } else if (typ === 'check') {
        return data ?
            '<i class=\"fa fa-check-circle fa-lg text-success\" aria-hidden=\"true\"></i>' :
            '<i class=\"fa fa-times-circle fa-lg text-danger\" aria-hidden=\"true\"></i>';
    } else if (typ === 'sex') {
        return data.sex === "Z" ?
            'Dívka <i class=\"fa fa-female fa-lg text-red\" aria-hidden=\"true\"></i>' :
            'Chlapec <i class=\"fa fa-male fa-lg text-blue\" aria-hidden=\"true\"></i>';
    } else if (typ === 'type') {
        switch (data) {
            case "JD":
                return '<h4><span class="label label-primary"><i class="fa fa-map-o"></i> Jednodenní</span></h4>';
            case "VA":
                return '<h4><span class="label label-danger"><i class="fa fa-map-signs"></i> Vícedenní</span></h4>';
            case "LT":
                return '<h4><span class="label label-success"><i class="fa fa-fire"></i> Letní tábor</span></h4>';
            case "APV":
                return '<h4><span class="label label-warning"><i class="fa fa-beer"></i> Dospělácká</span></h4>';
            default:
                return 'Neznámé';
        }
    } else if (typ === 'activeApplication') {
        switch (data) {
            case "1":
                return '<h4><span class=\"label label-success\"><i class=\"fa fa-check\"></i> Aktivní</span></h4>';
            case true:
                return '<h4><span class=\"label label-success\"><i class=\"fa fa-check\"></i> Aktivní</span></h4>';
            case "0":
                return '<h4><span class=\"label label-danger\"><i class=\"fa fa-times\"></i> Neaktivní</span></h4>';
            case "":
                return '<h4><span class=\"label label-danger\"><i class=\"fa fa-times\"></i> Neaktivní</span></h4>';
            case false:
                return '<h4><span class=\"label label-danger\"><i class=\"fa fa-times\"></i> Neaktivní</span></h4>';
            default:
                return "Neznámé";
        }
    } else if (typ === 'simpleDate') {
        var date = new Date(data);
        return twoDigitDate(date.getDate()) + "." + twoDigitDate(date.getMonth()+1) + "." + date.getFullYear();
    } else if (typ === 'birthDateWithAge') {
        var date = new Date(data);
        var age = new Date(Date.now() - date.getTime()).getUTCFullYear()-1970;
        return twoDigitDate(date.getDate()) + "." + twoDigitDate(date.getMonth()+1)  + "." + date.getFullYear()
            + " (" + age + " let)";

    } else if (typ === 'signDateTime') {
        var date = new Date(data);
        return twoDigitDate(date.getDate()) + "." + twoDigitDate(date.getMonth()+1)  + "." + date.getFullYear()
            + " " + twoDigitDate(date.getHours()) + "." + twoDigitDate(date.getMinutes())  + "." + twoDigitDate(date.getSeconds());
>>>>>>> Symfony6
    } else if (typ === 'email') {
        return '<a href="mailto:' + data.email + '">' +
            data.email +
            '</a>';

    } else if (typ === 'parent') {
        return '<a href="/admin/rodic/zobrazit/' + data.id + '">' +
            data.name + ' ' + data.surname +
            '</a>';

    } else if (typ === 'phone') {
        if (data.phone === null)
            return '<span class=\"label label-danger\">NEVYPLNĚNO!</span>';
        else
            return '<a href="tel:' + data.phone + '">' +
                    data.phone +
                    '</a>';

    } else if (typ === 'fullname') {
        return data.name + ' ' + data.surname;
    }else if (typ === 'capacity') {
        return data === -1 ? "Neomezená" : data;
    } else if (typ === 'fullEvent') {
        return data.shortName + ',<br>' + data.fullName;
    } else if (typ === 'priceOther') {
        if (typeof data === "undefined" || data == "-1") {
            return "-";
        } else {
            return numberToThousandsSep(data) + " Kč";
        }
    } else if (typ === 'priceMember') {
        if (typeof data === "undefined" || data == "-1") {
            return "-";
        } else {
            return numberToThousandsSep(data) + " Kč";
        }
    } else if (typ === 'role') {
        var retval = "";
        data.forEach(function(role) {
            switch (role) {
                case "ROLE_ADMIN":
                    retval = retval + ' <span class="label label-danger">Admin</span>';
                    break;
                case "ROLE_LEADER":
                    retval = retval + ' <span class="label label-warning">Vedoucí</span>';
                    break;
                case "ROLE_INSTRUCTOR":
                    retval = retval + ' <span class="label label-success">Instruktor</span>';
                    break;
                case "ROLE_PARENT":
                    retval = retval + ' <span class="label label-primary">Rodič</span>';
                    break;
            }
        });
        return retval;
    }
}