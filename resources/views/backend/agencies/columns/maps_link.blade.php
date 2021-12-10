@if($maps_link != '')
    <a target="popup" style="cursor: pointer;"
       onclick="window.open('http://www.google.com/maps?q={{$maps_link}}','popup','width=800,height=750'); return false;">
        <img width="29" src="/backend/assets/images/google-maps.gif" alt="">
    </a>
@endif
