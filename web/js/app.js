/**
 * Created by PhpStorm.
 * User: kaanburaksener
 * Date: 14/10/16
 * Time: 18:00
 */

function checkPasswordMatch() {
    var passwordElement = $("#user_registration_plainPassword_first");
    var passwordConfirmElement = $("#user_registration_plainPassword_second");

    var password = passwordElement.val();
    var confirmPassword = passwordConfirmElement.val();

    if(password != '' || confirmPassword != '') {
        if (password != confirmPassword) {
            if(passwordElement.hasClass("match") || passwordConfirmElement.hasClass("match")) {
                passwordElement.removeClass("match").addClass("not-match");
                passwordConfirmElement.removeClass("match").addClass("not-match");
            } else {
                passwordElement.addClass("not-match");
                passwordConfirmElement.addClass("not-match");
            }
        } else {
            if(passwordElement.hasClass("not-match") || passwordConfirmElement.hasClass("not-match")) {
                passwordElement.removeClass("not-match").addClass("match");
                passwordConfirmElement.removeClass("not-match").addClass("match");
            } else {
                passwordElement.addClass("match");
                passwordConfirmElement.addClass("match");
            }
        }
    }
}

$(document).ready(function () {
    $("#user_registration_plainPassword_first, #user_registration_plainPassword_second").keyup(checkPasswordMatch);
});