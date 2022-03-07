$('#arrivalBranchType').change(function () {

    if ($(this).val() == 'Acente') {
        $('#divArrivalTc').hide()
        $('#divArrivalAgency').show()
    } else if ($(this).val() == 'Aktarma') {
        $('#divArrivalTc').show()
        $('#divArrivalAgency').hide()
    }
})

$('#plaque').keyup(function () {
    $(this).val($(this).val().toLocaleUpperCase())
})

$('#AddRoutes').click(function () {
    $('#ModalAddRoutes').modal()
})
