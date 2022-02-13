@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Destek Taleplerim')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-ticket icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Destek Taleplerim
                        <div class="page-title-subheading">Bu sayfa üzerinden açmış olduğunuz tüm destek taleplerinizi
                            görüntüleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <button id="btnRefresh" type="button" data-toggle="tooltip" title="Yenile"
                            data-placement="bottom"
                            class="btn-shadow mr-3 btn btn-dark" data-original-title="Example Tooltip">
                        <i class="pe-7s-refresh"></i>
                    </button>

                    <div class="d-inline-block dropdown">
                        <a href="{{ route('systemSupport.NewTicket') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Destek Talebi Oluştur
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-ticket mr-3 text-muted opacity-6"> </i>Tüm Destek Talepleri
                    <div class="badge mr-2 ml-2 badge-alternate">{{$data['count']}}</div>
                </div>
            </div>
            <div style="min-height: 70vh;overflow-x: auto;" class="card-body">

                <table
                    class="NikolasDataTable align-middle mb-0 table table-borderless table-striped ">
                    <thead>
                    <tr>
                        <th>Durum</th>
                        <th>Departman</th>
                        <th>Oluşturan</th>
                        <th>Başlık</th>
                        <th>Öncelik</th>
                        <th>Oluşt. Tarihi</th>
                        <th>Son Güncelleme</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($data['tickets'] as $ticket)


                        <tr>
                            <td width="50">
                                @if($ticket->status == 'AÇIK')
                                    <div class="badge badge-success">{{$ticket->status}}</div>
                                @elseif($ticket->status == 'BEKLEMEDE')
                                    <div class="badge badge-warning">{{$ticket->status}}</div>
                                @elseif($ticket->status == 'CEVAPLANDI')
                                    <div class="badge badge-alternate">{{$ticket->status}}</div>
                                @elseif($ticket->status == 'KAPANDI')
                                    <div class="badge badge-dark">{{$ticket->status}}</div>
                                @endif
                            </td>
                            <td width="130">{{$ticket->department_name}}</td>
                            <td width="130">{{$ticket->name_surname}}</td>
                            <td>
                                <a class="text-primary font-weight-bold"
                                   href="{{route('systemSupport.TicketDetails', ['TicketID' => $ticket->id])}}">
                                    {{'#D-'.$ticket->id . ' - ' . Str::Words($ticket->title, 3 , '...') }}
                                </a>
                            </td>

                            <td>
                                <div
                                    class="badge badge-pill badge-{{PriorityColor($ticket->priority)}}">{{$ticket->priority}}</div>
                            </td>

                            <td class="font-weight-bold">{{substr($ticket->created_at, 0 , 16)}}</td>
                            <td class="font-weight-bold">{{substr($ticket->updated_at, 0 , 16)}}</td>
                            <td width="150">
                                <a href="{{route('systemSupport.TicketDetails', ['TicketID' => $ticket->id])}}">
                                    <button
                                        class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-secondary">
                                        <i class="pe-7s-look btn-icon-wrapper"> </i>Detaylar
                                    </button>
                                </a>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>

                <div style="display: flex;align-items: center;justify-content: center;margin-top: 2rem;">
                    {{ $data['tickets']->links() }}
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
