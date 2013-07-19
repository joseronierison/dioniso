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
 * * Insere informações na Status-bar
 * 
 * @category   Views
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       15.07.2013
 */

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


