<div class="text-center">
    <div class="dropdown d-inline-block">
        <button style="width: 90px !important;" type="button" aria-haspopup="true" aria-expanded="false"
                data-toggle="dropdown"
                class="mb-2 mr-2 btn btn-sm btn-primary sonOfBitcj">İşlemler <i
                class="icon ion-android-arrow-dropdown"></i>
        </button>
        <div
            style="min-width: 1rem; max-width: 140px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);"
            role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start">

            <button type="button" agency_id="{{$id}}" tabindex="0"
                    class="dropdown-item  agency-detail">
                Detay
            </button>

            <a class="dropdown-item"
               href="{{ route('agency.EditAgency', ['id' => $id]) }}">
                Düzenle
            </a>
            <button type="button" id="{{$id}}" tabindex="0" from="agency"
                    class="dropdown-item trash">
                Sil
            </button>
        </div>
    </div>

</div>
