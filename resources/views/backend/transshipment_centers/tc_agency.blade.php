@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Aktarmalara Bağlı Acenteler Ayarı')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#000" class="fa fa-truck con-gradient">
                        </i>
                    </div>
                    <div>Aktarmalara Bağlı Acenteler Ayarı
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
                                                        Bağlanacak Acenteleri Seçin
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
                                            <th>Acente</th>
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
                                            <th>Acente</th>
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


    <script type="text/javascript">
        var city_id, region_id;
        var oTable;

        function getAgencies(directive = 'all') {
            $('#TbodyAgencies').html('');
            $('input:checkbox:not(:disabled)').prop('checked', false);

            $.ajax({
                type: "POST",
                url: '{{route('TransshipmentCenters.getAgencies')}}/' + directive,
                data: {
                    _token: token
                }
            }).done(function (data) {

                if (data.length == 0) {
                    $('#TbodyAgencies').append(
                        '<tr>' +
                        '<td colspan="4" class="text-center"> <b class="text-primary">Acente Yok</b><td>' +
                        '</tr>'
                    );
                }

                $.each(data, function (key, val) {
                    $('#TbodyAgencies').append(
                        '<tr>' +
                        '<td width="10"> <input style="width: 20px" value="' + (
                            val['key']) +
                        '" type="checkbox" ' +
                        (val['tc_code'] != null ? 'checked disabled' : '') +
                        ' class="form-control check-give-agency-to-tc  ck-' + (val['tc_code']) +
                        '">' + '</td>' +

                        '<td>' + (val['city']) + '<td>' +
                        '<td>' + (val['district']) + '<td>' +
                        '<td>' + (val['agency_name']) + '<td>' +
                        '</tr>'
                    );
                });

            }).fail(function () {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            });
        }

        $(document).ready(function () {

            $('#BtnGiveDistrictToRegion').click(function () {

                if ($('#select-tc').val() == '' || $('#select-tc').val() == '-1') {
                    ToastMessage('error', 'Önce Tekil Aktarma Seçin!', 'Hata!');
                } else {
                    getAgencies();
                    $('#ModalCityDistrictsTitle').html($('#select-tc option:selected').text());
                    $('#city').val('');
                    $('#ModalCityDistricts').modal();
                }
            });

            $('#city').change(function () {
                getAgencies($(this).val());
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
                    url: '{!! route('TransshipmentCenters.ListTCAgency') !!}',
                    data: function (d) {
                        d.tc_id = $('#select-tc').val();
                    }
                },
                columns: [
                    {data: 'tc_name', name: 'tc_name'},
                    {data: 'city', name: 'city'},
                    {data: 'district', name: 'district'},
                    {data: 'agency_name', name: 'agency_name'},
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
                <div class="modal-body">
                    <p class="mb-0">Transfer merkezine bağlanacak acenteleri aşağıdan seçebilirsiniz.</p>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <select name="city" id="city" required class="form-control">
                                    <option value="all">Tüm Acenteler</option>
                                    @foreach ($data['cities'] as $city)
                                        <option id="{{ $city->id }}"
                                                value="{{ $city->city_name }}">{{ $city->city_name }}</option>
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
                        <th width="180">Acente İsmi</th>
                        </thead>
                    </table>
                    <div style="max-height: 50vh; overflow-y: scroll;">
                        <table id="TableRegionalDistricts" class="table table-striped">
                            <tbody id="TbodyAgencies"></tbody>
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
            var AgencyArray = [];
            $("input.check-give-agency-to-tc[type=checkbox]:checked:not(:disabled)").each(function () {
                AgencyArray.push($(this).val());
            });

            $.post('{{ route('TransshipmentCenters.giveAgency') }}', {
                _token: token,
                tc_id: $('#select-tc').val(),
                agency_array: AgencyArray
            }, function (response) {

                if (response.status == '1') {
                    ToastMessage('success', 'Acenteler aktarmaya bağlandı!', 'İşlem Başarılı!');
                } else {
                    ToastMessage('error', 'İşlem Başarısız, Lütfen daha sonra tekrar deneyin!', 'Hata!');
                }

                $('#TbodyRegionalDistricts').html('');
                $('.select-all-cb').prop('checked', false);

            });

        });

    </script>
@endsection
