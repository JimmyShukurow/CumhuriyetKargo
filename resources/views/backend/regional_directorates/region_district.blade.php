@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'B.M. Bağlı İl İlçe Ayarı')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#ddd" class="fa fa-university con-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>B.M. Bağlı İl İlçe Ayarı
                        <div class="page-title-subheading">Bu modül üzerinden bölge müdürlüklerine bağlı olan il ve
                            ilçeleri
                            düzenleyenilirsiniz.
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
                            <a data-toggle="tab" href="#role_module" class="nav-link active">Bölge
                                Müdürlüğü İl İlçe Ayarı</a>
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
                                                <h5 class="card-title">BÖLGE MÜDÜRLÜĞÜ SEÇİN</h5>
                                                <select id="select-region" class="selectpicker form-control">
                                                    <option value="">Tümü</option>
                                                    @foreach ($data['regional_directorates'] as $region)
                                                        <option {{ $id == $region->id ? 'selected' : '' }}
                                                                role="{{ $region->id }}" value="{{ $region->id }}">
                                                            {{ $region->name . ' BÖLGE MÜDÜRLÜĞÜ' }}
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
                                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Bölgeye
                                                        Bağlanacak
                                                        İlçeleri Seçin
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
                                            <th>Bölge</th>
                                            <th>İl</th>
                                            <th>İlçe</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Bölge</th>
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


    <script type="text/javascript">
        var city_id, region_id;
        var oTable;


        $(document).ready(function () {


            $('#select-region').change(function () {
                // getDistrictsOfRegion();
            })

            $('#BtnGiveDistrictToRegion').click(function () {

                if ($('#select-region').val() == '' || $('#select-region').val() == '-1') {
                    ToastMessage('error', 'Önce Tekil Bölge Seçin!', 'Hata!');
                } else {
                    $('#ModalCityDistrictsTitle').html($('#select-region option:selected').text());
                    $('#city').val('');
                    $('#TbodyRegionalDistricts').html('');
                    $('#ModalCityDistricts').modal();
                }
            });


            $('#city').change(function () {

                city_id = $(this).children(":selected").attr("id");
                region_id = $('#select-region').val();


                $.post('{{ route('rd.RegionalDistricts') }}', {
                    _token: token,
                    city_id: city_id,
                    region_id: region_id
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

                });
            });


            // and The Last Part: NikoStyle
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: dtLengthMenu,
                language: dtLanguage,
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                buttons: [
                    'copy',
                    'pdf',
                    'csv',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2]
                        },
                        title: "CK-Bölge Müdürlükleri - {{date('d/m/Y H:i')}}"
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
                    url: '{!! route('rd.ListRegionalDistricts') !!}',
                    data: function (d) {
                        d.region_id = $('#select-region').val();
                    }
                },
                columns: [
                    // {data: 'district', name: 'district'},
                    {data: 'name', name: 'name'},
                    {data: 'city', name: 'city'},
                    {data: 'district', name: 'district'},
                    {data: 'delete', name: 'delete'}
                ],
                scrollY: false
            });

        });

        $('#select-region').on('change', function (e) {
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
                    <p class="mb-0">Bölgeye bağlanacak il ve ilçeleri aşağıdan seçebilirsiniz.</p>
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


            $.post('{{ route('rd.AddRegDistrict') }}', {
                _token: token,
                city_id: city_id,
                region_id: region_id,
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
