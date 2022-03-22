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
                                        <div class="widget-heading">Kargo Sayısı</div>
                                        <div class="widget-subheading">(Bugün) Toplam Kesilen Kargo Adeti
                                        </div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div id="package_count"
                                             class="widget-numbers text-success">{{$daily['package_count']}}</div>
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
                                        <div class="widget-heading">Dosya Sayısı</div>
                                        <div class="widget-subheading">(Bugün) Toplam Kesilen Dosya</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div id="file_count"
                                             class="widget-numbers text-danger">{{$daily['file_count']}}</div>
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
                                        <div class="widget-heading">Toplam Kesilen Kargo-Dosya</div>
                                        <div class="widget-subheading">(Bugün) Kargo-Dosya Adeti</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div id="total_cargo_count"
                                             class="widget-numbers text-success">{{$daily['total_cargo_count']}}</div>
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
                                        <div class="widget-subheading">(Bugün) Total Desi
                                        </div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div id="total_desi"
                                             class="widget-numbers text-primary">{{$daily['total_desi']}}</div>
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
                                        <div class="widget-heading">Parça Sayısı</div>
                                        <div class="widget-subheading">(Bugün) Toplam Parça Sayısı
                                        </div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div id="total_number_of_pieces"
                                             class="widget-numbers text-warning">{{$daily['total_number_of_pieces']}}</div>
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
                                        <div class="widget-subheading">(Bugün) Toplam Cironuz
                                        </div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers text-primary" id="total_endorsement">
                                            ₺{{$daily['total_endorsement']}}</div>
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
