function calculateCurpVerificationDigit(t) {
    let e = 0;
    for (let a = 0; a < 17; a++) e += "0123456789ABCDEFGHIJKLMN\xd1OPQRSTUVWXYZ".indexOf(t[a]) * (18 - a);
    let i = 10 - e % 10;
    return 10 === i ? "0" : String(i)
}

function calculateNssVerificationDigit(t) {
    if (t.length >= 11) return parseInt(t[10], 10);
    let e = 0;
    for (let a = 0; a < 10; a++) if (1 & a) {
        let i = 2 * parseInt(t[a], 10);
        e += i % 10 + (i >= 10 ? 1 : 0)
    } else e += parseInt(t[a], 10);
    return String((10 - e % 10) % 10)
}

jQuery(document).ready(function () {
    jQuery(".wpcf7-validates-as-required").addClass("required"), jQuery(".wpcf7-email").addClass("email"), jQuery(".wpcf7-checkbox.wpcf7-validates-as-required input").addClass("required"), jQuery(".wpcf7-radio input").addClass("required"), jQuery("form.wpcf7-form").each(function () {
        jQuery(this).addClass(scriptData.jvcf7p_default_settings.jvcf7p_invalid_field_design), jQuery(this).addClass(scriptData.jvcf7p_default_settings.jvcf7p_show_label_error), jQuery(".wpcf7-file").attr("accept", ""), jQuery(this).validate({
            ignore: ":hidden input, :hidden textarea, :hidden select",
            onfocusout: function (t) {
                this.element(t)
            },
            onfocusout: function (t) {
                this.element(t)
            },
            errorPlacement: function (t, e) {
                e.is(":checkbox") || e.is(":radio") ? t.insertAfter(jQuery(e).parent().parent().parent()) : t.insertAfter(e)
            }
        })
    }),jQuery(".wpcf7-form-control.wpcf7-submit").click(function (t) {
        $jvcfpValidation = jQuery(this).parents("form"), jQuery($jvcfpValidation).valid(), 0 != jQuery($jvcfpValidation).validate().pendingRequest && (t.preventDefault(), $topPendingPosition = parseInt($topPendingPosition = jQuery(".wpcf7-form-control.pending").offset().top) - 100, jQuery("body, html").animate({scrollTop: $topPendingPosition}, "normal")), jQuery($jvcfpValidation).valid() || (t.preventDefault(), $topErrorPosition = parseInt($topErrorPosition = jQuery(".wpcf7-form-control.error").offset().top) - 100, jQuery("body, html").animate({scrollTop: $topErrorPosition}, "normal"))
    }), jQuery(".submit-type-reload").click(function (t) {
        $jvcfpValidation = jQuery(this).parents("form"), jQuery($jvcfpValidation).valid();
        0 != jQuery($jvcfpValidation).validate().pendingRequest
    }), jQuery('[class*="JVmin-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVmin-[0-9]+/)).toString().split("-");
        jQuery(this).attr("min", t[1])
    }), jQuery('[class*="JVmax-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVmax-[0-9]+/)).toString().split("-");
        jQuery(this).attr("max", t[1])
    }), jQuery('[class*="JVminlength-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVminlength-[0-9]+/)).toString().split("-");
        jQuery(this).rules("add", {minlength: t[1]})
    }), jQuery('[class*="JVmaxlength-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVmaxlength-[0-9]+/)).toString().split("-");
        jQuery(this).rules("add", {maxlength: t[1]})
    }), jQuery('[class*="JVrangelength-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVrangelength-[0-9]+-[0-9]+/)).toString().split("-");
        jQuery(this).rules("add", {rangelength: [t[1], t[2]]})
    }), jQuery('[class*="JVrange-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVrange-[0-9]+-[0-9]+/)).toString().split("-");
        jQuery(this).rules("add", {range: [t[1], t[2]]})
    }), jQuery('[class*="JVequalTo-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVequalTo-[a-zA-Z0-9-_]+/)).toString().split("To-");
        jQuery(this).rules("add", {equalTo: "[name=" + t[1] + "]"})
    }), jQuery('[class*="JVextension-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVextension-[a-zA-Z0-9-_]+/)).toString().split("extension-")[1].toString().split("_").join("|");
        jQuery(this).rules("add", {extension: t})
    }), jQuery('[class*="JVrequireGroup-"]').each(function () {
        var t = (processingClass = (allClasser = jQuery(this).attr("class")).match(/JVrequireGroup-[a-zA-Z0-9-_]+/)).toString().split("requireGroup-")[1].toString().split("_");
        jQuery(this).addClass(t[1]), jQuery(this).rules("add", {require_from_group: [t[0], "." + t[1]]})
    }), jQuery("input.checkUsername").each(function () {
        jQuery(this).rules("add", {
            remote: {
                url: scriptData.jvcf7p_ajax_url,
                type: "post",
                data: {
                    method: "checkUsername",
                    fieldname: jQuery(this).attr("name"),
                    _wpnonce: jQuery('input[name="_wpnonce"]').val()
                }
            }
        })
    }), jQuery('[class*="CCode-"]').each(function () {
        CustomValidatonID = (processingClass = (allClasser = jQuery(this).attr("class")).match(/CCode-[0-9]+/)).toString().split("-")[1], jQuery(this).rules("add", {
            remote: {
                url: scriptData.jvcf7p_ajax_url,
                type: "post",
                data: {
                    method: "customCode",
                    fieldname: jQuery(this).attr("name"),
                    custom_validation_id: CustomValidatonID,
                    _wpnonce: jQuery('input[name="_wpnonce"]').val()
                }
            }
        })
    }), jQuery("input.checkCURP").each(function () {
        jQuery(this).rules("add", {
            remote: {
                url: scriptData.jvcf7p_ajax_url,
                type: "post",
                data: {
                    method: "checkCURP",
                    fieldname: jQuery(this).attr("name"),
                    _wpnonce: jQuery('input[name="_wpnonce"]').val()
                }
            }
        })
    }), jQuery("input.emailVerify").each(function () {
        elementName = jQuery(this).attr("name"), saveButton = '<span style="width:' + (elementSize = jQuery(this).outerWidth()) + 'px;" class="verification_code_holder"><input type="text" name="email-verification-code" data-for="' + elementName + '" class="wpcf7-form-control wpcf7-text verifyEmailCode required" value="" placeholder="' + scriptData.jvcf7p_default_settings.jvcf7p_verify_code_field_placeholder + '" /><input type="button" class="jvcf7_verify_email_btn" value="' + scriptData.jvcf7p_default_settings.jvcf7p_code_send_button_label + '" data-for="' + elementName + '" id="jvcf7_verify_email_btn" style="display:none;" /></span>', jQuery(saveButton).insertAfter(this), jQuery(".verifyEmailCode").rules("add", {
            required: !0,
            remote: {
                url: scriptData.jvcf7p_ajax_url,
                type: "post",
                data: {
                    method: "verifyEmailCode",
                    fieldname: "email-verification-code",
                    _wpnonce: jQuery('input[name="_wpnonce"]').val(),
                    email: function () {
                        return jQuery("input[name=" + elementName + "]").val()
                    }
                }
            }
        }), jQuery(".jvcf7_verify_email_btn").show()
    }), jQuery("input.jvcf7_verify_email_btn").click(function () {
        fieldname = jQuery(this).attr("data-for"), !0 == jQuery("input[name=" + fieldname + "]").valid() ? jQuery.ajax({
            url: scriptData.jvcf7p_ajax_url,
            context: this,
            type: "post",
            data: {
                method: "sendVerificationCode",
                _wpnonce: jQuery('input[name="_wpnonce"]').val(),
                email: jQuery("input[name=" + fieldname + "]").val()
            },
            beforeSend: function () {
                jQuery(this).removeClass("valid"), jQuery(this).attr("value", "Sending..")
            },
            success: function (t) {
                jQuery(this).removeClass("valid"), jQuery(this).attr("value", "Resend Code")
            },
            error: function () {
                jQuery(this).removeClass("valid"), jQuery(this).attr("value", "Error !")
            }
        }) : jQuery("input[name=" + fieldname + "]").focus()
    })
}), jQuery.validator.addMethod("email", function (t, e) {
    return this.optional(e) || /^[+\w-\.]+@([\w-]+\.)+[\w-]{2,10}$/i.test(t)
}, "Please enter a valid email address"), jQuery.validator.addMethod("curp", function (t, e) {
    return this.optional(e) || /^[A-Z][AEIXOU][A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HMX](AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z][0-9]$/i.test(t) && t[17] == calculateCurpVerificationDigit(t)
}, "Por favor ingresa una CURP v\xe1lida"), jQuery.validator.addMethod("nss", function (t, e) {
    return this.optional(e) || /^\d{11}$/i.test(t) && t[10] == calculateNssVerificationDigit(t)
}, "Por favor ingresa un N\xfamero de Seguro Social v\xe1lido"), jQuery.validator.addMethod("rfc", function (t, e) {
    return this.optional(e) || /^([A-Z]{4}[0-9]{6}[A-Z0-9]{3})|([A-Z]{3}[0-9]{6}[A-Z0-9]{3})$/i.test(t)
}, "Por favor ingresa un RFC v\xe1lido"), jQuery.validator.addMethod("letters_space", function (t, e) {
    return this.optional(e) || /^[a-zA-Z ]*$/.test(t)
}, "Letters and space only"), jQuery.validator.addMethod("stateUS", function (t, e) {
    return this.optional(e) || t.match(/^(A[LKSZRAEP]|C[AOT]|D[EC]|F[LM]|G[ANU]|HI|I[ADLN]|K[SY]|LA|M[ADEHINOPST]|N[CDEHJMVY]|O[HKR]|P[ARW]|RI|S[CD]|T[NX]|UT|V[AIT]|W[AIVY])$/)
}, "Please specify a valid state");
var regExs = [];
for (const validationID in scriptData.jvcf7p_regexs) regExs[validationID] = RegExp(scriptData.jvcf7p_regexs[validationID].regex, "i"), errorMsg = scriptData.jvcf7p_regexs[validationID].error_mg, jQuery.validator.addMethod("RegEx-" + validationID, function (t, e) {
    return this.optional(e) || regExs[validationID].test(t)
}, errorMsg);
jQuery.extend(jQuery.validator.messages, scriptData.jvcf7p_default_error_msgs);