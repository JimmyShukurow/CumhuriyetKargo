@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Bildirimler & Duyurular')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-bell icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Bildirimler & Duyurular
                        <div class="page-title-subheading">Bu sayfa üzerinden bildirimlerinizi ve yöneticiler tarafından
                            paylaşılan duyuruları takip edebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <button id="btnRefresh" type="button" data-toggle="tooltip" title="Yenile"
                            data-placement="bottom"
                            class="btn-shadow mr-3 btn btn-dark" data-original-title="Example Tooltip">
                        <i class="pe-7s-refresh"></i>
                    </button>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 card min-vh-100">
                    <div class="tabs-lg-alternate card-header">
                        <ul class="nav nav-justified">
                            <li class="nav-item">

                                <a data-toggle="tab" href="#tab-notifications"
                                   class="nav-link show {{$tab ==  'Notifications' ? 'active' : ''}}">
                                    <div class="widget-number">Bildirimler</div>
                                    <div class="tab-subheading">
                                            <span class="pr-2 opactiy-6">
                                                <i class="fa fa-bell fa-2x"></i>
                                                @if(Auth::user()->unReadnotifications->count())
                                                    <span id="spanNotificationQuantity" style="margin-left: -3px;"
                                                          class="badge badge-secondary">{{Auth::user()->unReadnotifications->count()}}</span>
                                                @endif
                                            </span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="#tab-announcements"
                                   class="nav-link show {{$tab ==  'Announcements' ? 'active' : ''}}">
                                    <div class="widget-number text-danger">Duyurular</div>
                                    <div class="tab-subheading">
                                        <span class="pr-2 opactiy-6">
                                            <i class="fa fa-fw fa-2x" aria-hidden="true"
                                               title="Copy to use comment"></i>
                                         </span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show {{$tab ==  'Notifications' ? 'active' : ''}}" id="tab-notifications"
                             role="tabpanel">
                            <div class="card-body">
                                <div style="overflow: auto;white-space: nowrap;" class="card-body">
                                    <div>
                                        <button class="btn mb-4 btn-alternate markAllRead">Tümünü
                                            Okundu Olarak İşaretle
                                        </button>
                                    </div>
                                    <table id="TableRolePermissions"
                                           style="white-space: nowrap; "
                                           class="table table-hover table-striped  table-bordered Table30Padding NikolasDataTable IdleDistricts table-hover">
                                        <thead>
                                        <tr>
                                            <th>Bildirim</th>
                                            <th>Tarih</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($notifications as $notification)
                                            @php
                                                $data = json_decode($notification->data);
                                            @endphp
                                            <tr id="NotfDot-{{$notification->id}}">
                                                <td>
                                                    <a href="{{$notification->data != '' ?  $data->link : '#'}}"
                                                       id="aNotfContent-{{$notification->id}}"
                                                       data-id="{{$notification->id}}"
                                                       class="{{$notification->read_at == '' ? 'text-primary' : 'text-dark' }} NotfContent mark-as-read"> {{$data->notification}}
                                                    </a>
                                                    <span id="spanNotfHumenTime-{{$notification->id}}"
                                                          class="{{$notification->read_at == '' ? 'text-primary' : 'text-dark' }} NotfContent">
                                                        ({{\Carbon\Carbon::parse($notification->created_at)
                                                    ->diffForHumans()}})
                                                    </span>
                                                </td>
                                                <td class="{{$notification->read_at == '' ? 'text-primary' : 'text-dark' }} date-of-notf-{{$notification->id}} NotfContent">
                                                    {{$notification->created_at}}
                                                </td>
                                                <td>
                                                    <b id="markRead-{{$notification->id}}"
                                                       data-id="{{$notification->id}}"
                                                       class="{{$notification->read_at == '' ? 'text-primary fake-link mark-as-read2' : 'text-dark' }}">
                                                        Okundu
                                                    </b>
                                                </td>
                                            </tr>
                                        @endforeach


                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Bildirim</th>
                                            <th>Tarih</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div
                                    style="display: flex;align-items: center;justify-content: center;margin-top: 2rem;">
                                    {{ $notifications->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show {{$tab ==  'Announcements' ? 'active' : ''}}" id="tab-announcements"
                             role="tabpanel">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="card-body">
                                        <table id="TableRolePermissions"
                                               style="white-space: nowrap; "
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleAgenciesTC"
                                               role="grid">
                                            <thead>

                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Acente Sahibi</th>
                                                <th>Bağlı Old. Aktarma</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Acente Sahibi</th>
                                                <th>Bağlı Old. Aktarma</th>
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
    </div>
@endsection


@section('js')
    <script>
        $('#btnRefresh').click(function () {
            location.reload();
        });
    </script>
@endsection
