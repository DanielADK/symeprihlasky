require( 'jquery' );
require( 'jszip' );
require( 'pdfmake' );
require( 'datatables.net-bs' )();
require( 'datatables.net-autofill-bs' )();
require( 'datatables.net-buttons-bs' )();
require( 'datatables.net-buttons/js/buttons.html5.js' )();
require( 'datatables.net-buttons/js/buttons.print.js' )();
require( 'datatables.net-scroller-bs' )();
require( 'datatables.net-searchbuilder-bs' )();
require( 'datatables.net-select-bs' )();
require( 'datatables.net-colreorder-bs' )();
require( 'datatables.net-datetime' )();
require( 'datatables.net-fixedcolumns-bs' )();
require( 'datatables.net-fixedheader-bs' )();
require( 'datatables.net-responsive-bs' )();

var $       = require( 'jquery' );
var dt      = require( 'datatables.net' )( window, $ );
var buttons = require( 'datatables.net-buttons' )( window, $ );