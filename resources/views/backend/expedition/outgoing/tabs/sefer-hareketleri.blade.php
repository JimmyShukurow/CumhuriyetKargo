<div class="tab-pane show" id="tabExpeditionMovements" role="tabpanel">
    <h3 class="text-dark text-center mb-4">Kargo İptal Başvurusu</h3>

    <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
         class="cont">
        <table style="white-space: nowrap;" id="TableEmployees"
               class="Table30Padding table-bordered table-hover table table-striped mt-3">
            <thead>
            <tr>
                <th>Sefer Seri No</th>
                <th>Kullanıcı</th>
                <th>Kullanıcı Birimi</th>
                <th>Açıklama</th>
                <th>Kayıt Zamanı</th>
            </tr>
            </thead>
            <tbody id="tbodyExpeditionMovements">
                @foreach($expedition->movements as $movement)
                    <tr>
                        <td>{{ $expedition->serial_no }}</td>
                        <td>{{ $movement->user->name_surname }} ({{ $movement->user->role->display_name }})</td>
                        <td>{{ $movement->user->user_type }}</td>
                        <td>{{ $movement->description }}</td>
                        <td>{{ $movement->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr>
</div>
