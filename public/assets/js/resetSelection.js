$( document ).ready(function() {
    
    $('#resetSelection').click( function(){
        $('input:radio').prop('checked', false);
    });

});