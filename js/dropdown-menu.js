$("#menu-dropdown-trigger").click(function() {

    if ( $("#dropdown-menu").css('display') == 'none' )
    {
        $("#dropdown-menu").show();
    } else $("#dropdown-menu").hide();
});