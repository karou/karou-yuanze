function initial() {

    CKEDITOR.replace('albatross_acebundle_attachmenttype_message');

    $('#albatross_acebundle_iofsearchtype_submit_from').datepicker();
    $('#albatross_acebundle_iofsearchtype_submit_to').datepicker();
    getAutoInfoList($('#albatross_acebundle_attachmenttype_wave').val());
}
function clearFileFormValue() {
    $('#albatross_acebundle_attachmenttype_label').val('');
    $('#albatross_acebundle_attachmenttype_file').val('');
    $('#albatross_acebundle_attachmenttype_text').val('');
    $('#albatross_acebundle_attachmenttype_wave').val(1);
    $('#albatross_acebundle_attachmenttype_customclient').val('');
    $('#albatross_acebundle_attachmenttype_customproject').val('');
    $('#albatross_acebundle_attachmenttype_wave').val('');
    $('.iof_empty_message').html('');
    $('#iof_exit').val(0);
    if (CKEDITOR.instances.albatross_acebundle_attachmenttype_message) {
        CKEDITOR.instances.albatross_acebundle_attachmenttype_message.setData('');
    }
    $('.options').html('');
}
function fileuplodFormCancel() {
    clearFileFormValue();
    $('#iof_upload').fadeOut();
    $('#iof_upload').html('');
}
function addTagFormIOF(collectionHolder, $newLinkLi, update) {
    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    // get the new index
    var index = collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);
    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<tr></tr>').append(newForm);
    if (index == 0) {
        $newLinkLi.before('<tr><th>Num</th><th>Bu</th><th>Project</th><th>Scope</th><th>FW start date</th><th>FW end date</th><th>Report due date</th><th>Comment</th></tr>');
    }
    $newLinkLi.before($newFormLi);
    //take the calendar
    for (var i = 0; i <= index; i++) {
        $('#albatross_acebundle_attachmenttype_attachinfo_' + i).html(i);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + i + '_fwstartdate').datepicker();
        $('#albatross_acebundle_attachmenttype_attachinfo_' + i + '_fwenddate').datepicker();
        $('#albatross_acebundle_attachmenttype_attachinfo_' + i + '_reportduedate').datepicker();
    }
        $('#albatross_acebundle_attachmenttype_attachinfo_'+ index +'_bu_chzn').attr('readonly', 'readonly');
    if (update !== '') {
        $('#iof_exit').val(1);
        var updateArr = update.split(':');
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_bu').val(updateArr[5]);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_project').val(updateArr[6]);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_fwstartdate').val(updateArr[1]);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_fwenddate').val(updateArr[2]);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_reportduedate').val(updateArr[3]);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_scope').val(updateArr[0]);
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_comment').val(updateArr[4]);
        
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_bu').attr('onbeforeactivate',"return false");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_bu').attr('onfocus',"this.blur()");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_bu').attr('onmouseover',"this.setCapture()");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_bu').attr('onmouseout',"this.releaseCapture()");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_project').attr('onbeforeactivate',"return false");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_project').attr('onfocus',"this.blur()");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_project').attr('onmouseover',"this.setCapture()");
        $('#albatross_acebundle_attachmenttype_attachinfo_' + index + '_project').attr('onmouseout',"this.releaseCapture()");
    }
}
function getAttachInfo(info) {
    $('#albatross_acebundle_attachmenttype_attachtype').val('upload');
    var collectionHolder = $('.options_iof');
    var $addTagLink = $('');
    var $newLinkLi = $('<span class="iof_empty_message"></span>').append($addTagLink);
    // add the "add a tag" anchor and li to the tags ul
    collectionHolder.append($newLinkLi);
    collectionHolder.data('index', collectionHolder.find(':input').length);
    if (info !== '') {
        info = info.split(',');
        for (var i = 0; i < info.length; i++) {
            // add a new tag form (see next code block)
            addTagFormIOF(collectionHolder, $newLinkLi, info[i]);
        }
    }
}
function projectFileFinish() {
    clearFileFormValue();
    $('#success_box').fadeIn();
    location.reload();
    $('#fileupload_project_form_box').fadeOut();
    window.setTimeout(function() {
        $("#cover_window_iof").fadeOut();
    }, 3000);
}
function getBrowserValue() {
    $('#albatross_acebundle_attachmenttype_text').val($('#albatross_acebundle_attachmenttype_file').val());
    var extStart = $('#albatross_acebundle_attachmenttype_file').val().lastIndexOf(".");
    var name = $('#albatross_acebundle_attachmenttype_file').val().substring(0, extStart);

    $('#albatross_acebundle_attachmenttype_label').val(name);
}
function validateIOF(){
    var checkValue = 0;
    if($('#iof_exit').val() == 0){
        $('.iof_empty_message').html('<font id="warning-message">WARNING: ACE project empty. Please bind ACE project to this wave.</font>');
        checkValue = 1;
    }
    if($('#albatross_acebundle_attachmenttype_text').val() == ''){
        $('#albatross_acebundle_attachmenttype_text').attr('placeholder', 'Please Upload File');
        checkValue = 1;
    }
    if(checkValue == 0){
        $('#iof_exit').val(0);
        $('#iof_submit').click();
    }
}