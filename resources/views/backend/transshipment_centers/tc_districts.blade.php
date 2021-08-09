@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Aktarmalara Bağlı İl-İlçe Ayarı')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#000" class="fa fa-truck con-gradient">
                        </i>
                    </div>
                    <div>Aktarmalara Bağlı İl-İlçe Ayarı
                        <div class="page-title-subheading">Bu modül üzerinden aktarmalara bağlı acenteleri
                            düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('rd.Index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Bölgelere Dön
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            {{-- MY BITCCH --}}
            <div class="mb-3 card">
                <div class="card-header">
                    <ul class="nav nav-justified">

                        <li class="nav-item">
                            <a data-toggle="tab" href="#role_module" class="nav-link active">Aktarmalara Bağlı Acenteler
                                Ayarı</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active min-vh-100" id="role_module" role="tabpanel">
                            <div class="main-card mb-3 card">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h5 class="card-title">TRANSFER MERKEZİ SEÇİN</h5>
                                                <select id="select-tc" class="selectpicker form-control">
                                                    <option value="">Tümü</option>
                                                    @foreach ($data['transshipment_centers'] as $tc)
                                                        <option {{ $id == $tc->id ? 'selected' : '' }}
                                                                role="{{ $tc->id }}" value="{{ $tc->id }}">
                                                            {{ $tc->tc_name . ' TRANSFER MERKEZİ (' . $tc->city . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body text-center">
                                                <a href="javascript:void(0)">
                                                    <button id="BtnGiveDistrictToRegion"
                                                            class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-light">
                                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Aktarmaya
                                                        Bağlanacak İlçeleri Seçin
                                                    </button>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <table id="TableRolePermissions"
                                           style="white-space: nowrap; "
                                           class="table table-stripted TableNoPadding NikolasDataTable table-hover">
                                        <thead>
                                        <tr>
                                            <th>Transfer Merkezi</th>
                                            <th>İl</th>
                                            <th>İlçe</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Transfer Merkezi</th>
                                            <th>İl</th>
                                            <th>İlçe</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('js')
    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>

    <script type="text/javascript">
        var city_id, region_id;
        var oTable;

        $(document).ready(function () {

            $('#BtnGiveDistrictToRegion').click(function () {

                if ($('#select-tc').val() == '' || $('#select-tc').val() == '-1') {
                    ToastMessage('error', 'Önce Tekil Aktarma Seçin!', 'Hata!');
                } else {
                    $('#ModalCityDistrictsTitle').html($('#select-tc option:selected').text());
                    $('#TbodyRegionalDistricts').html('');
                    $('#city').val('');
                    $('#ModalCityDistricts').modal();
                }
            });

            $('#city').change(function () {

                city_id = $(this).children(":selected").attr("id");
                tc_id = $('#select-region').val();

                $('#modalBodyTCDistricts.modal-body').block({
                    message: $('<div class="loader mx-auto">\n' +
                        '                            <div class="ball-pulse-sync">\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                            </div>\n' +
                        '                        </div>')
                });
                $('.blockUI.blockMsg.blockElement').css('border', '0px');
                $('.blockUI.blockMsg.blockElement').css('background-color', '');

                $.post('{{ route('TransshipmentCenters.getDistricts') }}', {
                    _token: token,
                    city_id: city_id,
                    tc_id: $('#select-tc').val()
                }, function (response) {

                    $('#TbodyRegionalDistricts').html('');
                    $('#district').append(
                        '<option  value="">İlçe Seçin</option>'
                    );

                    $.each(response, function (key, value) {
                        $('#TbodyRegionalDistricts').append(
                            '<tr>' +

                            '<td width="10"> <input style="width: 20px" value="' + (
                                value['district_id']) +
                            '" type="checkbox" ' +
                            (value['count'] != '0' ? 'checked disabled' : '') +
                            ' class="form-control check-give-district-to-region  ck-' +
                            (
                                value['district_id']) +
                            '">' + '</td>' +

                            '<td>' + ($('#city option:selected').text()) + '</td>' +
                            '<td>' + (value['district_name']) + '</td>' +

                            '</tr>'
                        );
                    });
                    $('.select-all-cb').prop('checked', false);

                    $('#modalBodyTCDistricts.modal-body').unblock();

                });
            });

            // and The Last Part: NikoStyle
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
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
                    "select": {
                        "rows": {
                            "_": "%d kayıt seçildi",
                            "0": "",
                            "1": "1 kayıt seçildi"
                        }
                    }
                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                buttons: [
                    'copy',
                    'pdf',
                    'csv',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: "CK- Transfer Merkezlerine Bağlı Acenteler - {{date('d/m/Y H:i')}}"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                responsive: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('TransshipmentCenters.ListTCDistricts') !!}',
                    data: function (d) {
                        d.tc_id = $('#select-tc').val();
                    }
                },
                columns: [
                    {data: 'tc_name', name: 'tc_name'},
                    {data: 'city', name: 'city'},
                    {data: 'district', name: 'district'},
                    {data: 'delete', name: 'delete'}
                ],
                scrollY: false
            });

        });

        $('#select-tc').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });

    </script>
@endsection


@section('modals')
    <!-- Large modal -->
    <div class="modal fade" id="ModalCityDistricts" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalCityDistrictsTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyTCDistricts" class="modal-body">
                    <p class="mb-0">Aktarmaya bağlanacak il ve ilçeleri aşağıdan seçebilirsiniz.</p>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <select name="city" id="city" required class="form-control">
                                    <option value="">İl Seçiniz</option>
                                    @foreach ($data['cities'] as $city)
                                        <option {{ old('city') == $city->id ? 'selected' : '' }} id="{{ $city->id }}"
                                                value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <table style="margin-bottom:  0;" class="table table-striped">
                        <thead>
                        <th style="width: 50px;padding:0">
                            <input style="width: 20px;margin-left: 7px;" type="checkbox"
                                   class="select-all-cb form-control">
                        </th>
                        <th>il</th>
                        <th>ilçe</th>
                        </thead>
                    </table>
                    <div style="max-height: 50vh; overflow-y: scroll;">
                        <table id="TableRegionalDistricts" class="table table-striped">
                            <tbody id="TbodyRegionalDistricts"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">İptal Et</button>
                    <button type="button" class="btn btn-primary" id="SaveBtn" data-dismiss="modal">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".select-all-cb").click(function () {
            $('input:checkbox:not(:disabled)').prop('checked', this.checked);
        });


        $('#SaveBtn').click(function () {
            var DistrictArray = [];
            $("input.check-give-district-to-region[type=checkbox]:checked:not(:disabled)").each(function () {
                DistrictArray.push($(this).val());
            });


            $.post('{{ route('TransshipmentCenters.addTcDistrict') }}', {
                _token: token,
                city_id: city_id,
                tc_id: $('#select-tc').val(),
                district_array: DistrictArray
            }, function (response) {

                if (response == '1') {
                    ToastMessage('success', 'İlçeler Bölgeye Bağlandı!', 'İşlem Başarılı!');
                } else {
                    ToastMessage('error', 'İşlem Başarısız, Lütfen daha sonra tekrar deneyin!', 'Hata!');
                }

                $('#TbodyRegionalDistricts').html('');
                $('.select-all-cb').prop('checked', false);

            });

        });
    </script>
@endsection
