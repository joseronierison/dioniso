<?

class Zend_View_Helper_Dateformat extends Zend_View_Helper_Abstract
{
    private static $monthByNumber = array(
        'Janeiro' => 1,
        'Fevereiro'=> 2,
        'Março' => 3,
        'Abril' => 4,
        'Maio' => 5,
        'Junho' => 6,
        'Julho' => 7,
        'Agosto' => 8,
        'Setembro' => 9,
        'Outubro' => 10,
        'Novembro' => 11,
        'Dezembro' => 12
    );
    
    private static $monthSimpleByNumber = array(
        'Jan' => 1,
        'Fev'=> 2,
        'Mar' => 3,
        'Abr' => 4,
        'Mai' => 5,
        'Jun' => 6,
        'Jul' => 7,
        'Ago' => 8,
        'Set' => 9,
        'Out' => 10,
        'Nov' => 11,
        'Dez' => 12
    );
    
    private static $monthByName = array(
        'Janeiro' => 'Jan',
        'Fevereiro'=> 'Feb',
        'Março' => 'Mar',
        'Abril' => 'Apr',
        'Maio' => 'May',
        'Junho' => 'Jun',
        'Julho' => 'Jul',
        'Agosto' => 'Aug',
        'Setembro' => 'Sep',
        'Outubro' => 'Oct',
        'Novembro' => 'Nov',
        'Dezembro' => 'Dec'
    );
    
    private static $dayByName = array(
        'Domingo' => 'Sun',
        'Segunda-feira' => 'Mon',
        'Terça-feira' => 'Tue',
        'Quarta-feira' => 'Wed',
        'Quinta-feira' => 'Thu',
        'Sexta-feira' => 'Fri',
        'Sábado' => 'Sat'
    );
    
    /*
     * Retorna a própria instância.
     */
    public function dateformat()
    {
        return $this;
    }
    
    /*
     * Transforma data para PTBR
     */
    public function monthPTBR($month = null)
    {
        if($month === null){
            $month = date('M');
        }
        
        if(is_numeric ($month)){
            $month = (int)$month;
            return array_search($month, self::$monthByNumber);
        }elseif(is_string($month)){
            return array_search($month, self::$monthByName);
        }
    }
    
    public function dayPTBR($day = null)
    {
        if($day === null){
            $day = date('D');
        }
        
        return array_search($day, self::$dayByName);
    }
    
    public function getCompleteDatePTBR()
    {
        return $this->dayPTBR().', '
                . date('d') . ' de '
                . $this->monthPTBR() . ' de '
                . date('Y');
    }
    
    public function getDateTimePTBR($date = null){
        if($date == null){
            $date = date('Y-m-d H:i:s');
        }
        
        return substr($date, 8, 2).'/'
            . substr($date, 5, 2).'/'
            . substr($date, 0, 4)
            . ' às '
            . substr($date, 11, 9);
    }
    
    public function getMonths()
    {
        return self::$monthByNumber;
    }
    
    public function getSMonths()
    {
        return self::$monthSimpleByNumber;
    }
}
?>
