@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
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


            <div class="card mb-3">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                            class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Kestiğiniz Kargolar
                    </div>
                    <div class="btn-actions-pane-right actions-icon-btn">
                        <div class="btn-group dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i
                                    class="pe-7s-menu btn-icon-wrapper"></i></button>
                            <div tabindex="-1" role="menu" aria-hidden="true"
                                 class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
                                <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                <button type="button" tabindex="0" class="dropdown-item"><i
                                        class="dropdown-icon lnr-inbox"> </i><span>Menus</span></button>
                                <button type="button" tabindex="0" class="dropdown-item"><i
                                        class="dropdown-icon lnr-file-empty">
                                    </i><span>Settings</span></button>
                                <button type="button" tabindex="0" class="dropdown-item"><i
                                        class="dropdown-icon lnr-book"> </i><span>Actions</span></button>
                                <div tabindex="-1" class="dropdown-divider"></div>
                                <div class="p-3 text-right">
                                    <button class="mr-2 btn-shadow btn-sm btn btn-link">View
                                        Details
                                    </button>
                                    <button class="mr-2 btn-shadow btn-sm btn btn-primary">Action</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table style="width: 100%;" id="example"
                           class="TableNoPadding table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                        </tr>
                        <tr>
                            <td>Cedric Kelly</td>
                            <td>Senior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2012/03/29</td>
                            <td>$433,060</td>
                        </tr>
                        <tr>
                            <td>Airi Satou</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>33</td>
                            <td>2008/11/28</td>
                            <td>$162,700</td>
                        </tr>
                        <tr>
                            <td>Brielle Williamson</td>
                            <td>Integration Specialist</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2012/12/02</td>
                            <td>$372,000</td>
                        </tr>
                        <tr>
                            <td>Herrod Chandler</td>
                            <td>Sales Assistant</td>
                            <td>San Francisco</td>
                            <td>59</td>
                            <td>2012/08/06</td>
                            <td>$137,500</td>
                        </tr>
                        <tr>
                            <td>Rhona Davidson</td>
                            <td>Integration Specialist</td>
                            <td>Tokyo</td>
                            <td>55</td>
                            <td>2010/10/14</td>
                            <td>$327,900</td>
                        </tr>
                        <tr>
                            <td>Colleen Hurst</td>
                            <td>Javascript Developer</td>
                            <td>San Francisco</td>
                            <td>39</td>
                            <td>2009/09/15</td>
                            <td>$205,500</td>
                        </tr>
                        <tr>
                            <td>Sonya Frost</td>
                            <td>Software Engineer</td>
                            <td>Edinburgh</td>
                            <td>23</td>
                            <td>2008/12/13</td>
                            <td>$103,600</td>
                        </tr>
                        <tr>
                            <td>Jena Gaines</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>30</td>
                            <td>2008/12/19</td>
                            <td>$90,560</td>
                        </tr>
                        <tr>
                            <td>Quinn Flynn</td>
                            <td>Support Lead</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2013/03/03</td>
                            <td>$342,000</td>
                        </tr>
                        <tr>
                            <td>Charde Marshall</td>
                            <td>Regional Director</td>
                            <td>San Francisco</td>
                            <td>36</td>
                            <td>2008/10/16</td>
                            <td>$470,600</td>
                        </tr>
                        <tr>
                            <td>Haley Kennedy</td>
                            <td>Senior Marketing Designer</td>
                            <td>London</td>
                            <td>43</td>
                            <td>2012/12/18</td>
                            <td>$313,500</td>
                        </tr>
                        <tr>
                            <td>Tatyana Fitzpatrick</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>19</td>
                            <td>2010/03/17</td>
                            <td>$385,750</td>
                        </tr>
                        <tr>
                            <td>Michael Silva</td>
                            <td>Marketing Designer</td>
                            <td>London</td>
                            <td>66</td>
                            <td>2012/11/27</td>
                            <td>$198,500</td>
                        </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div
                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content pt-3 pl-3 pb-1">
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <div class="widget-chart-flex">
                                            <div class="fsize-4">
                                                <small class="opacity-5">$</small>
                                                <span>874</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="widget-subheading mb-0 opacity-5">sales last month</h6>
                            </div>
                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">
                                <div class="col-md-9">
                                    <div id="dashboard-sparklines-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div
                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content pt-3 pl-3 pb-1">
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <div class="widget-chart-flex">
                                            <div class="fsize-4">
                                                <small class="opacity-5">$</small>
                                                <span>1283</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="widget-subheading mb-0 opacity-5">sales Income</h6>
                            </div>
                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">
                                <div class="col-md-9">
                                    <div id="dashboard-sparklines-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div
                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-warning">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content pt-3 pl-3 pb-1">
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <div class="widget-chart-flex">
                                            <div class="fsize-4">
                                                <small class="opacity-5">$</small>
                                                <span>1286</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="widget-subheading mb-0 opacity-5">last month sales</h6>
                            </div>
                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">
                                <div class="col-md-9">
                                    <div id="dashboard-sparklines-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div
                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content pt-3 pl-3 pb-1">
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <div class="widget-chart-flex">
                                            <div class="fsize-4">
                                                <small class="opacity-5">$</small>
                                                <span>564</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="widget-subheading mb-0 opacity-5">total revenue</h6>
                            </div>
                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">
                                <div class="col-md-9">
                                    <div id="dashboard-sparklines-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- HERE --}}
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('modals')

@endsection
