{{-- Large modal  => New Sender (Current) --}}
<div class="modal fade bd-example-modal-lg" id="modalNewCurrent" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Yeni Gönderici Oluştur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    {{-- CARD START --}}

                    <div class="row">
                        {{-- Gönderici START --}}
                        <div class="col-md-4 border-box" id="divider-gonderici">

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="currentSelectCategory">Kategori:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select id="currentSelectCategory" class="form-control" readonly="" name="">
                                            <option value="Bireysel">Bireysel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="currentTckn">Gönderici TCKN:</label> <b
                                            class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentTckn" maxlength="11"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="currentName">Adı:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentName" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="currentSurName">Soyadı:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentSurName" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="currentYearOfBirth">Doğum Yılı:</label><b
                                            class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentYearOfBirth"
                                               data-inputmask="'mask': '9999'"
                                               placeholder="____" type="text"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button id="btnTCConfirm" type="button" style="width: 100%"
                                            class="text-center btn-icon btn-square btn btn-danger">
                                        <i class="fa fa-check btn-icon-wrapper"> </i>Doğrula
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 border-box" id="divider-gonderici2">

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="currentGSM">Cep Telefonu:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentGSM"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="currentPhone">Telefon:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentPhone"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="currentEmail">Email:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="currentEmail"
                                               data-inputmask="'alias': 'email'" type="email"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="currentCity">İl:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="" id="currentCity" class="form-control form-control-sm">
                                            <option value="">İl Seçiniz</option>
                                            @foreach($data['cities'] as $city)
                                                <option
                                                    {{$data['user_city'] == $city->city_name ? 'selected' : ''}}
                                                    value="{{$city->id}}">{{$city->city_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="currentDistrict">İlçe:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="" id="currentDistrict" class="form-control form-control-sm">
                                            <option value="">İlçe Seçiniz</option>
                                            @foreach($data['districts'] as $district)
                                                <option
                                                    {{$data['user_district'] == $district->district_name ? 'selected' : ''}}
                                                    value="{{$district->district_id}}">{{$district->district_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="currentDistrict">Mahalle/Köy:</label><b
                                            class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="" id="currentNeighborhood"
                                                class="form-control form-control-sm">
                                            <option value="">Mahalle Seçiniz</option>

                                            @foreach($data['neighborhoods'] as $key)
                                                <option
                                                    {{$data['user_neighborhood'] == $key->neighborhood_name ? 'selected' : ''}}
                                                    value="{{$key->neighborhood_id}}">{{$key->neighborhood_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="currentStreet">Cadde:</label><b class="text-danger">-</b>
                                    </div>

                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" name="cadde"
                                               id="currentStreet">
                                        <div class="input-group-append"><span
                                                class="input-group-text">CD.</span></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="currentStreet2">Sokak:</label><b class="text-danger">-</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" name="sokak" id="currentStreet2">
                                        <div class="input-group-append"><span
                                                class="input-group-text">SK.</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="currentBuildingNo">Bina No:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">NO:</span></div>
                                        <input type="text" class="form-control" id="currentBuildingNo"
                                               name="bina_no">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="currentFloor">Kat No:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">KAT:</span></div>
                                        <input type="text" class="form-control" name="kat_no" id="currentFloor">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="currentDoorNo">Daire No:</label><b class="text-danger">*</b>
                                    </div>

                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">D:</span></div>
                                        <input type="text" class="form-control" id="currentDoorNo" name="daire_no">
                                    </div>

                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="currentAdressNote">Adres Notu:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <textarea name="" id="currentAdressNote" cols="30" rows="2"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 text-center">
                                    <button style="width: 100%" type="button" disabled id="btnSaveCurrent"
                                            class="text-center btn-icon btn-square btn btn-primary">
                                        <i class="fa fa-plus btn-icon-wrapper"> </i>Gönderici Kaydet
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- Gönderici END --}}
                    </div>
                </div>
                <div style="display: block; padding-bottom: 3rem;" class="modal-footer">
                    <button style="float: left !important;" type="reset" class="btn btn-secondary float-left">Formu
                        Temizle
                    </button>
                    <b style="float: left;"> <b class="text-danger">*</b> ile belirtilen alanlar zorunludur.</b>

                    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Kapat</button>
                </div>
            </form>
        </div>
    </div>
</div>
