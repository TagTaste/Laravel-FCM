(function($, valid) {
    var app = {};
    app.valid = valid;

    $('[data-toggle="tooltip"]').tooltip();

    $("input").on({
        keydown: function(e) {
        if (e.which === 32 &&  e.target.selectionStart === 0)
          return false;
        }
    });

    /**
     * Resets form, and remove all validations from all inputs
     * @param  {[type]} id [description]
     * @return {[type]}    [description]
     */
    app.resetForm = function(id) {
        if (!(id.indexOf("#") > -1)) {
            id = "#" + id;
        }
        $(id)[0].reset();
        $.each($(id + " span"), function(key, value) {
            $(value).removeClass("glyphicon-ok");
            $(value).removeClass("glyphicon-remove");
        });
        $.each($(id + " div"), function(key, value) {
            $(value).removeClass("has-error");
            $(value).removeClass("has-success");
        });
        $.each($(id + " label.error"), function(key, value) {
            $(value).remove();
        });
    }

    /**
     * Removes all validations from all inputs and resets div,
     * @param  {div id}
     * @return {[type]}
     */
    app.resetDiv = function(id) {
        if (!(id.indexOf("#") > -1)) {
            id = "#" + id;
        }
        $.each($(id + " span"), function(key, value) {
            $(value).removeClass("glyphicon-ok");
            $(value).removeClass("glyphicon-remove");
        });
        $.each($(id + " div"), function(key, value) {
            $(value).removeClass("has-error");
            $(value).removeClass("has-success");
        });
        $.each($(id + " label.error"), function(key, value) {
            $(value).remove();
        });
        $.each($(id + " input"), function(key, value) {
            //alert(key + "===" + value.id);
            $(value).val("");
            //$(value.name).prop('checked', false);
            //$("input[name=" + value.name + "]").removeClass('active');
        });
    }

    /**
     * Validate partial form, ie validates specific div from form
     * @return {[type]} [description]
     */
    app.validateDiv = function(id) {
        var valid = true;
        if (!(id.indexOf("#") > -1)) {
            id = "#" + id;
        }

        $.each($(id + " input"), function(index, value) {
            if (!$(value).valid()) {
                valid = false;
            }
        });
        return valid;
    }


    /**
     * Display loader on form submit
     * @return {[type]} [description]
     */
    app.showLoading = function(id) {
        //$(document).on('submit', 'form', function() {
            if (!(id.indexOf("#") > -1)) {
                id = "#" + id;
            }
            Loading(true);
            var $form = $(id),
            $button,
            label;
            $form.find(':submit').each(function() {
                $button = $(id);
                label = $button.data('after-submit-value');
                if (typeof label != 'undefined') {
                    $button.val(label).prop('disabled', true);
                }
            });
        //});
    }

    app.disableButton = function(id) {
        //$(document).on('submit', 'form', function() {
        if (!(id.indexOf("#") > -1)) {
            id = "#" + id;
        }
        Loading(true);
        var $button = $(id),label;
        label = $button.data('after-submit-value');
        if (typeof label != 'undefined') {
            $button.val(label).prop('disabled', true);
        }
    }

    $.validator.setDefaults({    
        ignore: [],
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-success").removeClass("has-feedback");
            $(element).closest('.form-group').addClass("has-feedback").addClass("has-error");
            $(element).siblings(".glyphicon").removeClass("glyphicon-ok").addClass("glyphicon-remove");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error").removeClass("has-feedback");
            $(element).closest('.form-group').addClass("has-feedback").addClass("has-success");
            $(element).siblings(".glyphicon").removeClass("glyphicon-remove").addClass("glyphicon-ok");
        },
             //errorClass: 'control-labels',
            errorPlacement: function(error, element) {       // Uncomment below to enable showing error messages.
            element.parents('.form-group').find('.help-block').append(error);    
        }  
    });

    //window.$.validator.setDefaults = $.validator.setDefaults;
    window.app = app;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

})(jQuery, validator);

/**
 * display success and error message
 */
function successErrorMessage(msg, type) {
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            positionClass: 'toast-bottom-left',
            timeOut: 3000
        };
        if (type == "success") {
            toastr.success('', msg);
        } else {
            toastr.error('', msg);
        }
    }, 1300);
}