
    function fillCargo(tbodyId, cargo) {
        var mytbodyId = "#" + tbodyId;
        $.each(cargo, function (index, value) {
                $(mytbodyId).append(
                    '<tr>' +
                    '<td>' + arrangeCargoTrackingNumber(String(value.tracking_no)) + '</td>' +
                    '<td class="font-weight-bold">' + value.sender_name + '</td>' +
                    '<td class="font-weight-bold">' + value.receiver_name + '</td>' +
                    '<td class="font-weight-bold text-success">' + value.status + '</td>' +
                    '<td class="text-primary">' + value.cargo_type + '</td>' +
                    '<td class="font-weight-bold text-primary">' + value.total_price + '₺' + '</td>' +
                    '<td>' + '<button type="button" class="btn btn-sm btn-primary">Detay</button>' + '</td>' +
                    '</tr>'
                )
            }
        )
    }

    var detailsID;
    $(document).on('click', '.user-detail', function () {
        ToastMessage('warning', '', 'Yükleniyor');
        detailsID = $(this).prop('id');
        getCustomerDetails(detailsID);
    });
    
    $(document).on('dblclick', '.customer-detail', function () {
        detailsID = $(this).prop('id');
        getCustomerDetails(detailsID);
    });


    $(document).on('click', '#deleteCustomer', function () {
        // deleteCustomer(detailsID);
        swal({
            title: "Silme İşlemini Onaylayın!",
            text: "Emin misiniz? Bu işlem geri alınamaz!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if(willDelete) {
                 $.ajax({
                    type:'DELETE',
                    url:'/Customers/Delete/'+detailsID,
                    data:{
                         _token: token,
                    },
                    success:function() {
                        $('#ModalCustomerDetails').modal('hide');
                        oTable.draw();
                        ToastMessage('success', 'Müşteri silindi!', 'İşlem başarılı!');
                    }, 
                    error:function (jqXHR, response) {
                        if( jqXHR.status == 403) {
                            ToastMessage('error', JSON.parse(jqXHR.responseText).message, 'İşlem başarısız');
                        }
                        ajaxError(jqXHR.status);
                    }
                 }).always(function () {
                    $('#ModalBodyCustomerDetails').unblock();
                }); 
                   
            }


        }); 
    });
    var array = new Array();

    function getCustomerDetails(user) {


        $('#ModalBodyCustomerDetails').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-grid-pulse">\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            
        $.ajax('/Customers/GetCustomerInfo', {
            method: 'POST',
            data: {
                _token: token,
                user: user
            },
            cache: false
        }).done(function (response) {

            $('#ModalBodyCustomerDetails').unblock();

            var current = response.data[0];
            var category = current.category;
            var current_type = current.current_type;
            let addressNote = adresMaker(current.city, current.district, current.neighborhood, current.street, current.street2, current.building_no, current.door_no, current.floor, current.address_note);
            let branch_office = current.agencies_city + " / " + current.agencies_district + " / " + current.agency_name + " Acente";
            let cargo = response.cargo;

            if(current.created_at > 86400) $('#deleteButton').css("display", "none");
            else $('#deleteButton').css("display", "block");

            if (current_type == 'Gönderici' && category == 'Bireysel') {
                fillCargo('tbodyUserTopTenSenderPersonal', cargo);

                $('#ModalCustomerDetails').modal();

                // Tables display are changed here
                $('.taker-table-display').css("display","none");
                $('.sender-customer-display').css("display","none");
                $('.sender-personal-display').css("display","block");

                $('#senderPersonalCustomerType-1').html(category);
                $('#senderPersonalCustomerTckn').html(current.tckn)
                $('#senderPersonalNameSurname').html(current.name);
                $('#senderPersonalCustomerEmail').html(current.email);

                $('#senderPersonalCustomerCity').html(current.city);
                $('#senderPersonalCustomerDistrict').html(current.district);
                $('#senderPersonalCustomerNeighborhood').html(current.neighborhood);

                $('#senderPersonalCustomerPhone').html(current.phone);
                $('#senderPersonalCustomerGsm').html(current.gsm);
                $('#senderPersonalCustomerAdress').html(addressNote);

                $('#senderPersonalCustomerVkn').html(current.vkn);

                $('#customerName').html(current.name);
                $('#customerType').html(current_type);

            } else if (current_type == 'Gönderici' && category == 'Kurumsal') {
                console.log(current);

                fillCargo('tbodyUserTopTenSenderCorporate', cargo);

                $('#ModalCustomerDetails').modal();

                // Tables display are changed here
                $('.taker-table-display').css("display","none");
                $('.sender-customer-display').css("display","block");
                $('.sender-personal-display').css("display","none");

                $('#senderCustomerType-1').html(category);

                $('#senderCustomerTckn').html(current.tckn)
                $('#senderCustomerNameSurname').html(current.name);
                $('#senderCustomerEmail').html(current.email);

                $('#senderCustomerCity').html(current.city);
                $('#senderCustomerDistrict').html(current.district);
                $('#senderCustomerNeighborhood').html(current.neighborhood);
                $('#senderCustomerStreet').html(current.street);
                $('#senderCustomerStreet2').html(current.street2);
                $('#senderCustomerBuildingNo').html(current.building_no);
                $('#senderCustomerFloor').html(current.floor);
                $('#senderCustomerDaireNo').html(current.door_no);
                $('#senderCustomerAdressNote').html(current.address_note);
                $('#senderCustomerAdress').html(addressNote);


                $('#senderCustomerPhone').html(current.phone);
                $('#senderCustomer-phone-2').html(current.phone2);
                $('#senderCustomerGsm').html(current.gsm);
                $('#senderCustomerGsm-2').html(current.gsm2);
                $('#senderCustomerWebSite').html(current.web_site);

                $('#senderCustomerDispatchCity').html(current.dispatch_city);
                $('#senderCustomerDispatchDistrict').html(current.dispatch_district);
                $('#senderCustomerDispatchAdress').html(current.dispatch_adress);
                $('#senderCustomerTaxAdmin').html(current.tax_administration);
                $('#senderCustomerDispatchPastCode').html(current.dispatch_post_code);
                $('#senderCustomerBranchOffice').html(branch_office);

                $('#senderCustomerDiscount').html(current.discount);
                $('#senderCustomerContractEnd').html(current.contract_end_date);
                $('#senderCustomerCotractStart').html(current.contract_start_date);
                $('#senderCustomerRefference').html(current.reference);


                $('#senderCustomerVkn').html(current.vkn);

                $('#customerName').html(current.name);
                $('#customerType').html(current_type);

            } else if (current_type == 'Alıcı') {

                fillCargo('tbodyUserTopTen', cargo);

                // Tables display are changed here
                $('.taker-table-display').css("display","block");
                $('.sender-customer-display').css("display","none");
                $('.sender-personal-display').css("display","none");

                $('#ModalCustomerDetails').modal();
                $('#tcknTaker').html(current.tckn)
                $('#nameSurnameTaker').html(current.name);
                $('#emailTaker').html(current.email);

                $('#cityTaker').html(current.city);
                $('#districtTaker').html(current.district);
                $('#neighborhoodTaker').html(current.neighborhood);

                $('#phoneTaker').html(current.phone);
                $('#phoneGsm').html(current.gsm);
                $('#adressTaker').html(addressNote);

                $('#vknTaker').html(current.vkn);

                $('#customerName').html(current.name);
                $('#customerType').html(current_type);
            }


        }).error(function (jqXHR, response) {
            ajaxError(jqXHR.status);
        }).always(function () {
            $('#ModalBodyCustomerDetails').unblock();
        });

    }
    

    $(document).on('click', '.properties-log', function () {
        var properties = $(this).attr('properties');
        var log_id = $(this).attr('id');
        var array_no = $(this).attr('array-no');
        $('#json-renderer').text(JSON.parse(array[array_no]));
        $('#json-renderer').jsonViewer(JSON.parse(array[array_no]), {
            collapsed: false,
            rootCollapsable: false,
            withQuotes: false,
            withLinks: true
        });
        $('#ModalLogProperties').modal();
    });

