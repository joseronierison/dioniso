/**
 * Dioniso, Analysis tool safety
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0-standalone.html
 *
 * * Ações tomadas na página inicial
 * 
 * @category   Views
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       14.07.2013
 */

// Ao clicar no botão ceifar, está função é executada.
$("#button-ceifar").click(function(){
    if($("#button-ceifar").html() === '<i class="icon-remove icon-white"></i> Cancelar'){
        cancelarConsulta();
        return;
    }
    $('#ol-status-bar').html('');
    $('#div-dbs-tabs-content').fadeOut(500); 
    
    $("#button-ceifar").attr('class','btn btn-inverse');
    $("#button-ceifar").html('<i class="icon-remove icon-white"></i> Cancelar');
//    $.post(
//        "?module=triagem&controller=clinicogeral&action=examespreliminares", 
//        { 
//            url : $("#input-target-url").val()
//        }, 
//        function(response) {            
//            $('#div-modal-about').html(response);
//            $('#div-modal-about').modal('show');
//        } 
//    );
    showLoadingModal();
    
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=triagem&controller=clinicogeral&action=examespreliminares", 
        data: {
            url : $("#input-target-url").val()
        }, 
        success : function(response) {
            
            addResponseToStatusBar(response);
            
            if(response.status === true){
                switch(response.database){
                    case 'MySQL':  mysqlAtack(); break;
                }
            }
        }
    });
});

/**
 * Mostra Loading modal
 */
function showLoadingModal()
{
    $.post(
        "?module=index&controller=index&action=loading", 
        {  }, 
        function(response) {
            $('#div-modal-loading').html(response); 
            $('#div-modal-loading').modal({backdrop : false, keyboard : false}, 'show'); 
        } 
    );
}
/**
 * Cancela consulta requerida anteriormente
 */
function cancelarConsulta()
{
    $('#div-modal-loading').modal('hide'); 
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=index&controller=index&action=cancelarconsulta", 
        data: { }, 
        success : function(response) {            
            addResponseToStatusBar(response);
            alertMessage(response);            
        }
    });
    
    $("#button-ceifar").attr('class','btn btn-danger');
    $("#button-ceifar").html('<i class="icon-screenshot icon-white"></i> Ceifar');
}

/**
 * Abre aletar para monstrar mensagem
 * 
 * @param {object} response
 */
function alertMessage(response)
{
    messages = response.messages;
    for(count = 0; count < messages.length; count++){
        label = $('<i>').addClass('muted').html(messages[count].label+' : ');
        message = '';
        switch(messages[count].type){
            case 'dionisofala': //dionisofala
                litag = $('<li>').html(messages[count].message);
                content = $('<ul>').html(litag);
                message = $('<li>').html(label).append(content);
                break;
            break;
        }
        
               
    }
    
    $('#div-modal-alert-message-body').html(message); 
    $('#div-modal-alert-message').modal('show');
}

$("#form-ceifador").submit(function(){
   return false;
});


/**
 * Barra superior de ajuda e abouts
 */
$("#link-about-author").click(function(){ openWithAboutModal("?module=documentation&controller=about&action=author"); });
$("#link-about-dioniso").click(function(){ openWithAboutModal("?module=documentation&controller=about&action=dioniso"); });
$("#link-about-license").click(function(){ openWithAboutModal("?module=documentation&controller=about&action=license"); });
function openWithAboutModal(url)
{
    $.get(
        url, 
        { }, 
        function(response) {
            $('#div-modal-about').html(response); 
            $('#div-modal-about').modal('show'); 
        } 
    );
}


