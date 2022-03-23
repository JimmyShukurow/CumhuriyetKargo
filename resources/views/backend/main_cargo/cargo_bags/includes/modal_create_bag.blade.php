<!-- Large modal => Modal Create Bag -->
<div class="modal fade bd-example-modal-lg" id="modalCreateBag" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Çuval & Torba Oluştur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-y: auto; max-height: 75vh;" id="modalBodyCreateBag" class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="bag_type">Tip:</label>
                            <select name="" id="bag_type" class="font-weight-bold form-control form-control-sm">
                                <option value="">Seçiniz</option>
                                <option value="Torba">Torba</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Çıkış</label>
                            <input type="text" class="form-control form-control-sm text-primary font-weight-bold"
                                   readonly value="{{$departurePoint}}">
                        </div>
                    </div>

                    @if(Auth::user()->user_type == 'Acente')
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Varış</label>
                                <input type="text" class="form-control form-control-sm text-primary font-weight-bold"
                                       readonly value="{{$arrivalPoint}}">
                            </div>
                        </div>
                    @elseif(Auth::user()->user_type == 'Aktarma')

                        <div class="col-md-12">
                            <div class="form-group position-relative">
                                <label class="font-weight-bold" for="arrivalBranchType">Varış Birim Tipi:</label>
                                <select name="" id="arrivalBranchType"
                                        class="form-control form-control-sm">
                                    <option value="Aktarma">Aktarma</option>
                                    <option value="Acente">Acente</option>
                                </select>
                            </div>
                        </div>

                        <div id="divArrivalTc" class="col-md-12">
                            <div class="form-group position-relative">
                                <label class="font-weight-bold" for="arrivalTc">Varış Aktarma Seçin:</label>
                                <select name="" id="arrivalTc"
                                        class="form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['tc'] as $key)
                                        <option value="{{$key->id}}">{{$key->tc_name}} TRM.</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="display: none;" id="divArrivalAgency" class="col-md-12">
                            <div class="form-group position-relative">
                                <label class="font-weight-bold" for="arrivalAgency">Acente Seçin:</label>
                                <select style="width: 100%;" name="" id="arrivalAgency"
                                        class="form-control agency-select form-control-sm">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['agencies'] as $key)
                                        <option value="{{$key->id}}">{{$key->agency_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                <button id="btnInsertBag" type="button" class="btn btn-primary">Oluştur
                </button>
            </div>
        </div>
    </div>
</div>
