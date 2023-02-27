(function ($) {
    $(document).ready(function () {
        // Agregar el campo de entrada personalizado
        $('#new-tag-fechas').parent().addClass("form-field date-wrap");
        $('#new-tag-fechas').addClass("date-picker");

        // Agregar el selector de fecha
        $('#new-tag-fechas').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('#ui-datepicker-div').css("background-color", "#fff");
        $('#ui-datepicker-div').css("opacity", "0.8");
    });
})(jQuery);