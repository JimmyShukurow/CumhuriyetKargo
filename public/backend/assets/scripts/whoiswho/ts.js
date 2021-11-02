$(document).ready(function() {
    $('#example').DataTable( {
        "ajax": "http://127.0.0.1:8000/WhoIsWho/GetTransshipmentCenters"
    } );
} );