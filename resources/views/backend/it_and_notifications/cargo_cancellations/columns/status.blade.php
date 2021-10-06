@if($status == 'AÇIK')
    <div class="badge badge-success">{{$status}}</div>
@elseif($status == 'BEKLEMEDE')
    <div class="badge badge-warning">{{$status}}</div>
@elseif($status == 'CEVAPLANDI')
    <div class="badge badge-alternate">{{$status}}</div>
@elseif($status == 'KAPALI')
    <div class="badge badge-dark">{{$status}}</div>
@endif
