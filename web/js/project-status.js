$(document).ready(function() {
    $('.multi_choose').chosen({width: "100%"});
    $('#search-form-type').val('aol');
    
    acceptQuestionnaireSelectForm();
    acceptbuSelectForm();
    acceptBrandSelectForm();
    acceptcountrySelectForm();
    $('#albatross_operationbundle_projectstatusaoltype_fw_s_f').datepicker();
    $('#albatross_operationbundle_projectstatusaoltype_fw_s_t').datepicker();
    $('#albatross_operationbundle_projectstatusaoltype_fw_e_f').datepicker();
    $('#albatross_operationbundle_projectstatusaoltype_fw_e_t').datepicker();
    $('#albatross_operationbundle_projectstatusaoltype_due_f').datepicker();
    $('#albatross_operationbundle_projectstatusaoltype_due_t').datepicker();
    
    $('#albatross_operationbundle_projectstatusacetype_fw_s_f').datepicker();
    $('#albatross_operationbundle_projectstatusacetype_fw_s_t').datepicker();
    $('#albatross_operationbundle_projectstatusacetype_fw_e_f').datepicker();
    $('#albatross_operationbundle_projectstatusacetype_fw_e_t').datepicker();
    $('#albatross_operationbundle_projectstatusacetype_due_f').datepicker();
    $('#albatross_operationbundle_projectstatusacetype_due_t').datepicker();
    
    //============================================================data Table
    $('#operation-result-table').dataTable({
                "aaSorting": [[0, 'asc']],
                "bFilter": true,
                "bSort": true,
                "iDisplayLength": 50
     });
    //============================================================data Table
//    $(window.document).scroll(function(){
//            if($(window.document).scrollTop() > '388'){
//              $('#status-q-title').css('position', 'fixed');
//              $('#status-q-title').css('top', '0');
//              $('#status-ace-title').css('position', 'fixed');
//              $('#status-ace-title').css('top', '0');
//            }
//            if($(window.document).scrollTop() < '500'){
//              $('#title-name').width('450px');
//              $('#status-q-title').css('position', 'static');
//              $('#status-ace-title').css('position', 'static');
//            }
//    });
    
//    $('#count-box').click(function(){
//        var indexTotal = 0;
//        var aolTotal = 0;
//        $('.questionnaire-result').each(function(){
//            indexTotal = indexTotal + 1;
//            if($(this).find('td:eq(3)').html() !== 'none'){
//                aolTotal = aolTotal + parseInt($(this).find('td:eq(3)').html());
//            }
//        });
//        $('#count-box').animate({width:'220px'}, "normal", "swing", function(){
//            $('#total-title').html('Number of Results: ');
//            $('#aol-title').html('Total No. surveys AOL: ');
//            $('#total').html(indexTotal);
//            $('#aol').html(aolTotal);
//        });
//    });
    
    $('#reset-btn-aol').click(function(){
        $('#questionnaire-search-form .multi_choose').each(function(){
            $(this).val('');
            $(this).trigger("liszt:updated");
            $(this).parent().parent().parent().parent().parent().parent().prev().html('');
        });
        $('#questionnaire-search-form .surveySelection').each(function(){
            if($(this).attr('id') !== 'surveySelection_1'){
                $(this).parent().parent().remove();
            }else{
                $(this).val('');
                $(this).trigger("liszt:updated");
            }
            $('#selected-questionnaire').html('');
        });
        $('#questionnaire-search-form .campaignSelection').each(function(){
            $(this).val('');
            $(this).trigger("liszt:updated");
        });
    });
    
    $('#reset-btn-ace').click(function(){
        $('#aceproject-search-form .multi_choose').each(function(){
            $(this).val('');
            $(this).trigger("liszt:updated");
            $(this).parent().parent().parent().parent().parent().parent().prev().html('');
        });
    });
    
    $('#count-box').mouseleave(function(){
        $('#total').html('');
        $('#aol').html('');
        $('#total-title').html('');
        $('#aol-title').html('');
        $('#count-box').animate({width:'20px'});
    });
    
    $('.aol-add-search').hide();
    $('.ace-add-search').hide();
    
    $('#advLink2-aol').click(function(){
        $('.aol-add-search').toggle('slow');
        $('#advLink2-aol i').toggleClass('icon-minus-sign');
    });
    $('#advLink2-ace').click(function(){
        $('.ace-add-search').toggle('slow');
        $('#advLink2-ace i').toggleClass('icon-minus-sign');
    });
});

function showFormAce(){
    if($('#search-form-type').val() === 'aol'){
        $('#aceproject-search-form-box').attr('class', 'onstyle-ace');
        $('#aceproject-cover-box').css('display', 'none');
        $('#aceproject-search-form-box').animate({width:'80%'}, "normal", "swing", function(){ $('#search-box').animate({height:'418px'}, "normal", "swing", function(){$('#aceproject-search-form').show();});});
        $('#questionnaire-search-form').hide();
        $('#questionnaire-search-form-box').animate({width:'20%'}, "normal", "swing", function(){
            $('#questionnaire-cover-box').show();
            $('#questionnaire-search-form-box').attr('class', 'offstyle-aol');
        });
        $('#search-form-type').val('ace');
        
        acceptAceSelectForm();
        acceptacenumberSelectForm();
        acceptBrandAceSelectForm();
        acceptPmSelectForm();
        acceptbuaceSelectForm();
        acceptcountryaceSelectForm();
    }
}

function showFormAol(){
    if($('#search-form-type').val() === 'ace'){
        $('#questionnaire-search-form-box').attr('class', 'onstyle-aol');
        $('#questionnaire-cover-box').css('display', 'none');
        $('#questionnaire-search-form-box').animate({width:'80%'}, "normal", "swing", function(){$('#search-box').animate({height:'390px'}, "normal", "swing", function(){$('#questionnaire-search-form').show();});});
        $('#aceproject-search-form').hide();
        $('#aceproject-search-form-box').animate({width:'20%'}, "normal", "swing", function(){
            $('#aceproject-cover-box').show();
            $('#aceproject-search-form-box').attr('class', 'offstyle-ace');
        });
        $('#search-form-type').val('aol');
        
        acceptQuestionnaireSelectForm();
        acceptbuSelectForm();
        acceptBrandSelectForm();
        acceptcountrySelectForm();
    }
}

function showQuestionnaireSelect() {
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#questionnaire-select-table-box').show();
    $('.surveySelection').chosen();
    $('.campaignSelection').chosen();
}

function deleteSurvey(obj){
    if($(obj).parent().parent().children('td[class="survey"]').children('select').attr('id') !== 'surveySelection_1'){
        $(obj).parent().parent().remove();
    }else{
        $('#surveySelection_1').val('');
        $('#surveySelection_1').trigger("liszt:updated");
        $('#campaign_surveySelection_1').val('');
        $('#campaign_surveySelection_1').trigger("liszt:updated");
    }
}

function acceptQuestionnaireSelectForm(){
    var seleted_questionnaire = '';
    var title = '';
    $('td[class="survey"]').each(function(){
        if($(this).find("option:selected").text().length > 10){
            seleted_questionnaire = seleted_questionnaire + $(this).find("option:selected").text().substring(0, 10) + "...; ";
        }else if($(this).find("option:selected").text().length === 0){
            seleted_questionnaire = '';
        }else{
            seleted_questionnaire = seleted_questionnaire + $(this).find("option:selected").text()+ "; ";
        }
        title = title + $(this).find("option:selected").text() + "; ";
    });
    $('#selected-questionnaire').html(seleted_questionnaire);
    $('#selected-questionnaire').attr('title', title);
    $('#questionnaire-select-table-box').hide();
}
function closeQuestionnaireSelectForm(){
    $('#questionnaire-select-table-box').hide();
}
//brand=======================================================================
function showBrandSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#brand-select-table-box').show();
}
function acceptBrandSelectForm(){
    var seleted_brand = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusaoltype_brand').find("option:selected").each(function(){
        if($(this).text().length > 10){
            seleted_brand = seleted_brand + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            seleted_brand = '';
        }else{
            seleted_brand = seleted_brand + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-brand').html(seleted_brand);
    $('#selected-brand').attr('title', title);
    $('#brand-select-table-box').hide();
}
function closeBrandSelectForm(){
    $('#brand-select-table-box').hide();
}
//bu============================================================================
function showBuSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#bu-select-table-box').show();
}
function acceptbuSelectForm(){
    var selected_bu = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusaoltype_bu').find("option:selected").each(function(){
        if($(this).text().length > 10){
            selected_bu = selected_bu + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            selected_bu = '';
        }else{
            selected_bu = selected_bu + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-bu').html(selected_bu);
    $('#selected-bu').attr('title', title);
    $('#bu-select-table-box').hide();
}
function closebuSelectForm(){
    $('#bu-select-table-box').hide();
}
//country=======================================================================
function showCountrySelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#country-select-table-box').show();
}
function acceptcountrySelectForm(){
    var selected_country = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusaoltype_country').find("option:selected").each(function(){
        if($(this).text().length > 10){
            selected_country = selected_country + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            selected_country = '';
        }else{
            selected_country = selected_country + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-country').html(selected_country);
    $('#selected-country').attr('title', title);
    $('#country-select-table-box').hide();
}
function closecountrySelectForm(){
    $('#country-select-table-box').hide();
}
//====================================ace project form==========================
//ace name
function showacenameSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#acename-select-table-box').show();
}
function acceptAceSelectForm(){
    var seleted_ace = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusacetype_ace').find("option:selected").each(function(){
        if($(this).text().length > 10){
            seleted_ace = seleted_ace + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            seleted_ace = '';
        }else{
            seleted_ace = seleted_ace + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-acename').html(seleted_ace);
    $('#selected-acename').attr('title', title);
    $('#acename-select-table-box').hide();
}

function closeAceSelectForm(){
    $('#acename-select-table-box').hide();
}
//ace number
function showacenumberSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#acenumber-select-table-box').show();
}
function acceptacenumberSelectForm(){
    var seleted_acenumber = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusacetype_acenumber').find("option:selected").each(function(){
        if($(this).text().length > 10){
            seleted_acenumber = seleted_acenumber + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            seleted_acenumber = '';
        }else{
            seleted_acenumber = seleted_acenumber + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-acenumber').html(seleted_acenumber);
    $('#selected-acenumber').attr('title', title);
    $('#acenumber-select-table-box').hide();
}
function closeacenumberSelectForm(){
    $('#acenumber-select-table-box').hide();
}
//ace brand
function showBrandAceSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#brandace-select-table-box').show();
}
function acceptBrandAceSelectForm(){
    var seleted_brand = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusacetype_brand').find("option:selected").each(function(){
        if($(this).text().length > 10){
            seleted_brand = seleted_brand + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            seleted_brand = '';
        }else{
            seleted_brand = seleted_brand + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-brandace').html(seleted_brand);
    $('#selected-brandace').attr('title', title);
    $('#brandace-select-table-box').hide();
}
function closeBrandAceSelectForm(){
    $('#brandace-select-table-box').hide();
}
//pm
function showPmSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#pm-select-table-box').show();
}
function acceptPmSelectForm(){
    var seleted_pm = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusacetype_pm').find("option:selected").each(function(){
        if($(this).text().length > 10){
            seleted_pm = seleted_pm + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            seleted_pm = '';
        }else{
            seleted_pm = seleted_pm + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-pm').html(seleted_pm);
    $('#selected-pm').attr('title', title);
    $('#pm-select-table-box').hide();
}
function closePmSelectForm(){
    $('#pm-select-table-box').hide();
}
//bu============================================================================
function showBuaceSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#buace-select-table-box').show();
}
function acceptbuaceSelectForm(){
    var selected_bu = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusacetype_bu').find("option:selected").each(function(){
        if($(this).text().length > 10){
            selected_bu = selected_bu + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            selected_bu = '';
        }else{
            selected_bu = selected_bu + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-buace').html(selected_bu);
    $('#selected-buace').attr('title', title);
    $('#buace-select-table-box').hide();
}
function closebuaceSelectForm(){
    $('#buace-select-table-box').hide();
}
//country=======================================================================
function showCountryaceSelect(){
    $('.select-box').each(function(){
        $(this).hide();
    });
    $('#countryace-select-table-box').show();
}
function acceptcountryaceSelectForm(){
    var selected_country = '';
    var title = '';
    $('#albatross_operationbundle_projectstatusacetype_country').find("option:selected").each(function(){
        if($(this).text().length > 10){
            selected_country = selected_country + $(this).text().substring(0, 10) + "...; ";
        }else if($(this).text().length === 0){
            selected_country = '';
        }else{
            selected_country = selected_country + $(this).text() + "; ";
        }
        title = title + $(this).text() + "; ";
    });
    $('#selected-countryace').html(selected_country);
    $('#selected-countryace').attr('title', title);
    $('#countryace-select-table-box').hide();
}
function closecountryaceSelectForm(){
    $('#countryace-select-table-box').hide();
}

