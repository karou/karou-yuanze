/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function() {
    //user picture mouse action
    $('#profile-user-pic').mouseover(function() {
        $('#profile-edit-pic-button').show();
        $('#profile-pic-cover').fadeIn();
    });
    $('#profile-user-pic').mouseleave(function() {
        $('#profile-edit-pic-button').hide();
        $('#profile-pic-cover').fadeOut();
    });
});

function translateIntoEditInfo() {
    var editform = '<button class="btn btn-primary profile-submit-button" onclick="submit();" type="button"><i class="icon-ok"></i>Submit</button> <input type="button" class="btn btn-default" value="Cancel" onclick="cancel();">';
    $('#project-action-button').html(editform);
    $('.profile-editable-td').each(function() {
        var form = $.trim($(this).children('span.fixed-info').html());
        var idName = $(this).attr('id').split('-')[0];
        var change = '<input type="text" id="' + idName + '" value="' + form + '" style="height: 20px; margin:0; padding: 0 0 0 8px;" class="profile_edit_form">';
        $(this).children('span.editable-info').html(change);
    });
    //show the edit part, hide info part.
    $('.editable-info').show();
    $('.fixed-info').hide();
    checkValidateKeyup();
}

function submit() {
    if (validateSiteName() && validateInformation()) {
        $('.editable-info').hide();
        $('.fixed-info').show();
        $('#project-action-button').html('<div class="editableform-loading-ajax" style=""></div>');
        var data = '{';
        $('.profile-editable-td').each(function() {
            var editValue = $(this).children('span.editable-info').children('input').val();
            var preValue = $(this).children('span.fixed-info').html();
            if (editValue !== preValue) {
                $(this).children('span.fixed-info').html('<div class="editableform-loading-ajax" style=""></div>');
            }

            data += '"' + $(this).attr('id') + '":"' + $(this).children('span.editable-info').children('input').val() + '",';
        });
        data = data.substring(0, data.length - 1);
        data += '}';

        $.ajax({
            type: "POST",
            data: data,
            url: document.location.href + "/edit_profile_ajax",
            success: function(result) {
                var obj = JSON.parse(result);
                for (var i in obj) {
                    $('#' + i).children('span.fixed-info').html(obj[i]);
                }
                setEditProfileButton();
            }
        });
    }
}

function setEditProfileButton() {
    var button = '<button class="btn btn-profile-info-edit" type="button" onclick="translateIntoEditInfo();"><i class="icon-pencil"></i> Edit Profile</button>';
    $('#project-action-button').html(button);
    return;
}

function cancel() {
    $('.profile-editable-td').each(function() {
        $(this).children('span.editable-info').children('input').val('');
    });
    $('.editable-info').hide();
    $('.fixed-info').show();

    //hide error message
    $('#profile-error-message-1').hide();
    $('#profile-error-message-2').hide();

    setEditProfileButton();
}

function checkValidateKeyup() {
    $('#platformname').keyup(function() {
        validateSiteName();
    });
    $('#email').keyup(function() {
        validateInformation();
    });
}

function validateSiteName() {
    var container = $('#profile-error-message-1').children('td').children('div').children('ol').children('li');
    if ($('#platformname').val().length === 0) {
        $('#profile-error-message-1').show();
        container.html('<label for="platformname" class="error">Please enter your platform name</label>');
        return false;
    }
    if ($('#platformname').val().length < 2) {
        $('#profile-error-message-1').show();
        container.html('<label for="platformname" class="error">Platform name must consist of at least 2 characters</label>');
        return false;
    }

    $('#profile-error-message-1').hide();
    return true;
}

function validateInformation() {
    var container = $('#profile-error-message-2').children('td').children('div').children('ol');
    var email = $('#email').val();
    if (!checkEmail(email) && email !== '') {
        $('#profile-error-message-2').show();
        container.html('<li><label for="email" class="error">Please enter your email</label></li>');
        return false;
    }

    $('#profile-error-message-2').hide();
    return true;
}
//email regular expression
function checkEmail(email) {
    return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(email);
}
//click file button to upload picture
function submitPicFile() {
    $('#user-pic-file').click();
}

function submitUserPic() {
    var formData = new FormData();
    var fileObj = document.getElementById("user-pic-file").files;
    for (var i = 0; i < fileObj.length; i++) {
        formData.append("file" + i, fileObj[i]);
    }
    var url = document.location.href + "/submit_pic";
    $.ajax({
        data: formData,
        contentType: false,
        processData: false,
        type: "POST",
        url: url,
        success: function(result) {
            $('#profile-user-pic').children('img').attr('src', '/' + result);
        }
    });
}

//password validation
function showPasswordForm(e,type) {
    var eleId = $(e).attr('id');
    //get click element position
    var eleTop = $(e).position().top;

    //set password position
    $('#password_form').css('top', eleTop);

    //set password type
    var passwordType = ChangeFirstLetterToUpper(eleId.split('-')[0]);
    $('#password_type').html(passwordType);

    $('.btn-profile-password-edit').each(function() {
        if (eleId !== $(this).attr('id')) {
            $(this).removeClass('passwordEditButtonClicked');
        } else {
            if(type !== 1){
                $('.oldPassword').attr('style', 'display:none');
            } else {
                $('.oldPassword').attr('style', 'display:');
            }
            $('#password_form').show();
            $('#' + eleId).addClass('passwordEditButtonClicked');
        }
    });
}
function hidePasswordForm() {
    $('#password_form').hide();
    $('.btn-profile-password-edit').each(function() {
        $(this).removeClass('passwordEditButtonClicked');
    });
    clearPasswordForm();
}
function clearPasswordForm() {
    $('#pwmessage').html('');
    $('#Error0').html('');
    $('#Error1').html('');
    $('#Error2').html('');
    $('#old_password').val('');
    $('#new_password').val('');
    $('#confirm_password').val('');
}
function validate() {
    var dataArr = getData();
    var validateValue = 0;
    var result = '';
    $.each(dataArr, function(n, value) {
        if (value === '' && n !== 0) {
            $('#Error' + n).html('<font color="red" style="font-size:12px;">Cannot empty</font>');
            validateValue = 1;
        } else {
            result += value + ',';//make a password string to backend
            $('#Error' + n).html('');
        }

    });
    if (validateValue === 0) {
        clickEditPsw(result);
    } else {
        return false;
    }
}
function getData() { //get password value and make is as array
    var dataArr = [$('#old_password').val(), $('#new_password').val(), $('#confirm_password').val()];
    return dataArr;
}
function clickEditPsw(data) {
    var type = $('#password_type').html().toLowerCase();
    $('#pwmessage').html('<div class="editableform-loading-ajax" style=""></div>');
    $.ajax({
        type: "POST",
        data: data,
        url: document.location.href + "/edit_password/" + type,
        success: function(result) {
            $('#pwmessage').html(result);
            if (result === '<font style="color:green; font-size:12px">Change the password successfully.</font>') {
                $('#'+type+'-password').attr('onclick', 'showPasswordForm(this,1)');
                window.setTimeout(function() {
                    hidePasswordForm();
                }, 2000);
            }
        }
    });
}
//set string first charactor to upper case
function ChangeFirstLetterToUpper(strChange)
{
    return (strChange.toUpperCase());
}