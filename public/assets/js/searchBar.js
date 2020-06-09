$( document ).ready(function() {

    // Search bar
    $("#searchBar").on("keyup", function() {
        
        var value = $(this).val().toLowerCase();
        $(".list .data-row").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
      
});