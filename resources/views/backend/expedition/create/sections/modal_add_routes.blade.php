{{-- Standart Modal - Add Routes --}}
<div class="modal fade" id="ModalAddRoutes" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Güzergah Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modalEnabledDisabled">
                <div id="divRouteBranchType" class="row">
                    <div class="col-md-12">
                        <div class="form-group position-relative">
                            <label class="font-weight-bold" for="routeBranchType">Birim Tipi:</label>
                            <select id="routeBranchType"
                                    class="form-control form-control-sm">
                                <option value="Aktarma"> Aktarma</option>
                                <option value="Acente">Acente</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div id="divRouteTc" class="form-group position-relative">
                            <label class="font-weight-bold" for="routeTc">Aktarma Seçin:</label>
                            <select id="routeTc"
                                    class="form-control form-control-sm">
                                <option value="">Seçiniz</option>
                                @foreach($tc as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name}} TRM.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div style="display: none;" id="divRouteAgency" class="form-group position-relative">
                            <label class="font-weight-bold" for="routeAgency">Acente Seçin:</label>
                            <select style="width: 100%;" id="routeAgency"
                                    class="form-control agency-select form-control-sm">
                                <option value="">Seçiniz</option>
                                @foreach($agencies as $key)
                                    <option value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button id="btnAddRoute" type="button" class="btn btn-primary">Ekle</button>
            </div>
        </div>
    </div>
</div>
