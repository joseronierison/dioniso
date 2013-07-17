<?

class Zend_View_Helper_Pagination extends Zend_View_Helper_Abstract
{
    /*
     * Tag a ser construída.
     * 1ª %s = size
     * 2ª %s = align
     * 3ª %s = content
     */
    const HTMLMainTag = '
        <div class="pagination %s %s">
            <ul>
                %s
                %s
                %s
                %s
                %s
            </ul>
        </div>';
    
    
    /*
     * 
     */
    public function pagination($page, $datarows, $url, $limit = 30, $function = '_$NO', $getVar = 'page', $size = '', $align = 'pagination-centered', $maxActNumbers = 10)
    {
        $page = ($page > 0? $page : 1 );
        $datarows = ($datarows > 0? $datarows : 1);
        
        $MedPages = $datarows / $limit;

        $NumberOfPages = (round($MedPages) < $MedPages? round($MedPages) + 1 : round($MedPages));
        
        $First = '<li onclick="'.$function.'(\''.(($page != 1 && $function != '_$NO')? $url.'&'.$getVar.'=1' : '#' ).'\')" '.(($page == 1 )? 'class="disabled"': '').'>'.'<a href="'.(($page != 1 && $function == '_$NO')? $url.'&'.$getVar.'=1' : '#' ).'">&larr; Primeiro</a></li>';
        $Previous = '<li onclick="'.$function.'(\''.(($page != 1 && $function != '_$NO' )? $url.'&'.$getVar.'='.($page - 1) : '#').'\')" '.(($page == 1 )? 'class="disabled"': '').'><a href="'.(($page != 1 && $function == '_$NO' )? $url.'&'.$getVar.'='.($page - 1) : '#').'">Anterior</a></li>';
        
        $Next = '<li onclick="'.$function.'(\''.(($page != $NumberOfPages && $function != '_$NO' )? $url.'&'.$getVar.'='.($page + 1) : '#').'\')" '.(($page == $NumberOfPages)? 'class="disabled"' : '').'><a href="'.(($page != $NumberOfPages && $function == '_$NO' )? $url.'&'.$getVar.'='.($page + 1) : '#').'">Próximo</a></li>';
        $Last = '<li onclick="'.$function.'(\''.(($page != $NumberOfPages && $function != '_$NO' )? $url.'&'.$getVar.'='.$NumberOfPages : '#').'\')" '.(($page == $NumberOfPages)? 'class="disabled"' : '').'><a href="'.(($page != $NumberOfPages && $function == '_$NO' )? $url.'&'.$getVar.'='.$NumberOfPages : '#').'">Último &rarr;</a></li>';

        $MedIP = $page/$maxActNumbers;

        $RoundedIP = (round($MedIP) < $MedIP)? round($MedIP) + 1 : round($MedIP);

        $InitPaginator = (($RoundedIP-1)*$maxActNumbers)+1 ;
        $numericPaginators = '';
        for($Count = $InitPaginator; ($Count < $InitPaginator + $maxActNumbers and $Count <= $NumberOfPages); $Count++){
            $numericPaginators .= '<li onclick="'.$function.'(\''.$url.'&'.$getVar.'='.$Count.'\')" '.(($page == $Count)? 'class="active"' : '').'><a href="'.(($function == '_$NO' )?$url.'&'.$getVar.'='.$Count : '#').'">'.$Count.'</a></li>';
        }

        printf(self::HTMLMainTag, $size, $align, $First, $Previous, $numericPaginators, $Next, $Last);
    }
}

?>