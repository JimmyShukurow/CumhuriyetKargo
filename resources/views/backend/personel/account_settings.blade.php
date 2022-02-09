@extends('backend.layout')

@push('css')
    <link href="/backend/assets/css/pw-validate.css" rel="stylesheet">
@endpush()

@section('title', 'Hesap Ayarları')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Hesap Ayarları
                        <div class="page-title-subheading">Bu modül üzerinden hesap ayarlarınızı değiştirebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="pb-3 card-title text-center">Kişisel Bilgileriniz</h5>
                            <form action="">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nameSurname">Ad Soyad</label>
                                            <input type="text" readonly value="<?php echo e($person->name_surname); ?>"
                                                   class="form-control" id="nameSurname">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            <label for="email">E-Posta</label>
                                            <input type="text" readonly value="<?php echo e($person->email); ?>"
                                                   class="form-control"
                                                   id="email">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Telefon</label>
                                            <input type="text" readonly value="<?php echo e($person->phone); ?>"
                                                   class="form-control"
                                                   id="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Yetki</label>
                                            <input type="text" readonly value="<?php echo e($person->display_name); ?>"
                                                   class="form-control" id="role">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="pb-1 card-title text-center">Acente Bilgileri</h5>

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="agencyCity">Acente İl</label>
                                            <input type="text" readonly value="<?php echo e($person->branch_city); ?>"
                                                   class="form-control"
                                                   id="agencyCity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="agencyDistrict">Acente İlçe</label>
                                            <input type="text" readonly
                                                   value="<?php echo e($person->branch_district); ?>"
                                                   class="form-control" id="agencyDistrict">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="agencyName">Acente Adı</label>
                                            <input type="text" readonly value="<?php echo e($person->branch_name); ?>"
                                                   class="form-control" id="agencyName">
                                        </div>
                                    </div>
                                </div>
                                <div class="divider"></div>

                                <h5 class="pb-1 card-title text-center">Aktarma Bilgileri</h5>

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tcCity">Aktarma İl</label>
                                            <input type="text" readonly value="<?php echo e($tc->city); ?>"
                                                   class="form-control" id="tcCity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tcDistrict">Aktarma İlçe</label>
                                            <input type="text" readonly value="<?php echo e($tc->district); ?>"
                                                   class="form-control" id="tcDistrict">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tcName">Aktarma Adı</label>
                                            <input type="text" readonly
                                                   value="<?php echo e($tc->tc_name) . ' TRANSFER MERKEZİ';  ?>"
                                                   class="form-control" id="tcName">
                                        </div>
                                    </div>
                                </div>
                                <div class="divider"></div>

                                <h5 class="pb-1 card-title text-center">Bölge Müdürlüğü Bilgileri</h5>

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rdCity">Bölge Müdürlüğü İl</label>
                                            <input type="text" readonly value="<?php echo e($region_info->city); ?>"
                                                   class="form-control" id="rdCity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rdDistrict">Bölge Müdürlüğü İlçe</label>
                                            <input type="text" readonly value="<?php echo e($region_info->district); ?>"
                                                   class="form-control" id="rdDistrict">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rdName">Bölge Müdürlüğü Adı</label>
                                            <input type="text" readonly
                                                   value="<?php echo e($region_info->name != '' ? $region_info->name . ' BÖLGE MÜDÜRLÜĞÜ' : ''); ?>"
                                                   class="form-control" id="rdName">
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <div class="divider"></div>
                            <h4 class="pb-3 card-title text-center">Bağımlılıklar</h4>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-danger">
                                            <div class="menu-header-image opacity-2"
                                                 style="background-image: url('/backend/assets/images/dropdown-header/abstract2.jpg');"></div>
                                            <div class="menu-header-content"><h5
                                                    class="menu-header-title"><?php echo e($agency->agency_name . ' ACENTE'); ?></h5>
                                                <h6 class="menu-header-subtitle"><?php echo e($agency->city . '/' . $agency->district . ' (' . $agency->agency_code . ')'); ?></h6>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-striped table-bordered TableNoPadding">
                                        <thead>
                                        <tr class="text-center">
                                            <th colspan="2"> <?php echo e($agency->agency_name . ' ACENTE BİLGİLERİ'); ?> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 9rem">İl</td>
                                            <td><?php echo e($agency->city); ?></td>
                                        </tr>
                                        <tr>
                                            <td>İlçe</td>
                                            <td><?php echo e($agency->district); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mahalle</td>
                                            <td><?php echo e($agency->neighborhood); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Adres</td>
                                            <td><?php echo e($agency->adress); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Şube Kodu</td>
                                            <td><?php echo e($agency->agency_code); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Acente Sahibi</td>
                                            <td class="font-weight-bold"><?php echo e($agency->name_surname); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Acente İletişim</td>
                                            <td><?php echo e($agency->phone); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Acente Geliştirme Sorumlusu</td>
                                            <td class="font-weight-bold text-primary"><?php echo e($agency->agency_development_officer); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Acente Müdürü</td>
                                            <td class="font-weight-bold text-danger"><?php echo e(@$agency_director->name_surname); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-success">
                                            <div class="menu-header-image opacity-1"
                                                 style="background-image: url('/backend/assets/images/dropdown-header/abstract3.jpg');"></div>
                                            <div class="menu-header-content"><h5
                                                    class="menu-header-title"><?php echo e($tc->tc_name . ' TRANSFER MERKEZİ'); ?></h5>
                                                <h6 class="menu-header-subtitle"><?php echo e($tc->city . '/' . $tc->district); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered TableNoPadding">
                                        <thead>
                                        <tr class="text-center">
                                            <th colspan="2"> <?php echo e($tc->tc_name . ' TRANSFER MERKEZİ BİLGİLERİ'); ?> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 9rem">İl</td>
                                            <td><?php echo e($tc->city); ?></td>
                                        </tr>
                                        <tr>
                                            <td>İlçe</td>
                                            <td><?php echo e($tc->district); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mahalle</td>
                                            <td><?php echo e($tc->neighborhood); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Adres</td>
                                            <td><?php echo e($tc->adress); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Aktarma İletişim</td>
                                            <td><?php echo e($tc->phone); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Aktarma Müdürü</td>
                                            <td class="font-weight-bold text-danger"><?php echo e(isset($tc_director->name_surname) ? $tc_director->name_surname : 'ATANMADI'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Aktarma Müdürü İletişim</td>
                                            <td class="font-weight-bold text-danger"><?php echo e(isset($tc_director->phone) ? $tc_director->phone : ''); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Aktarma Müdür Yard.</td>
                                            <td class="font-weight-bold text-primary"><?php echo e(isset($tc_assistant_director->name_surname) ? $tc_assistant_director->name_surname : 'ATANMADI'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Aktarma Müdür Yard. İletişim</td>
                                            <td class="font-weight-bold text-primary"><?php echo e(isset($tc_assistant_director->phone) ? $tc_assistant_director->phone : ''); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-md-4">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-dark">
                                            <div class="menu-header-image opacity-1"
                                                 style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');"></div>
                                            <div class="menu-header-content"><h5
                                                    class="menu-header-title"><?php echo e($region_info->name . ' BÖLGE MÜDÜRLÜĞÜ'); ?></h5>
                                                <h6 class="menu-header-subtitle"><?php echo e($region_info->city . '/' . $region_info->district); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered TableNoPadding">
                                        <thead>
                                        <tr class="text-center">
                                            <th colspan="2"> <?php echo e($region_info->name . ' BÖLGE MÜDÜRLÜĞÜ'); ?> </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width: 9rem">İl</td>
                                            <td><?php echo e($region_info->city); ?></td>
                                        </tr>
                                        <tr>
                                            <td>İlçe</td>
                                            <td><?php echo e($region_info->district); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mahalle</td>
                                            <td><?php echo e($region_info->neighborhood); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Adres</td>
                                            <td><?php echo e($region_info->adress); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Bölge İletişim</td>
                                            <td><?php echo e($region_info->phone); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Bölge Müdürü</td>
                                            <td class="font-weight-bold text-danger"><?php echo e(isset($rd_director->name_surname) ? $rd_director->name_surname : 'ATANMADI'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Bölge Müdürü İletişim</td>
                                            <td class="font-weight-bold text-danger"><?php echo e(isset($rd_director->phone) ? $rd_director->phone : ''); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Bölge Müdür Yard.</td>
                                            <td class="font-weight-bold text-primary"><?php echo e(isset($rd_assistant_director->name_surname) ? $rd_assistant_director->name_surname : 'ATANMADI'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Bölge Müdür Yard. İletişim</td>
                                            <td class="font-weight-bold text-primary"><?php echo e(isset($rd_assistant_director->phone) ? $rd_assistant_director->phone : ''); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>


                            <div class="divider"></div>
                            <h4 class="pb-3 card-title text-center">Şifre Değişikliği</h4>


                            <form action="{{route('personel.ChangePassword')}}" id="frm" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="password">Geçerli Şifre</label>
                                            <input type="password" id="password" value="{{old('password')}}"
                                                   name="password" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="psw">Yeni Şifre</label>
                                            <input type="text" name="passwordNew" value="{{old('passwordNew')}}"
                                                   required id="psw" class="form-control"
                                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                   title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="passwordNewAgain">Yeni Şifre
                                                Tekrar</label>
                                            <input type="text" value="{{old('passwordNewAgain')}}"
                                                   name="passwordNewAgain" required id="passwordNewAgain"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-alternate w-25">Şifreyi Değiştir</button>
                                    </div>
                                </div>
                            </form>

                            <div id="message">
                                <h3 class="text-primary">Yeni şifre belirlerken uymanız gerekenler:</h3>
                                <p id="letter" class="invalid text-danger"> En az bir <b>küçük harf</b></p>
                                <p id="capital" class="invalid text-danger">En az bir <b>büyük harf</b></p>
                                <p id="number" class="invalid text-danger">En az bir <b>rakam</b></p>
                                <p id="length" class="invalid text-danger">En az <b>8 karakter</b></p>
                                <p id="special" class="invalid text-danger">En az bir <b>özel karakter</b></p>
                                <p id="again" class="invalid text-danger">Girdiğiniz şifreler <b>uyuşmalıdır</b></p>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('js')
    <script src="/backend/assets/scripts/psw-validation.js"></script>
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script>
        $(document).ready(() => {

            $("#frm").validate({
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
