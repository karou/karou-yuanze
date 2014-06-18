$(document).ready(function() {
    $('#ace_button').on('click', postAceAol);
    $('#aol_button').on('click', postAceAol);
    
    $('#login_button').on('click', postCRM);
});

function postAceAol() {
    $form = $(this).parent();
    if ($form.children('input[name=Password]').val() == '')
        alert("You have to set you password first ! Please go to your profile to set your password.");
    else {
        $form.children('input[name=Password]').val(atob($form.children('input[name=Password]').val()));
        $form.submit();
        $form.children('input[name=Password]').val(btoa($form.children('input[name=Password]').val()));
    }
}

function postCRM() {
    $form = $(this).parent();
    if ($form.children('input[name=user_password]').val() == '')
        alert("You have to set you password first ! Please go to your profile to set your password.");
    else {
        $form.children('input[name=user_password]').val(atob($form.children('input[name=user_password]').val()));
        $form.submit();
        $form.children('input[name=user_password]').val(btoa($form.children('input[name=user_password]').val()));
    }
}

$(document).ready(function() {
    $('#restore').on('click', postRestore);

});

function postRestore() {
    $form = $(this).parent();
    var result = confirm("Are you sure Restore the TASK from last backup?");
    if (result) {
        $form.submit();
    } else {
        return false;
    }
}

$(document).ready(function() {
    $('#backup').on('click', postBackup);

});

function postBackup() {
    $form = $(this).parent();
    var result = confirm("Are you sure Backup the TASK?");
    if (result) {
        $form.submit();
    } else {
        return false;
    }
}
