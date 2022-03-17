@extends('backend.layout')


@section('title', 'Tüm Müşterileriniz')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-briefcase icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Yeni Gönderici Oluştur
                        <div class="page-title-subheading">
                            Mu modül üzerinden yeni gönderici oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{route('customers.index')}}">
                            <button type="button" class="btn-shadow btn btn-info">
                                 <span class="btn-icon-wrapper pr-2 opacity-7">
                                  <i class="lnr-arrow-left fa-w-20"></i>
                                 </span>
                                Tüm müşterilere geri dön
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div style="max-width: 1300px;" class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Alıcı Oluştur
                </div>

            </div>
            <div class="card-body">
                @include('backend.customers.agency.forms.sender_create_form')
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/customers/customer-details.js"></script>
    <script>componentSenderFrom = 'create-sender'</script>
    <script src="/backend/assets/scripts/customers/create/create-sender.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
@endsection
