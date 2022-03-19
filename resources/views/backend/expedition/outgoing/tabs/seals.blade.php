<div class="tab-pane show" id="tabExpeditionSeals" role="tabpanel">
    <h3 class="text-dark text-center mb-4">Kırılan Mühürler</h3>

    <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
         class="cont">
        <table id="TableEmployees"
               class="Table30Padding table-bordered table table-striped mt-3">
            <thead>
            <tr>
                <th>Mühür No</th>
                <th>Mühürleyen</th>
                <th>Mühürleme Tarihi</th>
                <th>Mühürü Açan</th>
                <th>Açılma Tarihi</th>
            </tr>
            </thead>
            <tbody>
                @foreach($expedition->seals as $seal)
                    <tr>
                        <td> {{$seal->serial_no }}</td>
                        <td> {{$seal->creator}}</td>
                        <td> {{$seal->created_at }}</td>
                        <td> {{$seal->opener }}</td>
                        <td> {{$seal->opened_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
