import 'jszip';
import 'pdfmake';
import 'datatables.net-bs';
// import 'datatables.net-autofill-bs';
import 'datatables.net-buttons-bs';
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';
import 'datatables.net-scroller-bs';
// import 'datatables.net-searchbuilder-bs';
// import 'datatables.net-select-bs';
// import 'datatables.net-colreorder-bs';
// import 'datatables.net-datetime';
// import 'datatables.net-fixedcolumns-bs';
// import 'datatables.net-fixedheader-bs';
import 'datatables.net-responsive-bs';

$.extend($.fn.dataTable.defaults, {
    language: { url: "https://cdn.datatables.net/plug-ins/1.11.0/i18n/cs.json" },
    responsive: true,
    dom: 'Bfrtipl'
});