{{-- Large modal  => Select Customer --}}
<div class="modal fade bd-example-modal-lg" id="modalSelectCustomer" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSelectCustomerHead">Müşteri Seçin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodySelectCustomer" class="modal-body">
                {{-- CARD START --}}

                <div class="row">
                    <div style="max-height: 500px;overflow-x: auto;" id="table-scroll"
                         class="table-scroll col-md-12">
                        <table style="white-space: nowrap;"
                               id="main-table"
                               class="main-table table table-bordered Table30Padding table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Cep Telefonu</th>
                                <th>İl/İlçe</th>
                                <th>Adres</th>
                                <th>Kategori</th>
                                <th>Kayıt Tarihi</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyCustomers">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
