@extends('backend.layout')

@section('title', 'Acente Ödemesi Bildir')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-safe icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Ödeme Bildirisi Oluştur
                        <div class="page-title-subheading">Bu modül üzerinden Genel Müdürlük Muhasebe departmanına ödeme
                            bildirimide bulunabilirsiniz. Ödemeniz muhasebe departmanı tarafından onaylandıktan sonra
                            kasanızdan eksilecektir.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-sm-6">
                        <h5 style="display: inline-block" class="card-title">Ödeme Bildirisi Oluştur</h5>
                    </div>
                </div>

                <form id="UserForm" method="POST" enctype="multipart/form-data"
                      action="{{ route('safe.agency.insertPaymentApp') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Bildiriyi Oluşturan*</label>
                                <input name="name_surname" required id="name_surname"
                                       placeholder="Kullanıcı ad soyad bilgisini giriniz"
                                       type="text"
                                       value="{{ $data['name'] }}" disabled class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Acente</label>
                                <input name="name_surname" required id="name_surname"
                                       placeholder="Kullanıcı ad soyad bilgisini giriniz"
                                       type="text"
                                       value="{{ $data['agency'] }}" disabled class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="link" class="">Ödeme Kanalı:</label>
                                <select name="" class="form-control" id="" disabled="">
                                    <option value="EFT/HAVALE">EFT/HAVALE</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="link" class="">Para Birimi:</label>
                                <select name="" class="form-control" id="" disabled="">
                                    <option value="TL">TL</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="paid" class="">Ödenen Miktar:</label>
                                <input class="form-control input-mask-trigger" id="editCorporateUnitPrice"
                                       placeholder="₺ 0.00" type="text" name="paid" value="{{old('paid')}}"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" required style="text-align: right;">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="description" class="">Açıklama:</label>
                                <textarea name="description" id="description" cols="30" rows="5"
                                          class="form-control">{{old('description')}}</textarea>
                            </div>
                        </div>


                    </div>

                    <div class="form-row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="files1">Eklentiler</label>
                                <input type="file" name="file1" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file1"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="files2">Eklentiler</label>
                                <input type="file" name="file2" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file2"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="files3">Eklentiler</label>
                                <input type="file" name="file3" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file3"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <em class="text-primary text-center pt-2">İzin verilen dosya türleri: .jpg, .gif, .jpeg,
                                .png,
                                .doc,
                                .xls, .pdf, .txt, .docx,
                                .xlsx
                            </em>
                        </div>
                    </div>


                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Ödeme Başvurusu Yap</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>


            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/backend-modules.js"></script>
@endsection
