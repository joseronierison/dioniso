<?
/*
 * Tem funções de front-end que dizem respeito a segurança.
 * 
 * @date 18 de Maio de 2013
 * @authro José Roniérison Santos Silva
 */
class Zend_View_Helper_Security extends Zend_View_Helper_Abstract
{
    private static $positivePoints = array(
        'strlen' => 4,
        'uppercaseletters' => 2, //(len * n) * 2
        'lowercaseletters' => 2, //(len * n) * 2
        'number' => 4,
        'symbols'   => 6,        
        'middlenumbersorsymbols' => 2,
        'requirements' => 2 // número de requisitos aceitos > 5
    );
    
    private static $negativePoints = array(
        'lettersonly' => 1,
        'numbersonly' => 1,
        'repeatcharacters' => 1,
        'consecutiveuppercaseletters' => 2,
        'consecutivelowercaseletters' => 2,
        'consecutivenumbers' => 2,
        'sequentialletters' => 3,
        'sequentialenumbers' => 3,
        'sequentialsymbols' => 3
    );
    
    private static $symbols = array(
        "'", '"', "!", "@", "#", "$", "%", "¨", "&", "*", "(", ")", "+", "-", 
        '=', '`', '´', '~', '^', ':', ';', '>', '<', '/', '\\', '|', '{', '}',
        '[', ']', '?', '¬', '¢', '£'
    );
    
    /*
     * retorna a própria instância
     */
    public function security()
    {
        return $this;
    }
    
    public function checkPassword($password)
    {
        $point = 0;
        
        $point += strlen($password) * self::$positivePoints['strlen'];
        
        
        foreach(self::$symbols as $symbol){
            $point += substr_count($password, $symbol) * self::$positivePoints['symbols'];
        }
        
        if(@ereg('[A-Z]', $password)){
            echo 'Tem letras maiúsculas <br />';
        }
        
        echo 'Point: '.$point.'<br />';
        
//        foreach (count_chars($data, 1) as $i => $val) {
//            echo "There were $val instance(s) of \"" , chr($i) , "\" in the string.\n";
//        }
    }
    
}
?>
