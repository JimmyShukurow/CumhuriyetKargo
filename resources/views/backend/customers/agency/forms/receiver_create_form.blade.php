<div class="row">
    {{-- Gönderici START --}}
    <div class="col-md-6 border-box" id="divider-gonderici">
        <h6>Kişisel Bilgiler</h6>
        <div class="divider mt-0"></div>

        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative ">
                    <label for="selectReciverCategory">Kategori:</label><b class="text-danger">*</b>
                </div>
                <div class="input-group mb-1">
                    <select id="selectReciverCategory" class="form-control form-control-sm" name="">
                        <option value="Bireysel">Bireysel</option>
                        <option value="Kurumsal">Kurumsal</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="containerReciverIndividual">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative ">
                        <label for="receiverTCKN">Alıcı TCKN:</label>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" id="receiverTCKN" maxlength="11"
                               class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative ">
                        <label for="receiverIndividualName">Adı:</label><b class="text-danger">*</b>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" id="receiverIndividualName"
                               class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative ">
                        <label for="receiverIndividualSurname">Soyadı:</label><b
                            class="text-danger">*</b>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" id="receiverIndividualSurname"
                               class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="containerReciverCorporate">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative ">
                        <label for="receiverVKN">Alıcı VKN:</label>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" maxlength="10" id="receiverVKN"
                               class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative ">
                        <label for="receiverCompanyName">Firma Ünvanı:</label><b
                            class="text-danger">*</b>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" id="receiverCompanyName"
                               class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="position-relative ">
                        <label for="receiverAuthorizedName">Yetkili Adı:</label>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" id="receiverAuthorizedName"
                               class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative ">
                        <label for="receiverAuthorizedSurname">Yetkili Soyadı:</label>
                    </div>
                    <div class="input-group mb-1">
                        <input type="text" id="receiverAuthorizedSurname"
                               class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>


        <div class="form-row">
            <div class="col-md-6">
                <div class="position-relative ">
                    <label for="receiverGSM">Cep Telefonu:</label> <b class="text-danger">*</b>
                </div>
                <div class="input-group mb-1">
                    <input type="text" id="receiverGSM"
                           data-inputmask="'mask': '(999) 999 99 99'"
                           placeholder="(___) ___ __ __" type="text"
                           class="form-control input-mask-trigger form-control-sm">
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative ">
                    <label for="receiverPhone">Telefon:</label>
                </div>
                <div class="input-group mb-1">
                    <input type="text" id="receiverPhone"
                           data-inputmask="'mask': '(999) 999 99 99'"
                           placeholder="(___) ___ __ __" type="text"
                           class="form-control input-mask-trigger form-control-sm">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative ">
                    <label for="receiverEmail">Email:</label>
                </div>
                <div class="input-group mb-1">
                    <input type="text" id="receiverEmail"
                           data-inputmask="'alias': 'email'" type="email"
                           class="form-control input-mask-trigger form-control-sm">
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-6 border-box" id="divider-gonderici2">

        <h6>Adres Bilgileri</h6>
        <div class="divider mt-0"></div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="position-relative ">
                    <label for="receiverCity">İl:</label><b class="text-danger">*</b>
                </div>
                <div class="input-group mb-1">
                    <select name="" id="receiverCity" class="form-control form-control-sm">
                        <option value="">İl Seçiniz</option>
                        @foreach($data['cities'] as $city)
                            <option value="{{$city->id}}"
                                    id="{{$city->id}}">{{$city->city_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative ">
                    <label for="receiverDistrict">İlçe:</label><b class="text-danger">*</b>
                </div>
                <div class="input-group mb-1">
                    <select name="" id="receiverDistrict" disabled
                            class="form-control form-control-sm">
                        <option value="">İlçe Seçiniz</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative">
                    <label for="receiverNeighborhood">Mahalle/Köy:</label><b
                        class="text-danger">*</b>
                </div>
                <div class="input-group mb-1">
                    <select name="" id="receiverNeighborhood" disabled
                            class="form-control form-control-sm">
                        <option value="">Mahalle Seçiniz</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="position-relative">
                    <label for="receiverStreet">Cadde:</label><b class="text-danger">-</b>
                </div>
                <div class="input-group input-group-sm mb-1">
                    <input type="text" class="form-control" id="receiverStreet">
                    <div class="input-group-append"><span
                            class="input-group-text">CD.</span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative">
                    <label for="receiverStreet2">Sokak:</label><b class="text-danger">-</b>
                </div>
                <div class="input-group input-group-sm mb-1">
                    <input type="text" class="form-control" id="receiverStreet2">
                    <div class="input-group-append"><span
                            class="input-group-text">SK.</span></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                <div class="position-relative">
                    <label for="receiverBuildingNo">Bina No:</label><b class="text-danger">*</b>
                </div>
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend"><span
                            class="input-group-text">NO:</span></div>
                    <input type="text" class="form-control" id="receiverBuildingNo">
                </div>
            </div>

            <div class="col-md-4">
                <div class="position-relative">
                    <label for="receiverFloor">Kat No:</label><b class="text-danger">*</b>
                </div>

                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend"><span
                            class="input-group-text">KAT:</span></div>
                    <input type="text" class="form-control" id="receiverFloor">
                </div>
            </div>

            <div class="col-md-4">
                <div class="position-relative">
                    <label for="receiverDoorNo">Daire No:</label><b class="text-danger">*</b>
                </div>
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend"><span
                            class="input-group-text">D:</span></div>
                    <input type="text" class="form-control" id="receiverDoorNo">
                </div>
            </div>

        </div>

        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative">
                    <label for="receiverAdress">Adres Notu:</label>
                </div>
                <div class="input-group mb-1">
                                        <textarea name="" id="receiverAdress" cols="30" rows="3"
                                                  class="form-control"></textarea>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12 text-center">
                <button style="width: 100%" id="btnSaveReceiver"
                        class="text-center btn-icon btn-square btn btn-primary">
                    <i class="fa fa-plus btn-icon-wrapper"> </i>Alıcıyı Kaydet
                </button>
            </div>
        </div>
    </div>
    {{-- Gönderici END --}}
</div>
