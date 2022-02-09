@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/ticket-details.css">
@endpush()

@section('title', 'Ticket Detay - "' . $ticket->title .'"')


@section('content')
    <div class="app-main__inner">

        <div class="row">

            <div class="col-md-3">
                <div class="card mb-3">

                    <div style="" class="card-body">

                        <h5 class="font-weight-bold text-alternate">Destek Almaktan Çekinmeyin</h5>

                        <p class="mt-3">Sistemle ilgili tüm sorunlarınızı sistem destek birimine iletebilirsiniz.
                            Sizlere yardımcı
                            olmaktan mutluluk duyarız.</p>

                        <div class="text-center">
                            <a href="{{route('systemSupport.NewTicket')}}">
                                <button style="width: 100%; max-width: 450px;"
                                        class="mb-2 mr-2 btn-icon btn-square btn btn-alternate">
                                    Destek Bildirimi Oluşturun
                                    <i class="pe-7s-ticket ml-2 btn-icon-wrapper"> </i>
                                </button>
                            </a>
                            <a href="{{route('systemSupport.myTickets')}}">
                                <button style="width: 100%; max-width: 450px;"
                                        class="mb-2 mr-2 btn-icon btn-square btn btn-primary">
                                    Tüm Destek Taleplerim
                                    <i class="pe-7s-back ml-2 btn-icon-wrapper"> </i>
                                </button>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-9">

                <div class="card mb-3">

                    <div style="min-height: 70vh;overflow-x: auto;" class="card-body">

                        @if($ticket->status == 'AÇIK')
                            <div class="alert alert-success">Bu Destek Bildirimi Açıktır. Yetkililer <b>En Kısa Sürede
                                    Bildiriminizi Yanıtlayacaktır.</b>
                            </div>
                        @elseif($ticket->status == 'BEKLEMEDE')
                            <div class="alert alert-warning">Bu Destek Bildirimi Şu An Beklemede. Yetkililer <b>İlgili
                                    Araştırma Sonucu En Kısa Sürede Dönüş Yapacaktır.</b>
                            </div>
                        @elseif($ticket->status == 'CEVAPLANDI')
                            <div class="alert alert-alternate">Bu destek bildirimi yanıtlanmıştır. <b> Yanıt
                                    vermezseniz, sistem bu destek talebini 24 saat içerisinde kapatacaktır.</b>
                            </div>
                        @elseif($ticket->status == 'KAPANDI')
                            <div class="alert alert-dark">Bu destek bildirimi kapatılmıştır. <b> Tekrar açık hale
                                    getirmek için cevap yazın.</b>
                            </div>
                        @endif

                        <h3>{{'#D-' .  $ticket->id . ' - ' . $ticket->title}}</h3>
                        <div class="divider"></div>

                        <div class="row">
                            <div class="col-md-3">
                                <p style="float: left; display: block">Bildirim Durumu :
                                    <b class="text-primary">{{$ticket->status}}</b></p>
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

                                <form id="ticketForm" method="POST" action="{{ route('systemSupport.replyTicket') }}"
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
                                                    <p>Lütfen mesajınızı bir kaç cümle ile belirtin.</p>
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

                                    <button style="float: left" type="submit"
                                            class="btn-wide mb-2 mr-2 btn-icon-vertical btn btn-success btn-lg btnReply">
                                        <i
                                            class="pe-7s-paper-plane btn-icon-wrapper "> </i>Cevapla
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

                                            <span class="ticket-date"> {{substr($detail->created_at, 0 , 16)}}
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
                                                           href="/backend/assets/ticket_files/{{$detail->file1}}">Ek1</a>
                                                    </b>
                                                @endif

                                                @if($detail->file2 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/backend/assets/ticket_files/{{$detail->file2}}">Ek2</a>
                                                    </b>
                                                @endif

                                                @if($detail->file3 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/backend/assets/ticket_files/{{$detail->file3}}">Ek3</a>
                                                    </b>
                                                @endif

                                                @if($detail->file4 != '')
                                                    <b class="attachment">
                                                        <a target="_blank"
                                                           href="/backend/assets/ticket_files/{{$detail->file4}}">Ek4</a>
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

                                    <span class="ticket-date"> {{substr($ticket->created_at, 0 , 16)}}
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
                                                   href="/backend/assets/ticket_files/{{$ticket->file1}}">Ek1</a>
                                            </b>
                                        @endif

                                        @if($ticket->file2 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/backend/assets/ticket_files/{{$ticket->file2}}">Ek2</a>
                                            </b>
                                        @endif

                                        @if($ticket->file3 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/backend/assets/ticket_files/{{$ticket->file3}}">Ek3</a>
                                            </b>

                                        @endif

                                        @if($ticket->file4 != '')
                                            <b class="attachment">
                                                <a target="_blank"
                                                   href="/backend/assets/ticket_files/{{$ticket->file4}}">Ek4</a>
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

    @php($notBootstrap = true)
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

    </script>
@endsection

