@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush

@section('title', 'Modüller')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-diamond icon-gradient bg-warm-flame">
                        </i>
                    </div>
                    <div>Modüller
                        <div class="page-title-subheading">Kullanıcıların erişebileceği modülleri bu modül üzerinden
                            konfigüre edebilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <button type="button" data-toggle="tooltip" title="" data-placement="bottom"
                            class="btn-shadow mr-3 btn btn-dark"
                            data-original-title="Yetki, Modül Grubu, Modül ve Alt Modül Ekeleme İşlemlerini Buradan Yapabilirsiniz.">
                        <i class="fa fa-star"></i>
                    </button>
                    <div class="d-inline-block dropdown">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                class="btn-shadow dropdown-toggle btn btn-info">
                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                            <i class="fa fa-business-time fa-w-20"></i>
                                        </span>
                            İşlemler
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right "
                             x-placement="bottom-end"
                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-129px, 33px, 0px);">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('module.AddRole') }}">
                                        <i class="nav-link-icon lnr-plus-circle"></i>
                                        <span>Yeni Yetki Ekle</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('module.AddModuleGrpup') }}">
                                        <i class="nav-link-icon lnr-plus-circle"></i>
                                        <span>Yeni Modül Grubu Ekle</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('module.AddModule') }}">
                                        <i class="nav-link-icon lnr-plus-circle"></i>
                                        <span>Yeni Modül Ekle</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('module.AddSubModule') }}">
                                        <i class="nav-link-icon lnr-plus-circle"></i>
                                        <span>Yeni Alt Modül Ekle</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="">
            {{-- MY BITCCH --}}
            <div class="mb-3 card">
                <div class="card-header">
                    <ul class="nav nav-justified">
                        <li class="nav-item"><a data-toggle="tab" href="#roles"
                                                class="{{ setActive($tab, 'Roles')}} nav-link">Yetkiler</a>
                        </li>
                        <li class="nav-item"><a data-toggle="tab" href="#module_groups"
                                                class="{{ setActive($tab, 'ModuleGroups')}} nav-link">Modül
                                Grupları</a>
                        </li>
                        <li class="nav-item"><a data-toggle="tab" href="#modules"
                                                class="{{ setActive($tab, 'Modules')}} nav-link">Modüller</a></li>
                        <li class="nav-item"><a data-toggle="tab" href="#sub_modules"
                                                class="{{ setActive($tab, 'SubModules')}} nav-link">Alt Modüller</a>
                        </li>
                        <li class="nav-item"><a data-toggle="tab" href="#role_module" class="nav-link">Yetki Modül
                                İzin</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane {{ setActive($tab, 'Roles')}} min-vh-100" id="roles" role="tabpanel">
                            <div class="main-card mb-3 card">


                                <a href="{{ route('module.AddRole') }}">
                                    <button style="float: right; margin: 15px 0;"
                                            class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">
                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Yetki Ekle
                                    </button>
                                </a>


                                <div class="card-body">
                                    <div style="overflow-x: scroll; ">
                                        <table style="white-space: nowrap;  margin-bottom: 55px !important;"
                                               class="table table-stripted mb-5">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Yetki Adı</th>
                                                <th>Görünür Adı</th>
                                                <th>Açıklama</th>
                                                <th>Kayıt Tarihi</th>
                                                <th>Değitirilme Zamanı</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($data['roles'] as $role)
                                                <tr id="role-item-{{ $role->id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $role->name }}</td>
                                                    <td>{{ $role->display_name }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)"
                                                           data-title="{{ $role->display_name }}"
                                                           data-toggle="popover-custom-bg"
                                                           data-bg-class="text-light bg-premium-dark"
                                                           data-content="{{ $role->description }}">{{ Str::words($role->description, 4, '...') }}
                                                        </a>
                                                    </td>

                                                    <td class="text-center">{{ $role->created_at }}</td>
                                                    <td class="text-center">{{ $role->updated_at }}</td>
                                                    <td class="text-center" width="150">
                                                        <div class="dropdown d-inline-block">
                                                            <button type="button" aria-haspopup="true"
                                                                    aria-expanded="false"
                                                                    data-toggle="dropdown"
                                                                    class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">
                                                                İşlemler
                                                            </button>
                                                            <div style="max-width: 140px !important; min-width: 1rem;"
                                                                 role="menu" aria-hidden="true" class="dropdown-menu">

                                                                <a class="dropdown-item"
                                                                   href="{{ route('module.EditRole', ['id' => $role->id]) }}">
                                                                    Düzenle
                                                                </a>
                                                                <button type="button" id="{{ $role->id }}" tabindex="0"
                                                                        from="role" class="dropdown-item trash">
                                                                    Sil
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'ModuleGrpups') }} min-vh-100" id="module_groups"
                             role="tabpanel">
                            <div class="main-card mb-3 card">


                                <a href="{{ route('module.AddModuleGrpup') }}">
                                    <button style="float: right; margin: 15px 0;"
                                            class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-alternate">
                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Grup Ekle
                                    </button>
                                </a>


                                <div style="overflow-x: scroll;" class="card-body">
                                    <table style="white-space: nowrap;margin-bottom: 55px !important;"
                                           class="table table-stripted">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Grup Adı</th>
                                            <th>Sırası</th>
                                            <th>Açıklama</th>
                                            <th>Kayıt Tar.</th>
                                            <th>Değiştirilme Tar.</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['module_groups'] as $group)
                                            <tr id="mg-item-{{ $group->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $group->title }}</td>
                                                <td>{{ $group->must }}</td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                       data-title="{{ $group->title }}"
                                                       data-toggle="popover-custom-bg"
                                                       data-bg-class="text-light bg-premium-dark"
                                                       data-content="{{ $group->description }}">{{ Str::words($group->description, 4, '...') }}
                                                    </a>
                                                </td>
                                                <td>{{ $group->created_at }}</td>
                                                <td>{{ $group->updated_at }}</td>
                                                <td class="text-center" width="150">
                                                    <div class="dropdown d-inline-block">
                                                        <button type="button" aria-haspopup="true"
                                                                aria-expanded="false"
                                                                data-toggle="dropdown"
                                                                class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">
                                                            İşlemler
                                                        </button>
                                                        <div style="max-width: 140px !important; min-width: 1rem;"
                                                             role="menu" aria-hidden="true" class="dropdown-menu">

                                                            <a class="dropdown-item"
                                                               href="{{ route('module.EditModuleGroup', ['id' => $group->id]) }}">
                                                                Düzenle
                                                            </a>
                                                            <button type="button" id="{{ $group->id }}" tabindex="0"
                                                                    from="mg" class="dropdown-item trash">
                                                                Sil
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane {{ setActive($tab, 'Modules') }} min-vh-100" id="modules"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <a href="{{ route('module.AddModule') }}">
                                    <button style="float: right; margin: 15px 0;"
                                            class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-focus">
                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Modül Ekle
                                    </button>
                                </a>

                                <div style="overflow-x: scroll;" class="card-body">
                                    <table style="white-space: nowrap; margin-bottom: 55px !important;"
                                           class="table table-stripted mb-5">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Modül Adı</th>
                                            <th colspan="2">Modül Grubu</th>
                                            <th>Sırası</th>
                                            <th>Açıklama</th>
                                            <th>Eklenme Tar.</th>
                                            <th>Değiştirilme Tar.</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['modules'] as $m)
                                            <tr id="module-item-{{ $m->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $m->name }}</td>
                                                <td class="text-center"><i class="pe-3x  {{ $m->ico }}"></i></td>
                                                <td>{{ $m->title }}</td>
                                                <td>{{ $m->must }}</td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                       data-title="{{ $m->name }}"
                                                       data-toggle="popover-custom-bg"
                                                       data-bg-class="text-light bg-premium-dark"
                                                       data-content="{{ $m->description }}">{{ Str::words($m->description, 4, '...') }}
                                                    </a>
                                                </td>
                                                <td>{{ $m->created_at }}</td>
                                                <td>{{ $m->updated_at }}</td>
                                                <td class="text-center" width="150">
                                                    <div class="dropdown d-inline-block">
                                                        <button type="button" aria-haspopup="true"
                                                                aria-expanded="false"
                                                                data-toggle="dropdown"
                                                                class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">
                                                            İşlemler
                                                        </button>
                                                        <div style="max-width: 140px !important; min-width: 1rem;"
                                                             role="menu" aria-hidden="true" class="dropdown-menu">

                                                            <a class="dropdown-item"
                                                               href="{{ route('module.EditModule', ['id' => $m->id]) }}">
                                                                Düzenle
                                                            </a>
                                                            <button type="button" id="{{ $m->id }}" tabindex="0"
                                                                    from="module" class="dropdown-item trash">
                                                                Sil
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'SubModules') }} min-vh-100" id="sub_modules"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <a href="{{ route('module.AddSubModule') }}">
                                    <button style="float: right; margin: 15px 0;"
                                            class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary">
                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Alt Modül Ekle
                                    </button>
                                </a>

                                <div style="overflow-x: scroll;" class="card-body">
                                    <table
                                        style="white-space: nowrap; margin-bottom: 55px !important;"
                                        class="table table-hover table-striped NikolasDataTable table-bordered">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Alt Modül Adı</th>
                                            <th>Route</th>
                                            <th>Sıra</th>
                                            <th colspan="2">Bağ. Modül</th>
                                            <th>Açıklama</th>
                                            <th width="10"></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($data['sub_modules'] as $sub)
                                            <tr id="sub-item-{{ $sub->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sub->sub_name }}</td>
                                                <td>{{ $sub->link }}</td>
                                                <td>{{ $sub->must }}</td>
                                                <td width="5" class="text-center text-primary">
                                                    <i class="pe-2x {{ $sub->ico }}"></i>
                                                </td>
                                                <td> {{ $sub->name }}</td>

                                                <td data-content="{{$sub->description}}">
                                                    <a href="javascript:void(0)"
                                                       data-title="{{ $sub->sub_name }}"
                                                       data-toggle="popover-custom-bg"
                                                       data-bg-class="text-light bg-premium-dark"
                                                       data-content="{{ $sub->description }}">{{ Str::words($sub->description, 4, '...') }}
                                                    </a>
                                                </td>
                                                <td width="10">
                                                    <div class="dropdown d-inline-block">
                                                        <button type="button" aria-haspopup="true"
                                                                aria-expanded="false"
                                                                data-toggle="dropdown"
                                                                class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">
                                                            İşlemler
                                                        </button>
                                                        <div style="max-width: 140px !important; min-width: 1rem;"
                                                             role="menu" aria-hidden="true" class="dropdown-menu">

                                                            <a class="dropdown-item"
                                                               href="{{ route('module.EditSubModule', ['id' => $sub->id]) }}">
                                                                Düzenle
                                                            </a>
                                                            <button type="button" from="sub" id="{{ $sub->id }}"
                                                                    tabindex="0" class="dropdown-item trash">
                                                                Sil
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'RoleModule') }} min-vh-100" id="role_module"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h5 class="card-title">MODÜL İZİN VERMEK İÇİN YETKİ SEÇİN</h5>
                                                <select id="select-roles" class="selectpicker form-control">
                                                    <optgroup label="Tüm Yetkiler">
                                                        <option value="">Yetki Seçiniz</option>

                                                        @foreach($data['roles'] as $role)
                                                            <option role="{{$role->display_name}}"
                                                                    value="{{$role->id}}">{{$role->display_name}}</option>
                                                        @endforeach

                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body text-center">
                                                <a href="javascript:void(0)">
                                                    <button id="BtnGiveRolePermission"
                                                            class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-light">
                                                        <i class="lnr-plus-circle btn-icon-wrapper"> </i>İzin
                                                        Verilecek
                                                        Alt Modül Seç
                                                    </button>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div style="overflow-x: scroll;" class="card-body">
                                    <table id="TableRolePermissions"
                                           style="white-space: nowrap; margin-bottom: 55px !important;"
                                           class="table table-stripted">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Yetki</th>
                                            <th>Alt Modül Adı</th>
                                            <th>Alt Modül Açıklaması</th>
                                            <th colspan="2">Bağ. Modül</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodyRolePerm">
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                <b class="text-success">Data Yok</b>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script src="/backend/assets/scripts/backend-modules.js"></script>


@endsection

@section('js')

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>

    <script>

        $(document).ready(function () {
            $('.NikolasDataTable').DataTable({
                pageLength: 250,
                lengthMenu: dtLengthMenu,
                language: dtLanguage,
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
                buttons: [
                    'copy',
                    'pdf',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6],
                            format: {
                                body: function (data, row, column, node) {
                                    if (typeof $(node).attr('data-content') !== 'undefined') {
                                        data = $(node).attr('data-content');
                                    }
                                    return data;
                                }
                            }
                        },
                        title: "CK - CKG-Sis Alt Modüller - {{ date('d/m/Y') }}",
                    }
                ],
                responsive: true
            });
        });

    </script>
@endsection


@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalGiveRolePermission" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Yetkiye vermek istediğiniz alt modülleri seçin.</p>
                    <br>
                    <table id="GiveRolePermissionsTable" class="table table-striped">
                        <thead>
                        <th>#</th>
                        <th>Alt Modül</th>
                        <th>Açıklama</th>
                        <th colspan="2">Bağ. Old. Modül</th>
                        </thead>
                        <tbody id="GiveRolePermissionsTbody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
