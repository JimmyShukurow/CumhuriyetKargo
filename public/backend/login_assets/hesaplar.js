

function redirect_to_setup()
{
    var apikey = $("#user-api").val();
    var _data = {
        key: apikey
    };
    $.ajax({
        cache: false,
        url: "/hesaplar/apikeyControl",
        data: _data,
        type: "post",
        success: function (result) {
            if (result.returnVal == "0") {
                window.location = "/hesaplar/kurulum?key=" + apikey;
            }
            else {
                MessageBox("Hata!", result.returnText);
            }
        }, error: function (e) {
            alert(e.responseText);//MessageBox("Hata!", e);
        }
    });

}

function rememberPassword()
{
    var username = $("#username").val();

    var _data = {
        username: username
    };
    $.ajax({
        cache: false,
        url: "/hesaplar/rememberPassword",
        data: _data,
        type: "post",
        success: function (result) {
            if (result.returnVal == "0") {
                MessageBox("Başarılı!", result.returnText);
            }
            else {
                MessageBox("Hata!", result.returnText);
            }
        }, error: function (e) {
            alert(e.responseText);//MessageBox("Hata!", e);
        }
    });
}

function loginSubmit()
{

    var chkRemember = $("#chkrememberme").val();
    var txtUsername = $("#user-name").val();
    var txtPassword = $("#user-password").val();

    var _data = {
        chkRemember: chkRemember,
        txtUsername:txtUsername,
        txtPassword:txtPassword
    };
    $.ajax({
        cache: false,
        url: "/hesaplar/loginSubmit",
        data: _data,
        type: "post",
        success: function (result) {
            if (result.returnVal == "0") {




                if (result.location != "") {
                    window.location = result.location;

                }

                else {
                    window.location = "/anasayfa";
                }

                
            }
            else {
                MessageBox("Hata!", result.returnText);
            }
        }, error: function (e) {
            alert(e.responseText);//MessageBox("Hata!", e);
        }
    });
}

