@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/ticket-details.css">
@endpush

@section('title', 'Adm.Tick. - ' .  $ticket->title)


@section('content')
    <div class="app-main__inner">

        <div class="row">

            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <i class="header-icon pe-7s-ticket icon-gradient bg-mixed-hopes"> </i>Tıcket Özeti
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm nav btn-group">
                                <a data-toggle="tab" href="#tab-eg3-0"
                                   class=" pl-3 active btn btn-focus">Özet Görüntülemeler</a>
                                <a data-toggle="tab" href="#tab-eg3-1" class="btn btn-focus">Tüm Görüntülemeler</a>
                                <a data-toggle="tab" href="#tab-eg3-2"
                                   class="pr-3  btn btn-focus">Ticket Hareketleri</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div style="max-height: 200px; overflow: auto; white-space: nowrap" class="tab-pane active"
                                 id="tab-eg3-0"
                                 role="tabpanel">
                                <table class="table TableNoPadding table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Görüntülemeler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($view_details_summery as $d)
                                        <tr>
                                            <td class="text-center">
                                                <b class="text-primary">{{$d->name_surname}}</b>
                                                <b class="text-danger">({{$d->display_name}})</b>
                                                en son <b
                                                    class="text-dark">{{ \Carbon\Carbon::parse($d->last_view)->translatedFormat('d F Y H:i') }}</b>
                                                tarihinde
                                                görüntüledi. <b class="text-alternate">({{$d->count}} Görüntüleme)</b>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <p>Bu destek talebini <b>{{$quantity}} Yetkili</b>, Toplam
                                    <b>{{$sum}} defa gönüntüledi</b>.</p>
                            </div>
                            <div class="tab-pane" id="tab-eg3-1" role="tabpanel">
                                <div style="max-height: 200px; overflow: auto; white-space: nowrap"
                                     class="tab-pane active" id="tab-eg3-0"
                                     role="tabpanel">
                                    <table class="table  table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Görüntüleyen</th>
                                            <th>Yetki</th>
                                            <th>Son Görünt. Zamanı</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($view_details as $d)
                                            <tr>
                                                <td class="font-weight-bold text-primary">{{$d->name_surname}}</td>
                                                <td class="font-weight-bold text-danger">{{$d->display_name}}</td>
                                                <td class="font-weight-bold">{{$d->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab-eg3-2" role="tabpanel">
                                <div style="max-height: 200px; overflow: auto; white-space: nowrap"
                                     class="tab-pane active" id="tab-eg3-0"
                                     role="tabpanel">
                                    <table class="table  table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Görüntüleyen</th>
                                            <th>Yetki</th>
                                            <th>Log Name</th>
                                            <th>Hareket</th>
                                            <th>Detaylar</th>
                                            <th>Tarih</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($ticket_logs as $d)
                                            <tr id="logs-item-{{$d->id}}">
                                                <td class="font-weight-bold text-primary">{{$d->name_surname}}</td>
                                                <td class="font-weight-bold text-danger">{{$d->display_name}}</td>
                                                <td class="font-weight-bold text-dark">{{$d->log_name}}</td>
                                                <td>{{$d->description}}</td>
                                                <td width="10" class="text-center">
                                                    @if($d->properties != '[]')
                                                        <button id="{{$d->id}}" properties="{{$d->properties}}"
                                                                class="btn  btn-xs btn-danger properties-log">Detay
                                                        </button>
                                                    @endif
                                                </td>
                                                <td width="10" class="font-weight-bold">{{$d->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">

                <div class="card mb-3">

                    <div style="min-height: 70vh;overflow-x: auto;" class="card-body">

                        <div class="row">
                            <div class="col-md-9">
                                <h3>{{'#D-' .  $ticket->id . ' - ' . $ticket->title}}</h3>
                            </div>
                            <div class="col-md-3">
                                <a href="{{route('admin.systemSupport.index')}}">
                                    <button style="width: 100%; max-width: 350px;"
                                            class="mb-2 mr-2 btn-icon btn-square btn btn-alternate">
                                        Destek Taleplerine Geri Dön
                                        <i class="pe-7s-back ml-2 btn-icon-wrapper"> </i>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="row">
                            <div class="col-md-3">
                                <p style="float: left; display: block">Bildirim Durumu :
                                    <b class="text-primary">{{$ticket->status}}</b></p>
                            </div>
                            <div class="col-md-3">

                            </div>

                            <div class="col-md-3 text-center">
                                <!-- Button trigger modal -->
                                <button style="width: 100%; max-width: 350px;" data-toggle="modal"
                                        data-target="#modalUpdateStatus"
                                        class="mb-2 mr-2 btn-icon btn-square btn btn-danger">
                                    Bildirim Durumu Güncelle
                                    <i class="pe-7s-attention ml-2 btn-icon-wrapper"> </i>
                                </button>
                            </div>
                            <div class="col-md-3 text-center">
                                <!-- Button trigger modal -->
                                <button style="width: 100%; max-width: 350px;" data-toggle="modal"
                                        data-target="#exampleModal"
                                        class="mb-2 mr-2 btn-icon btn-square btn btn-primary">
                                    Talebi Yönlendir
                                    <i class="pe-7s-back ml-2 btn-icon-wrapper"> </i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                Departman <br> <b class="text-primary">{{$ticket->department_name}}</b>
                            </div>
                            <div class="col-md-4">
                                Oluşt. Tarihi <br> <b class="text-primary">{{substr($ticket->created_at, 0 , 16)}}
                                    <small>{{'(' . \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() .')'}}</small>
                                </b>
                            </div>
                            <div class="col-md-2">
                                Öncelik <br> <b class="text-primary">{{$ticket->priority}}</b>
                            </div>
                            <div class="col-md-4">
                                Son Yanıt <br> <b class="text-primary">{{ substr($ticket->updated_at, 0 , 16)}}
                                    <small>{{'(' . \Carbon\Carbon::parse($ticket->updated_at)->diffForHumans() .')'}}</small>
                                </b>
                            </div>
                        </div>

                        <p class="mt-4 text-right">
                            <a class="mb-2 mr-2 btn-icon btn-square btn btn-success" data-toggle="collapse"
                               href="#collapseExample" role="button"
                               aria-expanded="false" aria-controls="collapseExample"><i
                                    class="pe-7s-pen btn-icon-wrapper"> </i>CEVAP YAZ
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="borderles">

                                <form id="ticketForm" method="POST"
                                      action="{{ route('admin.systemSupport.replyTicket') }}"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="token" value="{{Crypte4x($ticket->id)}}">

                                    <script
                                        src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
                                    <script
                                        src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/translations/tr.js"></script>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="agency" class="">Cevap Yaz: </label>
                                                <div id="editor">
                                                    <p>Merhaba,</p>
                                                    <p></p>
                                                    <p>Soru ve problemleriniz için sizelere yardımcı olmaktan mutluluk
                                                        duyar, iyi çalışmalar
                                                        dilerim.</p>
                                                </div>

                                            </div>
                                        </div>
                                        <input type="hidden" id="message" name="message">

                                        <script>
                                            ClassicEditor
                                                .create(document.querySelector('#editor'), {
                                                    language: 'tr'
                                                })
                                                .then(editor => {
                                                    console.log('Editor was initialized', editor);
                                                    myEditor = editor;
                                                })
                                                .catch(error => {
                                                    console.error(error);
                                                });
                                        </script>

                                        <style>
                                            .ck-editor__editable_inline {
                                                min-height: 25vh !important;
                                            }
                                        </style>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="status">Bildirim Durumu:</label>
                                                <select name="status" required id="status" class="form-control">
                                                    <option value="Cevaplandı">Cevaplandı</option>
                                                    <option value="Kapandı">Kapandı</option>
                                                    <option value="Beklemede">Beklemede</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="files1">Eklentiler</label>
                                                <input type="file" name="file1" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file1"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="files2">Eklentiler</label>
                                                <input type="file" name="file2" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file2"
                                                       class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="files3">Eklentiler</label>
                                                <input type="file" name="file3" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file3"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="files4">Eklentiler</label>
                                                <input type="file" name="file4" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file4"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <em class="text-primary text-dark pb-2">İzin verilen dosya türleri: .jpg,
                                                .gif, .jpeg,.png,.doc, .xls, .pdf, .txt, .docx,.xlsx
                                            </em>
                                        </div>
                                    </div>

                                    <button style="float: left"
                                            class="btn-wide mb-2 mr-2 btn-icon-vertical btn btn-success btn-lg btnReply">
                                        <i
                                            class="pe-7s-paper-plane btn-icon-wrapper"> </i>Cevapla
                                    </button>
                                    <button type="reset" style="float: right"
                                            class="btn-wide mb-2 mr-2 btn-icon-vertical btn btn-danger btn-lg"><i
                                            class="pe-7s-close-circle btn-icon-wrapper"> </i>İptal Et
                                    </button>
                                </form>

                            </div>
                        </div>

                        {{-- REPLİES--}}
                        @foreach($ticket_details as $detail)

                            @if(isRedirectedMessage($detail->message) != null)
                                <div class="alert alert-secondary">
                                    Bu destek talebi <b>{{$detail->name_surname}}</b>
                                    <b>({{ $detail->display_name }})</b>
                                    tarafından
                                    <b>{{\Carbon\Carbon::parse($detail->created_at)->translatedFormat('d F D Y H:i')}}</b>
                                    tarihinde
                                    <b>{{isRedirectedMessage($detail->message)}}</b> departmanına
                                    yönlendirildi.
                                </div>
                            @elseif(isUpdatedStatusMessage($detail->message) != null)
                                <div class="alert alert-success">
                                    <b>{{$detail->name_surname}}</b>
                                    <b>({{ $detail->display_name }})</b>
                                    tarafından
                                    <b>{{\Carbon\Carbon::parse($detail->created_at)->translatedFormat('d F D Y H:i')}}</b>
                                    tarihinde destek talebinin durumu
                                    <b>{{isUpdatedStatusMessage($detail->message)}}</b> olarak güncellendi.
                                </div>
                            @else
                                <div class="answer">
                                    <div
                                        class="borderles {{$ticket->user_id != $detail->user_id ? 'department-reply' : '' }}">
                                        <div class="top-row clearfix">
                                            <span class="icon-helper">
                                             <img width="42" class="rounded-circle"
                                                  src="/backend/assets/images/ck-ico-blue.png"
                                                  alt="">
                                            </span>
                                            <span class="ticket-user">
                                        <span class="user-type"> </span>
                                        <span
                                            class="user-name"> {{$ticket->user_id != $detail->user_id ? 'YETKİLİ:' : '' }} {{$detail->name_surname}} </span>
                                            </span>

                                            <span class="ticket-date">{{\Carbon\Carbon::parse($detail->created_at)->translatedFormat('d F D Y H:i')}}
                                            <small>{{'(' . \Carbon\Carbon::parse($detail->created_at)->diffForHumans() .')'}}</small>
                                            </span>
                                        </div>

                                        <div class="message">
                                            {!! $detail->message !!}
                                        </div>

                                        @if ($detail->file1 != '' || $detail->file2 != '' || $detail->file3 != '' || $detail->file4 != '')
                                            <div class="attachments">
                                                @if($detail->file1 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/files/ticket_files/{{$detail->file1}}">Ek1</a>
                                                    </b>
                                                @endif

                                                @if($detail->file2 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/files/ticket_files/{{$detail->file2}}">Ek2</a>
                                                    </b>
                                                @endif

                                                @if($detail->file3 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/files/ticket_files/{{$detail->file3}}">Ek3</a>
                                                    </b>
                                                @endif

                                                @if($detail->file4 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/files/ticket_files/{{$detail->file4}}">Ek4</a>
                                                    </b>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="divider"></div>

                                        <div class="summery">
                                            {{ $detail->name_surname }} <b>({{ $detail->display_name }})</b> <br>
                                            {{ $detail->branch_city . '/' . $detail->branch_district . ' - ' . $detail->branch_name . ' ' . tr_strtoupper($detail->user_type)  }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endforeach

                        {{-- MESSAGE OF TICKET--}}
                        <div class="answer">
                            <div class="borderles">
                                <div class="top-row clearfix">
                                            <span class="icon-helper">
                                             <img width="42" class="rounded-circle"
                                                  src="/backend/assets/images/ck-ico-blue.png"
                                                  alt="">
                                            </span>
                                    <span class="ticket-user">
                                        <span class="user-type"> </span>
                                        <span class="user-name">{{$ticket->name_surname}} </span>
                                            </span>

                                    <span class="ticket-date"> {{\Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F D Y H:i')}}
                                            <small>{{'(' . \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() .')'}}</small>
                                            </span>
                                </div>


                                <div class="message">
                                    {!! $ticket->message !!}
                                </div>


                                @if ($ticket->file1 != '' || $ticket->file2 != '' || $ticket->file3 != '' || $ticket->file4 != '')
                                    <div class="attachments">
                                        @if($ticket->file1 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/files/ticket_files/{{$ticket->file1}}">Ek1</a>
                                            </b>
                                        @endif

                                        @if($ticket->file2 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/files/ticket_files/{{$ticket->file2}}">Ek2</a>
                                            </b>
                                        @endif

                                        @if($ticket->file3 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/files/ticket_files/{{$ticket->file3}}">Ek3</a>
                                            </b>
                                        @endif

                                        @if($ticket->file4 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/files/ticket_files/{{$ticket->file4}}">Ek4</a>
                                            </b>
                                        @endif
                                    </div>
                                @endif

                                <div class="divider"></div>

                                <div class="summery">
                                    {{ $ticket->name_surname }} <b>({{ $ticket->display_name }})</b> <br>
                                    {{ $ticket->phone }}<br>
                                    {{ $ticket->branch_city . '/' . $ticket->branch_district . ' - ' . $ticket->branch_name . ' ' .tr_strtoupper($ticket->user_type)  }}
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

        $(document).ready(function () {
            $('#ticketForm').submit(function () {
                $('#message').val(myEditor.getData());
            })

            @if(old('message') !== null)
            myEditor.setData('{!!  old('message') !!}')
            @endif
        })

        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        $(document).on('click', '.properties-log', function () {
            var properties = $(this).attr('properties');
            var log_id = $(this).attr('id');
            $('#json-renderer').text(properties);
            $('#json-renderer').jsonViewer(JSON.parse(properties), {
                collapsed: false,
                rootCollapsable: false,
                withQuotes: false,
                withLinks: true
            });
            $('#userName').text($("#logs-item-" + log_id + " > td:nth-child(1)").text());
            $('#userAgency').text($("#logs-item-" + log_id + " > td:nth-child(2)").text());
            $('#log-time').text($("#logs-item-" + log_id + " > td:nth-child(6)").text());
            $('#log-type').text($("#logs-item-" + log_id + " > td:nth-child(3)").text());
            $('#description').text($("#logs-item-" + log_id + " > td:nth-child(4)").text());
            $('#ModalLogProperties').modal();
        });

        $('#tc').change(function () {
            $('#agency').val('');
            $('#user_type').val('');
        });
        $('#agency').change(function () {
            $('#tc').val('');
            $('#user_type').val('');
        });


    </script>
@endsection

@section('modals')
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>

    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalLogProperties" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Log Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-image "
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract2.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 id="userName" class="menu-header-title">###</h5>
                                            <h6 id="userAgency" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">Log Detayları</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">İşlem Zamanı</td>
                                                    <td id="log-time"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">İşlem</td>
                                                    <td id="log-type"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Açıklama</td>
                                                    <td id="description">Adres Satırı</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <h3 class="text-center text-primary">Özellikler</h3>
                                    <pre id="json-renderer"></pre>


                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- CARD END --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal STATUS -->
    <div class="modal fade" id="modalUpdateStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bildirim Durumnu Güncelle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Lütfen destek talebinin bildirim durumunu seçin.</p>
                    <form id="updateStatusOfTicketForm" method="post"
                          action="{{route('admin.systemSupport.updateStatusTicket')}}">
                        @csrf
                        <input type="hidden" name="x_token" value="{{Crypte4x($ticket->id)}}">
                        <div class="form-row mt-3">
                            <label for="status">Bildirim Durumu:</label>
                            <select required class="form-control" name="status" id="status">
                                <option value="">Seçiniz</option>
                                <option value="Açık">Açık</option>
                                <option value="Beklemede">Beklemede</option>
                                <option value="Cevaplandı">Cevaplandı</option>
                                <option value="Kapandı">Kapandı</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal Et</button>
                    <button type="button" id="BtnUpdateStatusOfTicket" class="btn btn-primary">Bildirim Durumunu
                        Güncelle
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Destek Talebini Yönlendir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Lütfen destek talebini yönlendirmek istediğiniz departmanı seçin.</p>
                    <form id="redirectToDepartmentForm" method="post"
                          action="{{route('admin.systemSupport.redirectTicket')}}">
                        @csrf
                        <input type="hidden" name="x_token" value="{{Crypte4x($ticket->id)}}">
                        <div class="form-row mt-3">
                            <label for="department">Departman:</label>
                            <select required class="form-control" name="department" id="department">
                                <option value="">Seçiniz</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal Et</button>
                    <button type="button" id="btnSubmit" class="btn btn-primary">Talebi Yönlendir</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#btnSubmit').click(function () {
            $('#redirectToDepartmentForm').submit();
        });

        $('#BtnUpdateStatusOfTicket').click(function () {
            $('#updateStatusOfTicketForm').submit();
        });


    </script>
@endsection
