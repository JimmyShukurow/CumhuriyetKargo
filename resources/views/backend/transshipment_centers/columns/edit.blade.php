<div class="text-center">
    <div class="dropdown d-inline-block">
        <button type="button" aria-haspopup="true" aria-expanded="false"
                data-toggle="dropdown"
                class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">
            İşlemler
        </button>
        <div
            style="min-width: 1rem; max-width: 140px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);"
            role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start">

            <button adress="{{$adress}}" neighborhood="{{$neighborhood}}" type="button" tc_id="{{$id}}" tabindex="0"
                    class="dropdown-item  transshipment-center-detail">
                Detay
            </button>

            <a class="dropdown-item"
               href="{{ route('TransshipmentCenters.edit', $id) }}">
                Düzenle
            </a>
            <button type="button" id="{{$id}}" tabindex="0" from="transshipment_center"
                    class="dropdown-item trash">
                Sil
            </button>
        </div>
    </div>

</div>
