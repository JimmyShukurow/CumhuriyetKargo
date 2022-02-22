
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <script src="/backend/assets/scripts/city-districts-point.js"></script>

    <script>
        $(document).on('change', '#receiverCity', function () {
            getDistricts('#receiverCity', '#receiverDistrict');
        });

        $(document).on('change', '#senderCity', function () {
            getDistricts('#senderCity', '#senderDistrict');
        });
    </script>


    <script>
        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('#agency').select2();
            $('#creatorUser').select2();

            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    [0, 'desc']
                ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
                    "sSearch": "",
                    "sZeroRecords": "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "Önceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                    },

                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',

                buttons: [
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]
                        },
                        title: "CK - Aktarma Araçları"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Sütun Görünüm'
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('agency.transfer.car.all') !!}',
                    data: function (d) {
                        d.marka = $('#filter_marka').val();
                        d.model = $('#filter_model').val();
                        d.plaka = $('#filter_plaka').val();
                        d.hat = $('#filter_hat').val();
                        d.aracKapasitesi = $('#filter_arac_kapasitesi').val();
                        d.cikisAktarma = $('#filter_cikisAktarma').val();
                        d.varisAktarma = $('#filter_varisAktarma').val();
                        d.soforIletisim = $('#filter_soforIletisim').val();
                    },
                    error: function (xhr, error, code) {

                        if (xhr.status == 429) {
                            ToastMessage('error', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        } else if (xhr.status == 509) {
                            ToastMessage('error', 'Tarih aralığı en fazla 60 gün olabilir!', 'Hata');
                        }
                    },
                    complete: function () {
                        ToastMessage('info', 'Tamamlandı!', 'Bilgi');
                    }
                },
                columns: [
                    {data: 'marka', name: 'marka'},
                    {data: 'model', name: 'model'},
                    {data: 'plaka', name: 'plaka'},
                    {data: 'hat', name: 'hat'},
                    {data: 'arac_kapasitesi', name: 'arac_kapasitesi'},
                    {data: 'cikis_aktarma', name: 'cikis_aktarma'},
                    {data: 'varis_aktarma', name: 'varis_aktarma'},
                    {data: 'aylik_kira_bedeli', name: 'aylik_kira_bedeli'},
                    {data: 'kdv_haric_hakedis', name: 'kdv_haric_hakedis'},
                    {data: 'bir_sefer_kira_maliyeti', name: 'bir_sefer_kira_maliyeti'},
                    // {data: 'name_surname', name: 'name_surname'},
                    {data: 'edit', name: 'edit'},
                ],
            });
        });

        function drawDT() {
            oTable.draw();
        }

        $('.niko-select-filter').change(delay(function (e) {
            drawDT();
        }, 1000));

        $('.niko-filter').keyup(delay(function (e) {
            drawDT();
        }, 1000));

        $('#btnClearFilter').click(function () {
            $('#search-form').trigger("reset");
            $('#select2-creatorUser-container').text('Seçiniz');
            $('#select2-agency-container').text('Seçiniz');
            drawDT();
        });


        $(document).on('click', '.btn_car_details', function () {
            detailsID = $(this).prop('id');
            carInfo($(this).prop('id'));
        });

        var array = new Array();

        function carInfo(id) {

            $('#ModalCarDetails').modal();

            $('#ModalBodyUserDetail.modal-body').block({
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

            $.ajax('{{route('getTransferCars')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    carID: id
                },
                cache: false
            }).done(function (response) {

                let cars = response.cars;

                $('#tdPlaka').html(cars.plaka);
                $('#marka').html(cars.marka);
                $('#model').html(cars.model);
                $('#modelYili').html(cars.model_yili);
                $('#aracKapasitesi').html(cars.arac_kapasitesi);
                $('#tonaj').html(cars.tonaj);
                $('#aracTakipSistemi').html(cars.arac_takip_sistemi);
                $('#hat').html(cars.hat);
                $('#cikisAktarma').html(cars.cikis_akt + " T.M.");
                $('#varisAktarma').html(cars.varis_akt + " T.M.");
                $('#ugradigiAktarmalar').html(response.aktarmalar);
                $('#muayeneBaslangicBitisTarihi').html(cars.muayene_baslangic_tarihi + " - " + cars.muayene_bitis_tarihi);
                $('#muayeneBitimiKalanGun').html(cars.muayene_kalan_sure);
                $('#sigortaBaslangicBitisTarihi').html(cars.trafik_sigortasi_baslangic_tarihi + " - " + cars.trafik_sigortasi_bitis_tarihi);
                $('#sigortaBitimiKalanGun').html(cars.sigorta_kalan_sure);
                $('#kayitTarihi').html(cars.created_at);

                $('#aylikKiraBedeli').html("₺" + getDotter(cars.aylik_kira_bedeli));
                $('#yakitOrani').html("%" + cars.yakit_orani);
                $('#turKm').html(getDotter(cars.tur_km));
                $('#seferKM').html(cars.sefer_km);
                $('#aylikYakit').html("₺" + getDotter(cars.aylik_yakit));
                $('#kdvHaricHakedis').html("₺" + getDotter(cars.kdv_haric_hakedis));
                $('#birSeferKiraMaliyeti').html("₺" + getDotter(cars.bir_sefer_kira_maliyeti));
                $('#birSeferYakitMaliyeti').html("₺" + getDotter(cars.bir_sefer_yakit_maliyeti));
                $('#hakedisArtiMazot').html("₺" + getDotter(cars.hakedis_arti_mazot));
                $('#seferMaliyeti').html("₺" + getDotter(cars.sefer_maliyeti));

                $('#soforAdi').html(cars.sofor_ad);
                $('#soforIletisim').html(cars.sofor_telefon);
                $('#soforAders').html(cars.sofor_adres);
                $('#aracSahibiAdi').html(cars.arac_sahibi_ad);
                $('#aracSahibiIletisim').html(cars.arac_sahibi_telefon);
                $('#aracSahibiAders').html(cars.arac_sahibi_adres);
                $('#aracSahibiYakiniAdi').html(cars.arac_sahibi_yakini_ad);
                $('#aracSahibiYakiniIletisim').html(cars.arac_sahibi_yakini_telefon);
                $('#aracSahibiYakiniAders').html(cars.arac_sahibi_yakini_adres);

                $('#stepne').html(cars.stepne);
                $('#kriko').html(cars.kriko);
                $('#Zincir').html(cars.zincir);
                $('#bijonAnahtari').html(cars.bijon_anahtari);
                $('#reflektor').html(cars.reflektor);
                $('#yanginTupu').html(cars.yangin_tupu);
                $('#ilkYardimCantasi').html(cars.ilk_yardim_cantasi);
                $('#seyyarLamba').html(cars.seyyar_lamba);
                $('#cekmeHalati').html(cars.cekme_halati);
                $('#giydirme').html(cars.giydirme);
                $('#korNoktaUyarisi').html(cars.kor_nokta_uyarisi);
                $('#hataBildirimHatti').html(cars.hata_bildirim_hatti);

                $('#muayeneEvragi').html(cars.sigorta_belgesi);
                $('#sigortaBelgesi').html(cars.sigorta_belgesi);
                $('#soforEhliyet').html(cars.sofor_ehliyet);
                $('#srcBelgesi').html(cars.src_belgesi);
                $('#ruhsatEkspertizRaporu').html(cars.ruhsat_ekspertiz_raporu);
                $('#tasimaBelgesi').html(cars.tasima_belgesi);
                $('#soforAdliSicilKaydi').html(cars.sofor_adli_sicil_kaydi);
                $('#aracSahibiSicilKaydi').html(cars.arac_sahibi_sicil_kaydi);
                $('#soforYakiniIkametgahBelgesi').html(cars.sofor_yakini_ikametgah_belgesi);

                $('.modal-body').unblock();
                return false;
            });

            $('#ModalAgencyDetail').modal();
        }

        $(document).on('click', '#btnConfirmCurrent', function () {
            $('#btnConfirmCurrent').prop('disabled', true);

            $.ajax('/SenderCurrents/AjaxTransaction/ConfirmCurrent', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                if (response.status == -1)
                    ToastMessage('error', response.message, '');
                else if (response.status == 1) {
                    ToastMessage('success', 'İşlem başarılı, cari hesabı onaylandı!', 'İşlem Başarılı!');
                    userInfo(detailsID);
                    $('#divConfirmCurrent').hide();
                }

                $('.modalEnabledDisabled.modal-body').unblock();
            }).error(function (jqXHR, response) {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            }).always(function () {
                $('#btnConfirmCurrent').prop('disabled', false);
            });

        });
        $(document).on('click', '#btnPrintModal', function () {
            printWindow('#ModalBodyUserDetail', "CKG-Sis - Aktarma Araçları");
        });

    </script>