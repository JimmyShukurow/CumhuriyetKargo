@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush

@section('title', 'Dağıtım Alanlarım (Acente #'.$agency->agency_code . ' - ' .$agency->agency_name . ' ŞUBE)')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-map icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Dağıtım Alanlarım <b>({{'#'.$agency->agency_code . ' - ' .$agency->agency_name . ' ŞUBE'}})</b>
                        <div class="page-title-subheading">Bu sayfa üzerinden şubenize ait dağıtım alanlarınızı
                            görüntüleyebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-map mr-3 text-muted opacity-6"> </i>
                    <b>{{'#'.$agency->agency_code . ' - ' .$agency->agency_name }} ŞUBE </b>
                    &nbsp;DAĞITIM ALANLARI
                </div>
                <div class="btn-actions-pane-right actions-icon-btn">
                    <div class="btn-group dropdown">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i
                                class="pe-7s-menu btn-icon-wrapper"></i></button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">

                            <div class="p-3 text-right">
                                <button id="btnClearFilter" class="mr-2 btn-shadow btn-sm btn btn-link">Filtreyi
                                    Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mb-3">
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Bölge Tipi</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $key)
                        <tr>
                            <td>{{$key->city}}</td>
                            <td>{{$key->district}}</td>
                            <td>{{$key->neighborhood}}</td>
                            <td>{{$key->area_type}}</td>
                            <td>{{$key->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Bölge Tipi</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                Dağıtım alanlarınız ile ilgili bir problem olması durumunda lokasyon
                birimimize <a href="{{route('systemSupport.NewTicket')}}">buraya</a> tıklayarak ticket üzerinden
                ulaşabilirsiniz.
            </div>
        </div>

        {{--Statistics--}}
        <div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/agency-location-summery.js"></script>
@endsection

