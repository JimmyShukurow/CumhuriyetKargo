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
                    @include('backend.customers.agency.forms.sender_create_form')
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
