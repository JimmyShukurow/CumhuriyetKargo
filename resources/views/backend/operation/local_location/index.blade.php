@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush

@section('title', 'Mahalli Lokasyon')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas lnr-map icon-gradient bg-plum-plate"></i>
                    </div>
                    <div> Mahalli Lokasyon
                        <div class="page-title-subheading">Türkiye geneli acentelerin dağıtım alanlarını bu modül
                            üzerinden görüntüleyebilir, düzenleyenilirsiniz. (Filtreleme işlemi sadece acente seçilerek
                            yapılabilir.)
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    {{--                    <div class="d-inline-block dropdown">--}}
                    {{--                        <a href="{{ route('SenderCurrents.create') }}">--}}
                    {{--                            <button type="button" aria-haspopup="true" aria-expanded="false"--}}
                    {{--                                    class="btn-shadow btn btn-info">--}}
                    {{--						<span class="btn-icon-wrapper pr-2 opacity-7">--}}
                    {{--							<i class="fa fa-plus fa-w-20"></i>--}}
                    {{--						</span>--}}
                    {{--                                Yeni Cari Oluştur--}}
                    {{--                            </button>--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-map mr-3 text-muted opacity-6"> </i>Mahalli Lokasyon
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
            <div class="card-body">
                <form id="search-form">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="position-relative form-control-sm form-group">
                                <label for="link" class="">İl:</label>
                                <select name="city" id="city"
                                        class="form-control niko-select-filter form-control-sm">
                                    <option value="">İl Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option id="{{$city->id}}" data="{{$city->city_name}}"
                                                value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="district" class="">İlçe*</label>
                                <select name="district" id="district"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">İlçe Seçiniz</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="agency">Acente</label>
                            <select name="agency" id="selectAgency"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="button" style="width: 100%;" id="btnGiveNeighborhood"
                                    class="mt-4 btn-icon btn-shadow btn-outline-2x btn btn-outline-alternate">
                                <i class="lnr-plus-circle btn-icon-wrapper"></i>Acenteye Bağlanacak Mahalleler
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-bordered table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Bölge Tipi</th>
                        <th>Uzaklık</th>
                        <th>Dağıtan</th>
                        <th>İşlem</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Bölge Tipi</th>
                        <th>Uzaklık</th>
                        <th>Dağıtan</th>
                        <th>İşlem</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/operation/local-location/general-location.js"></script>

@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade" id="ModalCityDistrictNeighborhoods" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalCityDistrictsTitle">Mahalle Seçin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyTCDistricts" class="modal-body">
                    <p class="mb-0">Aktarmaya bağlanacak il ve ilçeleri aşağıdan seçebilirsiniz.</p>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <select name="cityX" id="cityX" required class="form-control form-control-sm">
                                    <option value="">İl Seçiniz</option>
                                    @foreach ($data['cities'] as $city)
                                        <option {{ old('city') == $city->id ? 'selected' : '' }} id="{{ $city->id }}"
                                                value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <select name="districtX" id="districtX" required class="form-control form-control-sm">
                                    <option value="">İlçe Seçiniz</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <table style="margin-bottom:  0;" class="table table-striped">
                        <thead>
                        <th title="Ana Bölge" style="width: 50px;padding:0">
                            (AB) <input style="width: 20px;margin-left: 7px;" type="checkbox" id="cb-select-all-ab"
                                        class="select-all-cb-ab form-control">
                        </th>
                        <th title="Mobil Bölge" style="width: 50px;padding:0">
                            (MB) <input style="width: 20px;margin-left: 7px;" type="checkbox" id="cb-select-all-mb"
                                        class="select-all-cb-mb form-control">
                        </th>
                        <th style="vertical-align: middle;">Mahalle</th>
                        </thead>
                    </table>
                    <div style="max-height: 50vh; overflow-y: scroll;">
                        <table id="TableRegionalDistricts" class="table table-striped">
                            <tbody id="TbodyNeighborhoods"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">İptal Et</button>
                    <button type="button" class="btn btn-primary" id="SaveBtn">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Location Modal -->
    <div class="modal fade" id="ModalEditLocation" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalCityDistrictsTitle">Mahalle Düzenleyin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyTCDistricts" class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="editLocationCity">İl:</label>
                                <input type="text" id="editLocationCity" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="editLocationDistrict">İlçe:</label>
                                <input type="text" id="editLocationDistrict" class="form-control form-control-sm"
                                       readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="editLocationNeighborhood">Mahalle:</label>
                                <input type="text" id="editLocationNeighborhood" class="form-control form-control-sm"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="editLocationAgency">Şube:</label>
                                <select style="width: 100%;" name="" id="editLocationAgency"
                                        class="form-control form-control-sm">
                                    @foreach($data['agencies'] as $key)
                                        <option
                                            value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="editLocationDistance">Mesafe (KM):</label>
                                <input type="text" id="editLocationDistance" class="form-control form-control-sm">
                                <small class="text-danger">Zorunlu Alan</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="editLocationAreaType">Bölge Tipi:</label>
                                <select name="" class="form-control-sm form-control" id="editLocationAreaType">
                                    <option value="AB">AB</option>
                                    <option value="MB">MB</option>
                                </select>
                            </div>
                        </div>


                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">İptal Et</button>
                    <button type="button" class="btn btn-primary" id="saveEditLocation">Kaydet</button>
                </div>
            </div>
        </div>
    </div>


@endsection
