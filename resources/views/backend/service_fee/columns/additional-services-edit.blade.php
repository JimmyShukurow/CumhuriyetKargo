<div class="dropdown d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
            class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">İşlemler
    </button>
    <div
        style="min-width: 1rem; max-width: 140px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);"
        role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start">
        <a class="dropdown-item edit-additional-service" id="{{$id}}">Düzenle</a>
        <button type="button" id="{{$id}}" tabindex="0" from="additional-service"
                class="dropdown-item trash delete-additional-service"> Sil
        </button>
    </div>
</div>

