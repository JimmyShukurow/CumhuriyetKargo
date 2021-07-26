@extends('backend.layout')

@section('title', 'Modül Sıralama')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Modül Sıralama
                        <div class="page-title-subheading">Bu sayfa üzerinden sistemdeki tüm modüllerin sıralamalarını
                            güncelleyebilirsiniz.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mb-3 card">
            <div class="card-header">
                <ul class="nav nav-justified">

                    <li class="nav-item">
                        <a data-toggle="tab" href="#module_groups" class="active nav-link">Modül Grupları</a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#modules"
                           class="{{ setActive($tab, 'Modules')}} nav-link">Modüller</a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#sub_modules" class="{{ setActive($tab, 'SubModules')}} nav-link">Alt
                            Modüller</a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active min-vh-100" id="module_groups"
                         role="tabpanel">
                        <div class="main-card mb-3 card">

                            <div style="overflow-x: scroll;" class="card-body">
                                <table style="white-space: nowrap;margin-bottom: 55px !important;"
                                       class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Grup Adı</th>
                                        <th>Açıklama</th>
                                        <th>Kayıt Tar.</th>
                                        <th>Değiştirilme Tar.</th>
                                    </tr>
                                    </thead>
                                    <tbody id="mg-sort">
                                    @foreach ($data['module_groups'] as $group)
                                        <tr id="item-{{ $group->id }}">
                                            <td class="sortable">{{ $group->title }}</td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                   data-title="{{ $group->title }}"
                                                   data-toggle="popover-custom-bg"
                                                   data-bg-class="text-light bg-premium-dark"
                                                   data-content="{{ $group->description }}">{{ substr($group->description, 0, 45) }}
                                                    ..
                                                </a>
                                            </td>
                                            <td width="50">{{ $group->created_at }}</td>
                                            <td width="50">{{ $group->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane {{ setActive($tab, 'Modules') }} min-vh-100" id="modules" role="tabpanel">

                        <div class="col-md-12">
                            <div id="accordion" class="accordion-wrapper mb-3">

                                @php $module = GetTitles()  @endphp

                                @foreach($module as $title)

                                    <div class="card">
                                        <div id="heading{{$title->id}}" class="card-header">
                                            <button type="button" data-toggle="collapse"
                                                    data-target="#collapse{{$title->id}}"
                                                    aria-expanded="{{$loop->index == 0 ? 'true' : 'false'}}"
                                                    aria-controls="collapse{{$title->id}}"
                                                    class="text-left m-0 p-0 btn btn-link btn-block">
                                                <h5 class="m-0 p-0">{{$title->title}}</h5>
                                            </button>
                                        </div>
                                        <div data-parent="#accordion" id="collapse{{$title->id}}"
                                             aria-labelledby="heading{{$title->id}}"
                                             class="collapse {{$loop->index == 0 ? 'show' : ''}}">
                                            <div class="card-body">

                                                <table style="white-space: nowrap; margin-bottom: 55px !important;"
                                                       class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2">Modül Adı</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="module-sort">
                                                    @php $module_names = GetModuleNames($title->title)@endphp

                                                    @foreach($module_names as $mname)
                                                        <tr id="item-{{$mname->id}}">
                                                            <td width="10" class="text-center sortable"><i
                                                                    class="pe-3x  {{$mname->ico }}"></i></td>
                                                            <td>{{ $mname->module_name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane {{ setActive($tab, 'SubModules') }} min-vh-100" id="sub_modules"
                         role="tabpanel">
                        <div class="col-md-12">
                            <div id="accordion" class="accordion-wrapper mb-3">

                                @php $module = GetTitles()  @endphp

                                @foreach($module as $title)

                                    @php $counter = $loop->index @endphp

                                    @php $module_names = GetModuleNames($title->title)@endphp

                                    @foreach($module_names as $mname)
                                        <div class="card">
                                            <div id="heading{{$mname->id}}" class="card-header">
                                                <button type="button" data-toggle="collapse"
                                                        data-target="#collapse{{$mname->id}}"
                                                        aria-expanded="{{$counter == 0 && $loop->first ? 'true' : 'false'}}"
                                                        aria-controls="collapse{{$mname->id}}"
                                                        class="text-left m-0 p-0 btn btn-link btn-block">
                                                    <h5 class="m-0 p-0">
                                                        <i class="pe-3x  {{$mname->ico }}"></i>
                                                        {{$mname->module_name}}</h5>
                                                </button>
                                            </div>
                                            <div data-parent="#accordion" id="collapse{{$mname->id}}"
                                                 aria-labelledby="heading{{$mname->id}}"
                                                 class="collapse {{$counter == 0 && $loop->first ? 'show' : ''}}">
                                                <div class="card-body">

                                                    <table style="white-space: nowrap; margin-bottom: 55px !important;"
                                                           class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th colspan="2">Modül Adı</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="sub-module-sort">

                                                        @php $sub_module_names = GetModulesAllInfo($mname->module_name)  @endphp

                                                        @foreach($sub_module_names as $subname)
                                                            <tr id="item-{{$subname->sub_id}}">
                                                                <td class="sortable">{{ $subname->sub_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery-ui.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#mg-sort').sortable({
            revert: true,
            handle: ".sortable",
            stop: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                    type: "POST",
                    data: data,
                    url: "{{route('module.mg.Sort')}}",
                    success: function (msg) {
                        if (msg) {
                            ToastMessage('success', 'Düzenleme kaydedildi!', 'İşlem Başarılı!');
                        } else {
                            ToastMessage('success', 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin!', 'İşlem Başarısız!');
                        }
                    }
                });
            }
        });

        $('#mg-sort').disableSelection();

        $('.module-sort').sortable({
            revert: true,
            handle: ".sortable",
            stop: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                    type: "POST",
                    data: data,
                    url: "{{route('module.module.Sort')}}",
                    success: function (msg) {
                        if (msg) {
                            ToastMessage('success', 'Düzenleme kaydedildi!', 'İşlem Başarılı!');
                        } else {
                            ToastMessage('success', 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin!', 'İşlem Başarısız!');
                        }
                    }
                });
            }
        });

        $('.module-sort').disableSelection();

        $('.sub-module-sort').sortable({
            revert: true,
            handle: ".sortable",
            stop: function (event, ui) {
                var data = $(this).sortable('serialize');
                $.ajax({
                    type: "POST",
                    data: data,
                    url: "{{route('module.subModule.Sort')}}",
                    success: function (msg) {
                        if (msg) {
                            ToastMessage('success', 'Düzenleme kaydedildi!', 'İşlem Başarılı!');
                        } else {
                            ToastMessage('success', 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin!', 'İşlem Başarısız!');
                        }
                    }
                });
            }
        });

        $('.sub-module-sort').disableSelection();

    </script>
    <script src="/backend/assets/scripts/backend-modules.js"></script>

    @php($notBootstrap = true)
@endsection
