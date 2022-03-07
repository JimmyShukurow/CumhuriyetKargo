@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Departmanlara Bağlı Roller Ayarı')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-briefcase icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Departmanlara Bağlı Roller Ayarı
                        <div class="page-title-subheading">Bu modül üzerinden departmanlara bağlı yetkileri
                            düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('Departments.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Departmanlara Dön
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
                            <a data-toggle="tab" href="#role_module" class="nav-link active">Departmanlara Bağlı
                                Yetkiler
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
                                                <h5 class="card-title">DEPARTMAN SEÇİN</h5>
                                                <select id="select-department" class="selectpicker form-control">
                                                    <option value="">Tümü</option>
                                                    @foreach($data['departments'] as $department)
                                                        <option
                                                            value="{{$department->id}}">{{$department->department_name}}</option>
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
                                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Departmana
                                                        Bağlanacak Yetkileri Seçin
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
                                            <th>Departman</th>
                                            <th>Yetki</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Departman</th>
                                            <th>Yetki</th>
                                            <th>Kayıt Tarihi</th>
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

        function getRoles(directive = 'all') {
            $('#TbodyRoles').html('');

            $('input:checkbox:not(:disabled)').prop('checked', false);
            $('input:checkbox:not(:disabled)').prop('checked', false);

            $.ajax({
                type: "POST",
                url: '{{route('Departments.getRoles')}}/' + directive,
                data: {
                    _token: token
                }
            }).done(function (data) {

                if (data.length == 0) {
                    $('#TbodyRoles').append(
                        '<tr>' +
                        '<td colspan="3" class="text-center"> <b class="text-primary">Rol Yok</b><td>' +
                        '</tr>'
                    );
                }

                $.each(data, function (key, val) {
                    $('#TbodyRoles').append(
                        '<tr>' +
                        '<td width="10"> <input style="width: 20px" value="' + (
                            val['key']) +
                        '" type="checkbox" ' +
                        (parseInt(val['gived']) > 0 ? 'checked disabled' : '') +
                        ' class="form-control check-give-role-to-department  ck-' + (val['key']) +
                        '">' + '</td>' +
                        '<td>' + (val['display_name']) + '<td>' +
                        '<td>' + (val['description']) + '<td>' +
                        '</tr>'
                    );
                });

            }).fail(function () {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            });
        }

        $(document).ready(function () {

            $('#BtnGiveDistrictToRegion').click(function () {

                if ($('#select-department').val() == '' || $('#select-department').val() == '-1') {
                    ToastMessage('error', 'Önce Tekil Departman Seçin!', 'Hata!');
                } else {
                    getRoles($('#select-department').val());
                    $('#ModalCityDistrictsTitle').html($('#select-department option:selected').text());
                    $('#city').val('');
                    $('#ModalCityDistricts').modal();
                }
            });

            $('#city').change(function () {
                getRoles($(this).val());
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
                        title: "CK- Departmanlara Bağlı Yetkiler - {{date('d/m/Y H:i')}}"
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
                    url: '{!! route('Departments.ListRoleDepartments') !!}',
                    data: function (d) {
                        d.tc_id = $('#select-department').val();
                    }
                },
                columns: [
                    {data: 'department_name', name: 'department_name'},
                    {data: 'display_name', name: 'display_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'delete', name: 'delete'}
                ],
                scrollY: false
            });

        });

        $('#select-department').on('change', function (e) {
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
                    <p class="mb-0">Departmana bağlanacak yetkileri aşağıdan seçebilirsiniz.</p>
                    <br>

                    <table style="margin-bottom:  0;" class="table table-striped">
                        <thead>
                        <th style="width: 50px;padding:0">
                            <input style="width: 20px;margin-left: 7px;" type="checkbox"
                                   class="select-all-cb form-control">
                        </th>
                        <th>Yetki</th>
                        <th>Açıklama</th>
                        </thead>
                    </table>
                    <div style="max-height: 50vh; overflow-y: scroll;">
                        <table id="TableRegionalDistricts" class="table table-striped">
                            <tbody id="TbodyRoles"></tbody>
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
            $("input.check-give-role-to-department[type=checkbox]:checked:not(:disabled)").each(function () {
                AgencyArray.push($(this).val());
            });

            $.post('{{ route('Departments.giveRole') }}', {
                _token: token,
                department: $('#select-department').val(),
                roles_array: AgencyArray
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
