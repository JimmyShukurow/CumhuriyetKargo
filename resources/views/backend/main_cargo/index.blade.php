@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush



@section('title', 'Ana Menü')
@section('content')

    <div class="app-main__inner">

        <div class="tabs-animation">
            <div class="card no-shadow bg-transparent no-border rm-borders mb-3">
                <div class="card">
                    <div class="no-gutters row">
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Paket Sayısı</div>
                                                    <div class="widget-subheading">Toplan Kesilen Koli Adeti</div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-success">29</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Desi</div>
                                                    <div class="widget-subheading">Total Desi
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-primary">954</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Dosya Sayısı</div>
                                                    <div class="widget-subheading">Toplam Kesilen Dosya</div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-danger">25</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Parça Sayısı</div>
                                                    <div class="widget-subheading">Toplam Parça Sayısı
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-warning">92</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Toplam Kesilen Kargo</div>
                                                    <div class="widget-subheading">Kargo Adeti</div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-success">41</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Ciro</div>
                                                    <div class="widget-subheading">Bugünkü Toplam Cironuz
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-primary">₺6,758.42</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: 0" class="col-sm-12 col-lg-12">
                <div class="mb-3 card">
                    <div class="p-0 d-block card-footer">
                        <div class="grid-menu grid-menu-3col">
                            <div class="no-gutters row">

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-sync text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Yenile
                                    </button>
                                </div>

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <a style="text-decoration: none;" href="{{route('mainCargo.newCargo')}}">
                                        <button id="btnNewCargo"
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                                            <i class="lnr-plus-circle text-info opacity-7 btn-icon-wrapper mb-2"> </i>
                                            Yeni Kargo
                                        </button>
                                    </a>
                                </div>

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
                                        <i class="fas fa-file-excel text-warning opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Excele Aktar
                                    </button>
                                </div>

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                        <i class="lnr-printer text-alternate opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Yazdır
                                    </button>
                                </div>
                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-pencil text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Kargo Düzenle
                                    </button>
                                </div>
                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                        <i class="lnr-cross text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Kargo İptal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-3">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                            class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i>Kestiğiniz Kargolar
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
                    <form method="POST" id="search-form">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="currentCode">Başlangıç Tarih:</label>
                                <input name="name_surname" id="currentCode"
                                       data-inputmask="'mask': '999 999 999'"
                                       placeholder="___ ___ ___" type="text"
                                       class="form-control input-mask-trigger form-control-sm niko-filter">
                            </div>

                            <div class="col-md-2">
                                <label for="category">Bitiş Tarihi:</label>
                                <select name="agency" id="category"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="Kurumsal">Kurumsal</option>
                                    <option value="Bireysel">Bireysel</option>
                                    <option value="-1">Tümü</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="record">Kayıt</label>
                                <select name="record" id="record"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="1">Kayıt</option>
                                    <option value="0">Arşiv</option>
                                </select>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">

                    <table style="white-space: nowrap;" id="AgenciesTable"
                           class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                        <thead>
                        <tr>
                            <th>KTNO</th>
                            <th>Gönderici Adı</th>
                            <th>Gönderici İl</th>
                            <th>Alıcı Adı</th>
                            <th>Alıcı İl</th>
                            <th>Alıcı İlçe</th>
                            <th>Alıcı Adres</th>
                            <th>Ödeme Tipi</th>
                            <th>Ücret</th>
                            <th>Tahsilat Tipi</th>
                            <th>Tahilatlı</th>
                            <th>Fatura Tutarı</th>
                            <th>Statü</th>
                            <th>Durum</th>
                            <th>Oluşturan</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlem</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>KTNO</th>
                            <th>Gönderici Adı</th>
                            <th>Gönderici İl</th>
                            <th>Alıcı Adı</th>
                            <th>Alıcı İl</th>
                            <th>Alıcı İlçe</th>
                            <th>Alıcı Adres</th>
                            <th>Ödeme Tipi</th>
                            <th>Ücret</th>
                            <th>Tahsilat Tipi</th>
                            <th>Tahilatlı</th>
                            <th>Fatura Tutarı</th>
                            <th>Statü</th>
                            <th>Durum</th>
                            <th>Oluşturan</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlem</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>

            {{--Old data table--}}
            <div>
                {{--    <div class="card mb-3">--}}
                {{--        <div class="card-header-tab card-header">--}}
                {{--            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i--}}
                {{--                    class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Kestiğiniz Kargolar--}}
                {{--            </div>--}}
                {{--            <div class="btn-actions-pane-right actions-icon-btn">--}}
                {{--                <div class="btn-group dropdown">--}}
                {{--                    <button type="button" data-toggle="dropdown" aria-haspopup="true"--}}
                {{--                            aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i--}}
                {{--                            class="pe-7s-menu btn-icon-wrapper"></i></button>--}}
                {{--                    <div tabindex="-1" role="menu" aria-hidden="true"--}}
                {{--                         class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">--}}
                {{--                        <h6 tabindex="-1" class="dropdown-header">Header</h6>--}}
                {{--                        <button type="button" tabindex="0" class="dropdown-item"><i--}}
                {{--                                class="dropdown-icon lnr-inbox"> </i><span>Menus</span></button>--}}
                {{--                        <button type="button" tabindex="0" class="dropdown-item"><i--}}
                {{--                                class="dropdown-icon lnr-file-empty">--}}
                {{--                            </i><span>Settings</span></button>--}}
                {{--                        <button type="button" tabindex="0" class="dropdown-item"><i--}}
                {{--                                class="dropdown-icon lnr-book"> </i><span>Actions</span></button>--}}
                {{--                        <div tabindex="-1" class="dropdown-divider"></div>--}}
                {{--                        <div class="p-3 text-right">--}}
                {{--                            <button class="mr-2 btn-shadow btn-sm btn btn-link">View--}}
                {{--                                Details--}}
                {{--                            </button>--}}
                {{--                            <button class="mr-2 btn-shadow btn-sm btn btn-primary">Action</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--        </div>--}}
                {{--        <div class="card-body">--}}
                {{--            <table style="width: 100%;" id="example"--}}
                {{--                   class="TableNoPadding table table-hover table-striped table-bordered">--}}
                {{--                <thead>--}}
                {{--                <tr>--}}
                {{--                    <th>Name</th>--}}
                {{--                    <th>Position</th>--}}
                {{--                    <th>Office</th>--}}
                {{--                    <th>Age</th>--}}
                {{--                    <th>Start date</th>--}}
                {{--                    <th>Salary</th>--}}
                {{--                </tr>--}}
                {{--                </thead>--}}
                {{--                <tbody>--}}
                {{--                <tr>--}}
                {{--                    <td>Tiger Nixon</td>--}}
                {{--                    <td>System Architect</td>--}}
                {{--                    <td>Edinburgh</td>--}}
                {{--                    <td>61</td>--}}
                {{--                    <td>2011/04/25</td>--}}
                {{--                    <td>$320,800</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Garrett Winters</td>--}}
                {{--                    <td>Accountant</td>--}}
                {{--                    <td>Tokyo</td>--}}
                {{--                    <td>63</td>--}}
                {{--                    <td>2011/07/25</td>--}}
                {{--                    <td>$170,750</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Ashton Cox</td>--}}
                {{--                    <td>Junior Technical Author</td>--}}
                {{--                    <td>San Francisco</td>--}}
                {{--                    <td>66</td>--}}
                {{--                    <td>2009/01/12</td>--}}
                {{--                    <td>$86,000</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Cedric Kelly</td>--}}
                {{--                    <td>Senior Javascript Developer</td>--}}
                {{--                    <td>Edinburgh</td>--}}
                {{--                    <td>22</td>--}}
                {{--                    <td>2012/03/29</td>--}}
                {{--                    <td>$433,060</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Airi Satou</td>--}}
                {{--                    <td>Accountant</td>--}}
                {{--                    <td>Tokyo</td>--}}
                {{--                    <td>33</td>--}}
                {{--                    <td>2008/11/28</td>--}}
                {{--                    <td>$162,700</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Brielle Williamson</td>--}}
                {{--                    <td>Integration Specialist</td>--}}
                {{--                    <td>New York</td>--}}
                {{--                    <td>61</td>--}}
                {{--                    <td>2012/12/02</td>--}}
                {{--                    <td>$372,000</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Herrod Chandler</td>--}}
                {{--                    <td>Sales Assistant</td>--}}
                {{--                    <td>San Francisco</td>--}}
                {{--                    <td>59</td>--}}
                {{--                    <td>2012/08/06</td>--}}
                {{--                    <td>$137,500</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Rhona Davidson</td>--}}
                {{--                    <td>Integration Specialist</td>--}}
                {{--                    <td>Tokyo</td>--}}
                {{--                    <td>55</td>--}}
                {{--                    <td>2010/10/14</td>--}}
                {{--                    <td>$327,900</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Colleen Hurst</td>--}}
                {{--                    <td>Javascript Developer</td>--}}
                {{--                    <td>San Francisco</td>--}}
                {{--                    <td>39</td>--}}
                {{--                    <td>2009/09/15</td>--}}
                {{--                    <td>$205,500</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Sonya Frost</td>--}}
                {{--                    <td>Software Engineer</td>--}}
                {{--                    <td>Edinburgh</td>--}}
                {{--                    <td>23</td>--}}
                {{--                    <td>2008/12/13</td>--}}
                {{--                    <td>$103,600</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Jena Gaines</td>--}}
                {{--                    <td>Office Manager</td>--}}
                {{--                    <td>London</td>--}}
                {{--                    <td>30</td>--}}
                {{--                    <td>2008/12/19</td>--}}
                {{--                    <td>$90,560</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Quinn Flynn</td>--}}
                {{--                    <td>Support Lead</td>--}}
                {{--                    <td>Edinburgh</td>--}}
                {{--                    <td>22</td>--}}
                {{--                    <td>2013/03/03</td>--}}
                {{--                    <td>$342,000</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Charde Marshall</td>--}}
                {{--                    <td>Regional Director</td>--}}
                {{--                    <td>San Francisco</td>--}}
                {{--                    <td>36</td>--}}
                {{--                    <td>2008/10/16</td>--}}
                {{--                    <td>$470,600</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Haley Kennedy</td>--}}
                {{--                    <td>Senior Marketing Designer</td>--}}
                {{--                    <td>London</td>--}}
                {{--                    <td>43</td>--}}
                {{--                    <td>2012/12/18</td>--}}
                {{--                    <td>$313,500</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Tatyana Fitzpatrick</td>--}}
                {{--                    <td>Regional Director</td>--}}
                {{--                    <td>London</td>--}}
                {{--                    <td>19</td>--}}
                {{--                    <td>2010/03/17</td>--}}
                {{--                    <td>$385,750</td>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td>Michael Silva</td>--}}
                {{--                    <td>Marketing Designer</td>--}}
                {{--                    <td>London</td>--}}
                {{--                    <td>66</td>--}}
                {{--                    <td>2012/11/27</td>--}}
                {{--                    <td>$198,500</td>--}}
                {{--                </tr>--}}

                {{--                </tbody>--}}
                {{--                <tfoot>--}}
                {{--                <tr>--}}
                {{--                    <th>Name</th>--}}
                {{--                    <th>Position</th>--}}
                {{--                    <th>Office</th>--}}
                {{--                    <th>Age</th>--}}
                {{--                    <th>Start date</th>--}}
                {{--                    <th>Salary</th>--}}
                {{--                </tr>--}}
                {{--                </tfoot>--}}
                {{--            </table>--}}
                {{--        </div>--}}
                {{--    </div>--}}
            </div>


            {{--Statistics--}}
            <div>
                {{--            <div class="row">--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>874</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">sales last month</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-1"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>1283</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">sales Income</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-2"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-warning">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>1286</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">last month sales</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-3"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>564</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">total revenue</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-4"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>
            {{-- HERE --}}
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">

    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>

    <script>

        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('#agency').select2();
            $('#creatorUser').select2();

            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    6, 'desc'
                ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
                    "sSearch": "",
                    "sZeroRecords": "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "Önceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                    },
                    "select": {
                        "rows": {
                            "_": "%d kayıt seçildi",
                            "0": "",
                            "1": "1 kayıt seçildi"
                        }
                    }
                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',

                select: {
                    style: 'multi',
                    selector: 'td:nth-child(2)'
                },
                buttons: [
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        title: "CK - Kesilen Kargolar"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Sütun Görünüm'
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('mainCargo.getCargoes') !!}',
                    data: function (d) {
                        d.name = $('#name').val();
                        d.agency = $('#agency').val();
                        d.currentCode = $('#currentCode').val();
                        d.status = $('#status').val();
                        d.creatorUser = $('#creatorUser').val();
                        d.category = $('#category').val();
                        d.record = $('#record').val();
                        d.confirmed = $('#confirmed').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    },
                    complete: function () {
                        ToastMessage('info', 'Tamamlandı!', 'Bilgi');
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'sender_city', name: 'sender_city'},
                    {data: 'receiver_name', name: 'receiver_name'},
                    {data: 'receiver_city', name: 'receiver_city'},
                    {data: 'receiver_district', name: 'receiver_district'},
                    {data: 'receiver_address', name: 'receiver_address'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'collectible', name: 'collectible'},
                    {data: 'collectible', name: 'collectible'},
                    {data: 'collection_fee', name: 'collection_fee'},
                    {data: 'status', name: 'status'},
                    {data: 'status_for_human', name: 'status_for_human'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                ],

            });
        });

        function drawDT() {
            oTable.draw();
        }

        $('.niko-select-filter').change(delay(function (e) {
            drawDT();
        }, 1000));

        $('.niko-filter').keyup(delay(function (e) {
            drawDT();
        }, 1000));
        $('#btnClearFilter').click(function () {
            $('#search-form').trigger("reset");
            $('#select2-creatorUser-container').text('Seçiniz');
            $('#select2-agency-container').text('Seçiniz');
            drawDT();
        });

        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }


        $(document).on('click', '.user-detail', function () {
            $('#ModalUserDetail').modal();

            $('#ModalBodyUserDetail.modal-body').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-grid-pulse">\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            detailsID = $(this).prop('id');
            userInfo($(this).prop('id'));
        });

        var array = new Array();

        function userInfo(user) {
            $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: user
                },
                cache: false
            }).done(function (response) {
                let currentStatus, currentConfirmed;
                let creatorDisplayName = '<span class="text-primary font-weight-bold">(' + response.current.creator_display_name + ')</span>';

                if (response.current.status == "1")
                    currentStatus = '<span class="text-success">(Aktif Hesap)</span>';
                else
                    currentStatus = '<span class="text-danger">(Pasif Hesap)</span>';

                if (response.current.confirmed == "1") {
                    currentConfirmed = '<span class="text-success font-weight-bold">Onaylandı</span>';
                    $('#divConfirmCurrent').hide();
                } else {
                    currentConfirmed = '<span class="text-danger font-weight-bold">Onay Bekliyor</span>';
                    $('#divConfirmCurrent').show();
                }

                let city = response.current.city + "/",
                    district = response.current.district + " ",
                    neighborhood = response.current.neighborhood + " ",
                    street = response.current.street != '' && response.current.street != null ? response.current.street + " CAD. " : "",
                    street2 = response.current.street2 != '' && response.current.street2 != null ? response.current.street2 + " SK. " : "",
                    buildingNo = "NO:" + response.current.building_no + " ",
                    door = "D:" + response.current.door_no + " ",
                    floor = "KAT:" + response.current.floor + " ",
                    addressNote = "(" + response.current.address_note + ")";

                let fullAddress = neighborhood + street + street2 + buildingNo + floor + door + addressNote;


                $('#agencyName').html(response.current.name);
                $('#agencyCityDistrict').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
                $('#titleBranch').html(response.current.name + ' - ÖZET ' + currentStatus);

                $('#currentCategory').html(response.current.category);
                $('#modalCurrentCode').html(response.current.current_code);
                $('#nameSurnameCompany').html(response.current.name);
                $('#currentAgency').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
                $('#taxOffice').html(response.current.tax_administration);
                $('#tcknVkn').html(response.current.tckn);
                $('#phone').html(response.current.phone);
                $('#cityDistrict').html(response.current.city + "/" + response.current.district);
                $('#address').html(fullAddress);
                $('#gsm').html(response.current.gsm);
                $('#gsm2').html(response.current.gsm2);
                $('#phone2').html(response.current.phone2);
                $('#email').html(response.current.email);
                $('#website').html(response.current.website);
                $('#regDate').html(response.current.created_at);
                $('#dispatchCityDistrict').html(response.current.dispatch_city + "/" + response.current.dispatch_district);
                $('#dispatchAddress').html(response.current.dispatch_adress);
                $('#iban').html(response.current.iban);
                $('#bankOwner').html(response.current.bank_owner_name);
                $('#contractStartDate').html(response.current.contract_start_date);
                $('#contractEndDate').html(response.current.contract_end_date);
                $('#reference').html(response.current.reference);
                $('#currentCreatorUser').html(response.current.creator_user_name + " " + creatorDisplayName);
                $('#currentFilePrice').html(response.price.file_price + "₺");
                $('#current1_5Desi').html(response.price.d_1_5 + "₺");
                $('#current6_10Desi').html(response.price.d_6_10 + "₺");
                $('#current11_15Desi').html(response.price.d_11_15 + "₺");
                $('#current16_20Desi').html(response.price.d_16_20 + "₺");
                $('#current21_25Desi').html(response.price.d_21_25 + "₺");
                $('#current26_30Desi').html(response.price.d_26_30 + "₺");
                $('#currentAmountOfIncrease').html(response.price.amount_of_increase + "₺");
                $('#currentCollectPrice').html(response.price.collect_price + "₺");
                $('#collectAmountOfIncrease').html("%" + response.price.collect_amount_of_increase);
                $('#currentConfirmed').html(currentConfirmed);

                $('.modal-body').unblock();
                return false;
            });

            $('#ModalAgencyDetail').modal();
        }

        $(document).on('click', '#btnConfirmCurrent', function () {
            $('#btnConfirmCurrent').prop('disabled', true);

            $.ajax('/SenderCurrents/AjaxTransaction/ConfirmCurrent', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                if (response.status == -1)
                    ToastMessage('error', response.message, '');
                else if (response.status == 1) {
                    ToastMessage('success', 'İşlem başarılı, cari hesabı onaylandı!', 'İşlem Başarılı!');
                    userInfo(detailsID);
                    $('#divConfirmCurrent').hide();
                }


                $('.modalEnabledDisabled.modal-body').unblock();
            }).error(function (jqXHR, response) {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            }).always(function () {
                $('#btnConfirmCurrent').prop('disabled', false);
            });

        });

        $(document).on('click', '#btnEnabledDisabled', function () {
            // alert(detailsID);
            $('#modalEnabledDisabled').modal();

            $('#modalBodyEnabledDisabled.modalEnabledDisabled.modal-body').block({
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


            $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                $('#userNameSurname').val(response.current.name);
                $('#accountStatus').val(response.current.status);

                $('.modalEnabledDisabled.modal-body').unblock();
            });
        });

        $(document).on('click', '#btnSaveStatus', function () {

            ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
            $.ajax('/SenderCurrents/AjaxTransaction/ChangeStatus', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID,
                    status: $('#accountStatus').val(),
                }
            }).done(function (response) {
                if (response.status == 1) {

                    $('#ModalBodyUserDetail.modal-body').block({
                        message: $('<div class="loader mx-auto">\n' +
                            '                            <div class="ball-grid-pulse">\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                            </div>\n' +
                            '                        </div>')
                    });
                    $('.blockUI.blockMsg.blockElement').css('width', '100%');
                    $('.blockUI.blockMsg.blockElement').css('border', '0px');
                    $('.blockUI.blockMsg.blockElement').css('background-color', '');

                    userInfo(detailsID);
                    ToastMessage('success', 'Değişiklikler başarıyla kaydedildi.', 'İşlem Başarılı!');
                    $('#modalEnabledDisabled').modal('toggle');
                } else if (response.status == 0) {
                    ToastMessage('error', response.description, 'Hata!');
                } else if (response.status == -1) {
                    response.errors.status.forEach(key =>
                        ToastMessage('error', key, 'Hata!')
                    );
                }

                return false;
            }).error(function (jqXHR, response) {

                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            });
        });
        $(document).on('click', '#btnCurrentPerformanceReport', function () {
            ToastMessage('warning', 'Cari performans raporu çok yakında!', 'Bilgi');
        });

        $(document).on('click', '#btnPrintModal', function () {
            printWindow('#ModalBodyUserDetail', "CK - " + $('#agencyName').text());
        });

    </script>
@endsection


@section('modals')

@endsection
