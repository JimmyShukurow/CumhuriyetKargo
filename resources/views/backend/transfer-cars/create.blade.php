@extends('backend.layout')

@section('title', 'Yeni Muhtelif Araç Girişi')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-car icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Yeni Muhtelif Araç Oluştur
                        <div class="page-title-subheading">Bu sayfa üzerinden muhtelif araç oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('VariousCars.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Muhtelif Araçları Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="agencyForm" method="POST" action="{{ route('VariousCars.store') }}">

                    @csrf
                    <div class="row">
                        <div class="col-md-6" id="container-general-info">
                            <h6 class="text-dark text-center font-weight-bold">Araç Bilgiler</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="branchCars" class="font-weight-bold">Araç Marka:</label>
                                        <input name="branchCars" required id="branchCars"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="modelCars" class="">Araç Model</label>
                                        <input name="modelCars" required id="modelCars"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="modelYear" class="">Araç Model Yılı</label>
                                        <input name="modelYear" required id="modelYear"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}"
                                               class="form-control form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="plaqueCar" class="">Plaka:</label>
                                        <input name="plaqueCar" required id="plaqueCar"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="capacityCar" class="">Araç Kapasitesi</label>
                                        <select name="capacityCar" required id="capacityCar"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option
                                                {{old('stopTransfer') == 'Panelvan' ? 'selected' : ''}} value="Panelvan">
                                                Panelvan
                                            </option>
                                            <option
                                                {{old('stopTransfer') == 'Kamyonet' ? 'selected' : ''}} value="Kamyonet">
                                                Kamyonet
                                            </option>
                                            <option
                                                {{old('stopTransfer') == '6 Teker Kamyonet' ? 'selected' : ''}} value="6 Teker Kamyonet">
                                                6 Teker Kamyonet
                                            </option>
                                            <option
                                                {{old('stopTransfer') == '10 Teker Kamyonet' ? 'selected' : ''}} value="10 Teker Kamyon">
                                                10 Teker Kamyon
                                            </option>
                                            <option
                                                {{old('stopTransfer') == '40 Ayak Kamyon' ? 'selected' : ''}} value="40 Ayak Kamyon">
                                                40 Ayak Kamyon
                                            </option>
                                            <option {{old('stopTransfer') == 'Tır' ? 'selected' : ''}} value="Tır">
                                                Takılı
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="tonnage" class="">Tonaj (Kg)</label>
                                        <input name="tonnage" required id="tonnage"
                                               placeholder="Tonaj"
                                               type="text"
                                               value="{{ old('tonnage') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="desiCapacity" class="">Desi</label>
                                        <input name="desiCapacity" required id="desiCapacity"
                                               placeholder="Desi"
                                               type="text"
                                               value="{{ old('desiCapacity') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="atsInfo" class="">ATS (Araç Takip Sistemi):</label>
                                        <select name="atsInfo" required id="atsInfo"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option {{old('atsInfo') == 'Takılı' ? 'selected' : ''}} value="Takılı">
                                                Takılı
                                            </option>
                                            <option
                                                {{old('atsInfo') == 'Takılı Değil' ? 'selected' : ''}} value="Takılı Değil">
                                                Takılı Değil
                                            </option>
                                            <option
                                                {{old('atsInfo') == 'Gönderildi' ? 'selected' : ''}} value="Gönderildi">
                                                Gönderildi
                                            </option>
                                            <option
                                                {{old('atsInfo') == 'Gönderilmedi' ? 'selected' : ''}} value="Gönderilmedi">
                                                Gönderilmedi
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="container-communication-info">
                            <h6 class="text-dark text-center  font-weight-bold">Şöför İletişim Bilgileri</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="driverName" class="">Şoför Adı</label>
                                        <input name="driverName" required id="driverName"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="driverPhone" class="">Şoför İletişim *</label>
                                        <input name="driverPhone" id="driverPhone" required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ old('driverPhone') }}"
                                               class="form-control form-control-sm input-mask-trigger">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="driverAdress" class="">Şoför Adresi</label>
                                        <textarea name="driverAdress" id="driverAdress"
                                                  class="form-control form-control-sm" maxlength="500"></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-12" id="container-finance">
                            <h6 class="text-dark text-center  font-weight-bold">Finans</h6>
                            <div class="divider"></div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="line" class="">Hat</label>
                                        <select name="line" required id="line" class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="exitTransfer" class="">Çıkış Aktarma </label>
                                        <select name="exitTransfer" required id="exitTransfer"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="arriveTransfer" class="">Varış Aktarma</label>
                                        <select name="arriveTransfer" required id="arriveTransfer"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="stopTransfer" class="">Uğradığı Aktarmalar</label>
                                        <select name="stopTransfer" required id="stopTransfer"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Aracı Kaydet</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script>
        $(document).ready(() => {

            $("#agencyForm").validate({
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                }
            });

        });

    </script>
@endsection
