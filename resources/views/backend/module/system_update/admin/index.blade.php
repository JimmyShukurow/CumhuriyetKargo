@extends('backend.layout')

@section('title', 'Sistem Güncelleme')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-config icon-gradient bg-ck"> </i>
                    </div>
                    <div>[Admin] CKG-Sis Sistem Güncellemeleri
                        <div class="page-title-subheading">Bu modül üzerinden sisteme yeni version güncellemelerini ve
                            eklenen yeni özellikleri takip edebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('module.systemUpdate.Create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Güncelleme Girişi
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">CKG-Sis Sistem Version Geçmişi</h5>
                <div class="card-body">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; white-space: nowrap;"
                               class="TableNoPadding table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Version</th>
                                <th>Version Başlığı</th>
                                <th>Kayıt Tarihi</th>
                                <th width="10">Detay</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $d) {{-- datatable da düzgün listelenmiyor alfabeyi baz alıyor olabilir--}}
                            <tr>
                                <td>{{ $d->version }}</td>
                                <td>{{ $d->title }}</td>
                                <td>{{ $d->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('module.systemUpdate.Edit', $d->id)}}">
                                        <button type="button" class="btn mr-2 mb-2 btn-primary text-white">
                                            Detay
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div
                        style="display: flex;align-items: center;justify-content: center;margin-top: 2rem;">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>

    <script>
        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        $(document).ready(function () {
            $('.openModal').click(function () {

                $('.modal-content').block({
                    message: $('<div class="loader mx-auto">\n' +
                        '                            <div class="ball-pulse-sync">\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                                <div class="bg-warning"></div>\n' +
                        '                            </div>\n' +
                        '                        </div>')
                });
                $('.blockUI.blockMsg.blockElement').css('width', '100%');
                $('.blockUI.blockMsg.blockElement').css('border', '0px');
                $('.blockUI.blockMsg.blockElement').css('background-color', '');

                const id = $(this).attr('data-target2')
                $.ajax({
                    url: '/Ajax/SystemUpdatesShow/' + id,
                    type: 'GET',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        $('.modal-title').html(data.version + " - " + data.title)
                        $('.modal-body').html(data.content)
                        $('.date').html(dateFormat(data.created_at))
                    }
                }).done(function () {
                    $('.modal-content').unblock();
                }).error(function (jqXHR, exception) {
                    ajaxError(jqXHR.status);
                });
            })
        })
    </script>
@endsection

@section('modals')
    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalDetails" role="dialog"
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="mt-3 text-center mb-3"> Kayıt Tarihi: <span class="date"></span></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@endsection

