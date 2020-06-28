$( document ).ready(function() {
    
    $(".more-infos").click( function() {
        $(this).find(".more-infos-chevron").toggleClass('flip');
    });

    $('input:radio[name="severity"]').change( function(){
        if ($(".more-content").is(":hidden") && this.value != 'Matériel non infecté') {
            $('.more-content').slideToggle(300);
        }
        if ($(".more-content").is(":visible") && this.value == 'Matériel non infecté') {
            $("#internalAnalysis").val('');
            $('input:checkbox').prop('checked', false);
            $('.more-content').slideToggle(300);
        }
    });

    $("#windowsVersionCheckbox").change( function() {
        $('.more-content').slideToggle(300);
        $('input:text').val('');
        $('#windowsVersion').prop('required',true);

        if ($('#windowsVersion').prop('required',true)) {
            $('#windowsVersion').removeAttr('required');​​​​​
        }
        // } 
        // else {
        //     $('#windowsVersion').prop('required',true);
        // }
    });
});