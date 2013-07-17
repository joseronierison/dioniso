/**
 * Adiciona resposta à barra de status
 * 
 * @param response {objet} Objeto de resposta
 */
function addResponseToStatusBar(response)
{
    //Classe de status
    classe = (response.status === true ? 'text-success' : 'text-error');
    
    messages = response.messages;
    for(count = 0; count < messages.length; count++){
        label = $('<i>').addClass('muted').html(messages[count].label+' : ');
        
        switch(messages[count].type){
            case 'classe': //classe
                content = $('<b>').addClass(classe).html(messages[count].message);
                message = $('<li>').html(label).append(content);
                $('#ol-status-bar').append(message); 
                break;
            case 'command': //comando
                litag = $('<li>').addClass('text-command').html(messages[count].message);
                content = $('<ul>').html(litag);
                message = $('<li>').html(label).append(content);
                $('#ol-status-bar').append(message); 
                break;
            case 'info': 
                content = $('<b>').addClass('text-info').html(messages[count].message);
                message = $('<li>').html(label).append(content);
                $('#ol-status-bar').append(message); 
                break;
        }       
    }
    
    $('#div-status-bar').animate({ scrollTop: $("#pre-status-bar").height() }, 2000);
}


