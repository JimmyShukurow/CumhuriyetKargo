function drpIlceler(IlId, IlceID, IslemID) {




    var _data = {
        IlId: IlId,
        IlceID: IlceID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drpIlceler",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drpIlceler" + IslemID).empty().append(result);




        }, error: function (e) {

            alert(e.responseText);
        }
    });
}

function drpCariFirma(CariFirmaID, IslemID) {
    
    var _data = {
       
        CariFirmaID: CariFirmaID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drpCariFirma",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drpCariFirma").empty().append(result);
            
        }, error: function (e) {

            alert(e.responseText);
        }
    });
}

function drpKasaID_Acente(CariFirmaID, KasaID, TahsilatTipID, IslemID) {

    var _data = {

        CariFirmaID: CariFirmaID,
        KasaID: KasaID,
        TahsilatTipID: TahsilatTipID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drpKasaID_Acente",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drpKasaID_Acente" + IslemID).empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}

function drpKasaID(KasaID, TahsilatTipID, IslemID) {

    var _data = {

        KasaID: KasaID,
        TahsilatTipID: TahsilatTipID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drpKasaID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drpKasaID" + IslemID).empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}

function drp_RaporTipID(RaporTipID) {

    var _data = {

        RaporTipID: RaporTipID,
        //IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drp_RaporTipID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drp_RaporTipID").empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}


function modal_drpTipID(RaporTipID) {

    var _data = {

        RaporTipID: RaporTipID,
        //IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/modal_drpTipID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_modal_drpTipID").empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}


function drp_GenelRaporBaslik(ID) {

    var _data = {

        ID: ID
      

    };

    $.ajax({
        cache: false,
        url: "/inputs/drp_GenelRaporBaslik",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drp_GenelRaporBaslik").empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}


function drp_TarihSecimTipID(ID) {

    var _data = {

        ID: ID
        //IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drp_TarihSecimTipID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drp_TarihSecimTipID").empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}


function drpYuklemeNoktaID(YuklemeNoktaID, IslemID) {

    var _data = {

        YuklemeNoktaID: YuklemeNoktaID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drpYuklemeNoktaID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drpYuklemeNoktaID").empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}


function drp_KargoFirmaID(KargoFirmaID,FirmaID, IslemID) {

    var _data = {

        KargoFirmaID: KargoFirmaID,
        FirmaID: FirmaID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drp_KargoFirmaID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            $("#_drp_KargoFirmaID").empty().append(result);

        }, error: function (e) {

            alert(e.responseText);
        }
    });
}

function drpGelirGiderKalemID(GelirGiderKalemID, IslemID) {

    var _data = {

        GelirGiderKalemID: GelirGiderKalemID,
        IslemID: IslemID

    };

    $.ajax({
        cache: false,
        url: "/inputs/drpGelirGiderKalemID",
        data: _data,
        type: "post",
        success: function (result) {


            //$('#' + str_divcontent).html(result);
            //$("._drpGelirGiderKalemID").empty().append(result);
            $($("._drpGelirGiderKalemID")[IslemID]).empty().append(result);

            $("#modal_drpGelirGiderKalemID" + IslemID).select2();


            
        }, error: function (e) {

            alert(e.responseText);
        }
    });
}

function drpIlceler2(IlId, str_class, str_id, str_name, str_onchange, str_selecteditem, str_divcontent) {

    var _data = {
        IlId: IlId,
        str_class: str_class,
        str_id: str_id,
        str_name: str_name,
        str_onchange: str_onchange,
        str_selecteditem: str_selecteditem
    };

    $.ajax({
        cache: false,
        url: "/inputs/drpIlceler2",
        data: _data,
        type: "post",
        success: function (result) {
            $('#' + str_divcontent).html(result);
        }, error: function (e) {

            alert(e.responseText);
        }
    });
}