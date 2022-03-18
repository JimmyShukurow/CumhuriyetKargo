{{-- Large modal  => New Reciver --}}
<div class="modal fade bd-example-modal-lg" id="modalNewReceiver" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Yeni Alıcı Oluştur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- CARD START --}}

                @include('backend.customers.agency.forms.receiver_create_form')

            </div>
            <div style="display: block;" class="modal-footer">
                <b style="float: left;"> <b class="text-danger">*</b> ile belirtilen alanlar zorunludur.</b>
                <button style="float: right;" type="button" class="btn btn-danger" data-dismiss="modal">Kapat
                </button>
            </div>
        </div>
    </div>
</div>
