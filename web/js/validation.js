/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    $('#albatross_custombundle_customfieldtype_question_file1_label').click(function(){
        if($('#albatross_custombundle_customfieldtype_question_file1_label').val() === 'Please fill in.'){
            $('#albatross_custombundle_customfieldtype_question_file1_label').val('');
            $('#albatross_custombundle_customfieldtype_question_file1_label').css('color', 'black');
        }
    });
    
    $('#albatross_custombundle_customfieldtype_question_file2_label').click(function(){
        if($('#albatross_custombundle_customfieldtype_question_file2_label').val() === 'Please fill in.'){
            $('#albatross_custombundle_customfieldtype_question_file2_label').val('');
            $('#albatross_custombundle_customfieldtype_question_file2_label').css('color', 'black');
        }
    });
        
    $('#albatross_custombundle_customfieldtype_question_file3_label').click(function(){
        if($('#albatross_custombundle_customfieldtype_question_file3_label').val() === 'Please fill in.'){
            $('#albatross_custombundle_customfieldtype_question_file3_label').val('');
            $('#albatross_custombundle_customfieldtype_question_file3_label').css('color', 'black');
        }
    });    
    
    $('#albatross_custombundle_customfieldtype_question_file4_label').click(function(){
        if($('#albatross_custombundle_customfieldtype_question_file4_label').val() === 'Please fill in.'){
            $('#albatross_custombundle_customfieldtype_question_file4_label').val('');
            $('#albatross_custombundle_customfieldtype_question_file4_label').css('color', 'black');
        }
    });
    
    $('#albatross_custombundle_customfieldtype_path').blur(function(){
        if($('#albatross_custombundle_customfieldtype_path').val() !== 'Please fill in.'){
            $('#albatross_custombundle_customfieldtype_path').css('color', 'black');
        }
    });
});
function validation(){
    var index = $('#form_index').val();
    if(index === 'mm'){
        checkMMForm();
    }
    if(index === 'material'){
        checkMaterialForm();
    }
    if(index === 'recap'){
        checkRecapForm();
    }
    if(index === 'questionnaire'){
        checkQuestionForm();
    }
    if(index === 'questionsign'){
        return checkQuestionSignForm();
    }
    if(index === 'questionsign2'){
        return checkQuestionSignForm2();
    }
    if(index === 'posupload'){
        checkPosUpload();
    }
    if(index === 'brief'){
        checkBrief();
    }
    if(index === 'dic'){
        checkDic();
    }
    if(index === 'report'){
        checkReport();
    }
}

function submitForm(){
    $('#window-cover').show();
    $('#fieldform').submit();
}
function submitFormRecap(){
    $('#window-cover').show();
    $('#recap_edit_form').submit();
}
function validatePos(){
    $('#form_index').val('posupload');
    validation();
}
function checkMMForm(){
    var checkValue = 0;
    $('#big_table font').remove();
    
    if($('#albatross_custombundle_customfieldtype_mm_brand').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_brand').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_mm_date').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_date').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }    
    if($('#albatross_custombundle_customfieldtype_mm_address').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_address').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }    
    if($('#albatross_custombundle_customfieldtype_mm_purpose').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_purpose').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }    
    if($('#albatross_custombundle_customfieldtype_mm_nextstep').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_nextstep').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }    
    if($('#albatross_custombundle_customfieldtype_mm_agenda_of_the_meeting').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_agenda_of_the_meeting').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }    
    if($('#albatross_custombundle_customfieldtype_mm_clients_feedback').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_clients_feedback').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_mm_comments').val() === ''){
        $('#albatross_custombundle_customfieldtype_mm_comments').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    
    if(checkValue === 0){
        submitForm();
    }
}

function checkMaterialForm(){
    var checkValue = 0;
    $('#big_table font').remove();
    if($('#albatross_custombundle_customfieldtype_path').val() === ''){
        $('#albatross_custombundle_customfieldtype_path').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_path').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_material_name').val() === ''){
        $('#albatross_custombundle_customfieldtype_material_name').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if(checkValue === 0){
        submitForm();
    }
}

function checkRecapForm(){
    var checkValue = 0;
    $('#recap_box font').remove();
    if($('#albatross_custombundle_recaptype_name').val() === ''){
        $('#albatross_custombundle_recaptype_name').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if($('#recap_country').html() === 'No country info from POS list.'){
        $('#recap_country').html('<font color="red">Have to choose country.</font>');
    }
    if($('#aolquestionnaire').html() === 'No aol questionnaire.'){
        $('#aolquestionnaire').html('<font color="red">Have to choose questionnaire.</font>');
    }
    
    if($('#albatross_custombundle_recaptype_planned_surveys').val() === ''){
        $('#albatross_custombundle_recaptype_planned_surveys').val('Please fill in.');
        $('#albatross_custombundle_recaptype_planned_surveys').css('color', 'red');
    }
    if($('#albatross_custombundle_recaptype_planned_pos').val() === ''){
        $('#albatross_custombundle_recaptype_planned_pos').val('Please fill in.');
        $('#albatross_custombundle_recaptype_planned_pos').css('color', 'red');
    }
    
    if($('#albatross_custombundle_recaptype_actual_pos').val() === ''){
        $('#albatross_custombundle_recaptype_actual_pos').val('Please fill in.');
        $('#albatross_custombundle_recaptype_actual_pos').css('color', 'red');
    }
    if($('#albatross_custombundle_recaptype_actual_surveys').val() === ''){
        $('#albatross_custombundle_recaptype_actual_surveys').val('Please fill in.');
        $('#albatross_custombundle_recaptype_actual_surveys').css('color', 'red');
    }
    if($('#albatross_custombundle_recaptype_actual_misfire').val() === ''){
        $('#albatross_custombundle_recaptype_actual_misfire').val('Please fill in.');
        $('#albatross_custombundle_recaptype_actual_misfire').css('color', 'red');
    }
    if($('#albatross_custombundle_recaptype_actual_invalid').val() === ''){
        $('#albatross_custombundle_recaptype_actual_invalid').val('Please fill in.');
        $('#albatross_custombundle_recaptype_actual_invalid').css('color', 'red');
    }
    
    if(checkValue === 0){
        submitFormRecap();
    }
}

function checkQuestionForm(){
    var checkValue = 0;
    $('#big_table font').remove();
    if($('#albatross_custombundle_customfieldtype_path').val() === '' || $('#albatross_custombundle_customfieldtype_path').val() === 'Please fill in.'){
        $('#albatross_custombundle_customfieldtype_path').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_path').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_question_file1_label').val() === '' || $('#albatross_custombundle_customfieldtype_question_file1_label').val() === 'Please fill in.'){
        $('#albatross_custombundle_customfieldtype_question_file1_label').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_question_file1_label').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_path_2').val() !== '' && $('#albatross_custombundle_customfieldtype_question_file2_label').val() === ''){
        $('#albatross_custombundle_customfieldtype_question_file2_label').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_question_file2_label').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_path_3').val() !== '' && $('#albatross_custombundle_customfieldtype_question_file3_label').val() === ''){
        $('#albatross_custombundle_customfieldtype_question_file3_label').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_question_file3_label').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_path_4').val() !== '' && $('#albatross_custombundle_customfieldtype_question_file4_label').val() === ''){
        $('#albatross_custombundle_customfieldtype_question_file4_label').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_question_file4_label').css('color', 'red');
        checkValue = 1;
    }
    
    if(checkValue === 0){
        $('#window-cover').show();
        submitForm();
    }
}

function checkQuestionSignForm(){
    var checkValue = 0;
    $('#field_info font').remove();
    if($('#signature_value').val() === ''){
        $('#signature_value').next().after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if(checkValue === 0){
        return 1;
    }else{
        return 0;
    }
}
function checkQuestionSignForm2(){
    var checkValue = 0;
    $('#field_info font').remove();
    if($('#signature_value').val() === ''){
        $('#signature_value').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    $('.aol_questionnaire').each(function(){
        if($(this).val() === ''){
            $(this).after('<font color="red">Please fill in.</font>');
            checkValue = 1;
        }
    });
    if(checkValue === 0){
        return 1;
    }else{
        return 0;
    }
}

function checkPosUpload(){
    var checkValue = 0;
    $('#poslist_upload font').remove();
    if($('#albatross_custombundle_poslisttype_path').val() === ''){
        $('#albatross_custombundle_poslisttype_path').after('<font color="red">Please upload file.</font>');
        checkValue = 1;
    }
    if(checkValue === 0){
        $('#window-cover').show();
        $('#pos-upload-form').submit();
    }
}

function checkBrief(){
    var checkValue = 0;
    $('#big_table font').remove();
    if($('#albatross_custombundle_customfieldtype_path').val() === ''){
        $('#albatross_custombundle_customfieldtype_path').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_path').css('color', 'red');
        checkValue = 1;
    }
    if(!$("#albatross_custombundle_customfieldtype_main_brief").attr("checked") && $('#albatross_custombundle_customfieldtype_brief_translation').val() === ''){
        $('#albatross_custombundle_customfieldtype_brief_translation').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if(checkValue === 0){
        submitForm();
    }
}

function checkDic(){
    var checkValue = 0;
    $('#big_table font').remove();
    if($('#albatross_custombundle_customfieldtype_path').val() === ''){
        $('#albatross_custombundle_customfieldtype_path').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_path').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_question_file1_label').val() === ''){
        $('#albatross_custombundle_customfieldtype_question_file1_label').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if(checkValue === 0){
        submitForm();
    }
}

function checkReport(){
    var checkValue = 0;
    $('#big_table font').remove();
    if($('#albatross_custombundle_customfieldtype_path').val() === ''){
        $('#albatross_custombundle_customfieldtype_path').val('Please fill in.');
        $('#albatross_custombundle_customfieldtype_path').css('color', 'red');
        checkValue = 1;
    }
    if($('#albatross_custombundle_customfieldtype_report_type').val() === ''){
        $('#albatross_custombundle_customfieldtype_report_type').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if(!$("#albatross_custombundle_customfieldtype_report_executive").attr("checked") && $('#albatross_custombundle_customfieldtype_report_zone').val() === ''){
        $('#albatross_custombundle_customfieldtype_report_zone').after('<font color="red">Please fill in.</font>');
        checkValue = 1;
    }
    if(checkValue === 0){
        submitForm();
    }
}