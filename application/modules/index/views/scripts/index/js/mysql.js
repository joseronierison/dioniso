function mysqlAtack()
{
    $('#div-dbs-tabs').html('');
    countColumns();    
}

/**
 * Conta número de colunas para seleção UNION.
 * Caso dê erro é enviado para alert message modal
 * Caso dê sucessfull é enviado para findDbVersion();
 */
function countColumns()
{   
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=mysql&controller=cardiologista&action=contanumerodecolunas", 
        data: { }, 
        success : function(response) {            
            addResponseToStatusBar(response);
                
            if(response.status === true){
                findDbVersion();
            }else{
                alertMessage(response);
            }
        }
    });
}

/**
 * Procurar versão do banco de dados
 */
function findDbVersion()
{        
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=mysql&controller=cardiologista&action=examinaversaodobanco", 
        data: { }, 
        success : function(response) {
            
            addResponseToStatusBar(response);
                
            if(response.status === true){
                findDbs();
            }else{
                alertMessage(response);
            }
        }
    });
}

/**
 * Encontra banco de dados e os lista de forma horizontal
 */
function findDbs()
{
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=mysql&controller=cardiologista&action=extracaodebancos", 
        data: { }, 
        success : function(response) {
            addResponseToStatusBar(response);
            $('#div-modal-loading').modal('hide');
            
            if(response.status === true){
                for(cont = 0; cont < response.dbs.length; cont++){
                    a_dbmenu = $('<a>').attr('href', '#div-dbs-panel-content-'+cont).attr('data-toggle', 'tab').html('<i class="icon-hdd"></i> '+response.dbs[cont]);
                    li_dbmenu = $('<li>').attr('onclick', 'findTables(\''+cont+'\');').html(a_dbmenu);
                    
                    $('#div-dbs-tabs').append(li_dbmenu);
                    
                    ulTableTabs = $('<ul>').addClass('nav nav-tabs').attr('id', 'ul-table-tabs-'+cont);
                    divDbTableContent = $('<div>').attr('id', 'div-'+cont+'-tables-content').addClass('tab-content');
                    divTableTabs = $('<div>').addClass('tabbable tabs-left').attr('id', 'div-table-tabs-'+cont).html(ulTableTabs).append(divDbTableContent);
                    divTabPane = $('<div>').addClass('tab-pane').attr('id', 'div-dbs-panel-content-'+cont).html(divTableTabs);
                                        
                    $("#div-dbs-tabs-content").append(divTabPane);                    
                }
                $('#div-dbs-tabs').fadeIn(500);
                $('#div-dbs-tabs-content').fadeIn(500);                
            }else{
                alertMessage(response);
            }
        }
    });
}

/**
 * Pega tabelas de um determinado banco.
 * 
 * @param {Integer} db_key
 * @void
 */
function findTables(db_key)
{
    showLoadingModal();
    
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=mysql&controller=cardiologista&action=extracaodetabelas", 
        data: { db_key : db_key }, 
        success : function(response) {
            addResponseToStatusBar(response);
            
            $('#div-modal-loading').modal('hide');
            $('#ul-table-tabs-'+db_key).html('');
            $('#div-'+db_key+'-tables-content').html('');
            
            if(response.status === true){
                for(cont = 0; cont < response.tables.length; cont++){
                    iconTableTab = $('<i>').addClass('icon-th-list');
                    aTableTab = $('<a>').attr('href', '#tab-panel-table-'+db_key+'-'+cont).attr('data-toggle', 'tab').html(iconTableTab).append(' '+response.tables[cont]);
                    liTableTab = $('<li>').attr('onclick', 'findColumns('+db_key+','+cont+');').html(aTableTab);
                    
                    divTabPanel = $('<div>').addClass('tab-pane').attr('id', 'tab-panel-table-'+db_key+'-'+cont).html('Carrengando conteúdo da tabela '+response.tables[cont]+' ..');
                    
                    $('#ul-table-tabs-'+db_key).append(liTableTab);
                    $('#div-'+db_key+'-tables-content').append(divTabPanel);
                }
            }else{
                alertMessage(response);
            }
            
        }
    });
}

/**
 * Busca colunas da tabela do banco de dados
 * 
 * @param {Integer} db_key
 * @param {Integer} table_key
 */
function findColumns(db_key, table_key)
{
    showLoadingModal();
    
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=mysql&controller=cardiologista&action=extracaodecolunas", 
        data: { 
            table_key : table_key,
            db_key : db_key
        }, 
        success : function(response) {
            addResponseToStatusBar(response);
            
            $('#div-modal-loading').modal('hide');
            
            if(response.status === true){
                
                tbodyTag = $('<tbody>').attr('style', 'background-color: #cecece;').attr('id', 'tbody-table-'+db_key+'-'+table_key);
                trTag = $('<tr>').attr('id', 'tr-columns-'+db_key+'-'+table_key);
                theadTag = $('<thead>').html(trTag);
                tableTag = $('<table>').addClass('table table-bordered table-hover').html(theadTag).append(tbodyTag);
                
                buttonTag = $('<button>').attr('style', 'float:right; margin: 5px;').attr('type', 'button').attr('onclick', 'getTableData('+db_key+','+table_key+');').addClass('btn btn-info').html('Pegar Dados');
                $('#tab-panel-table-'+db_key+'-'+table_key).html(buttonTag);
                
                $('#tab-panel-table-'+db_key+'-'+table_key).append(tableTag);
            
                for(cont = 0; cont < response.columns.length; cont++){
                    $('#tr-columns-'+db_key+'-'+table_key).append('<th>'+response.columns[cont]+'</th>');
                }
                
            }else{
                alertMessage(response);
            }
            
        }
    });
}

/**
 * Pega dados de uma tabela
 * 
 * @param {Integer} db_key
 * @param {Integer} table_key
 */
function getTableData(db_key, table_key)
{
    showLoadingModal();
    
    $.ajax({
        type : "POST",
        dataType : "json",
        url: "?module=mysql&controller=cardiologista&action=extracaodedados", 
        data: { 
            table_key : table_key,
            db_key : db_key
        }, 
        success : function(response) {
            addResponseToStatusBar(response);
            
            $('#div-modal-loading').modal('hide');
            
            if(response.status === true){
                for(cont = 0; cont < response.data.length ; cont++){
                    trTag = $('<tr>').attr('id', 'tr-data-'+db_key+'-'+table_key+'-'+cont);
                    $('#tbody-table-'+db_key+'-'+table_key).append(trTag);
                    
                    for(cont1 = 0; cont1 < response.data[cont].length; cont1++){
                        $('#tr-data-'+db_key+'-'+table_key+'-'+cont).append('<td>'+response.data[cont][cont1]+'</td>');
                    }
                }
            }else{
                alertMessage(response);
            }
            
        }
    });
}

