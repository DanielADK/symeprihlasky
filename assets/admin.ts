export function customPopUpMin(object: any): void {
    window.scrollTo(0,0);
    object.fadeTo(5000,500).slideUp(1000, function () {
        object.slideUp(1000);
    });
}

/**
 * It takes a number and returns a string with a thousand separator
 * @param x - The number to be formatted.
 * @returns the number with a thousand separator.
 */
function numberToThousandsSep(x: number) : string {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
function twoDigitDate(date: string): string { return ("0" + date).slice(-2); }

// const months = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
// const days = ["Po", "Út", "St", "Čt", "Pá", "So", "Ne"];

/**
 * It takes a type of data and the data itself and returns a string with the data formatted according to the type
 * @param typ - type of data
 * @param data - the data to be displayed in the cell
 * @returns It is a function that returns a string.
 */
export function ajaxPrepare(typ: string, data: any): string {
    let date, age;
    switch (typ) {
        case "address":
            return '<a target="_blank" href="https://mapy.cz/zakladni?q=' +
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
        case "yesno":
        case "member":
            return (data || data === '1') ?
                '<i class=\"fa fa-check-circle fa-lg text-success\" aria-hidden=\"true\"></i> Ano' :
                '<i class=\"fa fa-times-circle fa-lg text-danger\" aria-hidden=\"true\"></i> Ne';
        case "check":
            return (data || data === '1') ?
                '<i class=\"fa fa-check-circle fa-lg text-success\" aria-hidden=\"true\"></i>' :
                '<i class=\"fa fa-times-circle fa-lg text-danger\" aria-hidden=\"true\"></i>';
        case "sex":
            return data.sex === "Z" ?
                'Dívka <i class=\"fa fa-female fa-lg text-red\" aria-hidden=\"true\"></i>' :
                'Chlapec <i class=\"fa fa-male fa-lg text-blue\" aria-hidden=\"true\"></i>';
        case "sexAdult":
            return data.sex === "Z" ?
                'Žena <i class=\"fa fa-female fa-lg text-red\" aria-hidden=\"true\"></i>' :
                'Muž <i class=\"fa fa-male fa-lg text-blue\" aria-hidden=\"true\"></i>';
        case "type":
        case "typeOfEvent":
            switch (data) {
                case "JA":
                    return '<h4><span class="label label-primary"><i class="fa fa-map-o"></i> Jednodenní</span></h4>';
                case "VA":
                    return '<h4><span class="label label-danger"><i class="fa fa-map-signs"></i> Vícedenní</span></h4>';
                case "LT":
                    return '<h4><span class="label label-success"><i class="fa fa-fire"></i> Letní tábor</span></h4>';
                case "PV":
                    return '<h4><span class="label label-warning"><i class="fa fa-beer"></i> Dospělácká</span></h4>';
                default:
                    return 'Neznámé';
            }
        case "activeApplication":
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
        case "simpleDate":
            date = new Date(data);
            return twoDigitDate(date.getDate()) + "." + twoDigitDate(date.getMonth()+1) + "." + date.getFullYear();
        case "birthDateWithAge":
            date = new Date(data);
            age = new Date(Date.now() - date.getTime()).getUTCFullYear()-1970;
            return twoDigitDate(date.getDate()) + "." + twoDigitDate(date.getMonth()+1)  + "." + date.getFullYear()
                + " (" + age + " let)";
        case "age":
            let start = new Date(data[0]);
            let end = new Date(data[1]);
            age = new Date(end.getTime() - start.getTime()).getUTCFullYear()-1970;
            return age;
        case "signDateTime":
            date = new Date(data);
            return twoDigitDate(date.getDate()) + "." + twoDigitDate(date.getMonth()+1)  + "." + date.getFullYear()
                + " " + twoDigitDate(date.getHours()) + ":" + twoDigitDate(date.getMinutes())  + ":" + twoDigitDate(date.getSeconds());
        case "email":
            return '<a href="mailto:' + data + '">' +
                data +
                '</a>';
        case "parent":
            return '<a href="/admin/rodic/zobrazit/' + data.id + '">' +
                data.name + ' ' + data.surname +
                '</a>';
        case "phone":
            if (data === null)
                return '<span class=\"label label-danger\">NEVYPLNĚNO!</span>';
            else
                return '<a href="tel:' + data + '">' +
                        data.match(/.{3}/g).join(' ') +
                        '</a>';

        case 'fullname':
            return data.name + ' ' + data.surname;
        case 'capacity':
            return data == -1 ? "Neomezená" : data;
        case 'fullEvent':
            return data.shortName + ',<br>' + data.fullName;
        case 'priceOther':
            if (typeof data === "undefined" || data == -1) {
                return "-";
            } else {
                return numberToThousandsSep(data) + " Kč";
            }
        case 'priceMember':
            if (typeof data === "undefined" || data == "-1") {
                return "-";
            } else {
                return numberToThousandsSep(data) + " Kč";
            }
        case 'role':
            let retval = "";
            if (typeof data !== 'object') {
                data = JSON.parse(data);
            }
            data.forEach(function (role) {
                switch (role) {
                    case "ROLE_ADMIN":
                        retval += ' <span class="label label-danger">Admin</span>';
                        break;
                    case "ROLE_LEADER":
                        retval += ' <span class="label label-warning">Vedoucí</span>';
                        break;
                    case "ROLE_INSTRUCTOR":
                        retval += ' <span class="label label-success">Instruktor</span>';
                        break;
                    case "ROLE_PARENT":
                        retval += ' <span class="label label-primary">Rodič</span>';
                        break;
                    default:
                        retval += ' <span class="label label-default">' + role + '</span>'
                }
            });
            return retval;
        case 'filled':
            return (data !== "") ? data : "Neznámé";
        default:
            return "Chyba v překladu."
    }
}