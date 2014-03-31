<?php
/**
 * Description of \sys\layout
 *
 * @author Rodrigo
 */

class layout {
    
    const
        contentType = 0,
        defaultStyle = 1,
        refresh = 2;

    static protected
        $layout = 'lliure';

    static private 
        $DocsHeaderPOS = array(),
        $DocsHeaderPRE = array(),
        $DocsHeader,
        $DocsFooter = array(),
        $Metas = array(),
        $htmlSattus = true,
        $layoutStatus = true,
        $showDocs = true,
        $content = '';

    //****************************************************************************/
    //*** Parte de configuracao de variaveis do layout ***************************/
    //****************************************************************************/
    
    /**
     * Configura as fariaveis inicias do layout. Nessesario para seu uso.
     * @return TRUE
     */
    public static function start(){
        self::$DocsHeaderPOS = array();
        self::$DocsHeaderPRE = array();
        self::$DocsHeader = &self::$DocsHeaderPRE;
        self::$DocsFooter = array();
        self::$Metas = array();
        self::$htmlSattus = true;
        self::$layoutStatus = true;
        self::$showDocs = true;
        self::$content = '';
        return TRUE;
    }

    /**
     * Altera a configuracao de onde esta senco colocada os doumentos do header.
     * Nessesario para os documentos da app ter prioridade sobre os documentos do layout.
     * @return TRUE
     */
    public static function setStartLayout(){
        self::$DocsHeader = &self::$DocsHeaderPOS;
        return TRUE;
    }
    
    
    
    
    //****************************************************************************/
    //*** Define o layout que voce esta trabalhando ******************************/
    //****************************************************************************/
    
    /**
     * Configura o layout que o lliure ira usar.
     * 
     * @return bool Retorna <b>TRUE</b> caso o layout exista ou <b>FALSE</b> se não existir.
     */
    public static function setLayout($layout){
        if (file_exists('layout'. '/'. $layout. '/'. $layout. '.php')){
            self::$layout = $layout;
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * retorn o caminho do layout que voce esta usando.
     * @return string
     */
    public static function getLayoutPath(){
        return 'layout'. '/'. self::$layout;
    }
    
    /**
     * Apelido para <var>getLayoutPath()</var>.
     * @return string
     */
    public static function getPathLayout(){
        return self::getLayoutPath();
    }
    
    /**
     * retorna o layout que voce esta usando ou o arquivo de entrada do layout.
     * @param bool $path se <b>TRUE</b> retorna o camihor para o arquivo principal do layout, se <b>FALSE</b> retorna somente o layout.
     * @return string
     */
    public static function getLayot($path = FALSE){
        return ($path? self::getLayoutPath() . '/'. self::$layout. '.php': self::$layout);
    }








    //****************************************************************************/
    //*** Documentos do layout ***************************************************/
    //****************************************************************************/

    /**
     * Adiciona um documento a lista de documentos do head
     * documento este pode ser passado de 3 formas.
     * 
     * 1ª - somente o nome do arquivo.
     * <b>pasta/arquivo.css</b> ou <b>../pasta/pasta/arquivo.css</b>
     * 
     * 2ª - tipo mais nome.
     * <b>css:pasta/arquivo.css</b> ou <b>css:../pasta/pasta/arquivo.css</b>
     * 
     * 3ª - tipo mais nome como array.
     * <b>array('css' => 'pasta/arquivo.css');</b> ou <b> array('css' => '../pasta/pasta/arquivo.css');</b>
     * 
     * @tipos tipos aceitos <i>css</i>, <i>js</i> e <i>ico</i>.
     * 
     * @param sting|array $documento com o nome e o tipo do arquivos
     */
    static function addDocHead($documento){
        self::addDoc(self::$DocsHeader, $documento);
    }

    /**
     * Adiciona um documento a lista de documentos do head
     * documento este pode ser passado de 3 formas.
     * 
     * 1ª - somente o nome do arquivo.
     * <b>pasta/arquivo.css</b> ou <b>../pasta/pasta/arquivo.css</b>
     * 
     * 2ª - tipo mais nome.
     * <b>css:pasta/arquivo.css</b> ou <b>css:../pasta/pasta/arquivo.css</b>
     * 
     * 3ª - tipo mais nome como array.
     * <b>array('css' => 'pasta/arquivo.css');</b> ou <b> array('css' => '../pasta/pasta/arquivo.css');</b>
     * 
     * @tipos tipos aceitos <i>css</i>, <i>js</i> e <i>ico</i>.
     * 
     * @param sting|array $documento com o nome e o tipo do arquivos
     */
    static function addDocFooter($documento){
        self::addDoc(self::$DocsFooter, $documento);
    }
    
    private static function addDoc(array &$array, $documento){
        if (!in_array($documento, $array)) {
            $array[] = $documento;
        }
    }

    /**
    * require todos os documentos da lista no head
    */
    private static function getDocsHead(){
        self::getDocs(self::$DocsHeaderPOS);
        self::getDocs(self::$DocsHeaderPRE);
    }
    
    /**
     * require todos os documentos da lista no footer
     */
    private static function getDocsFooter(){
        self::getDocs(self::$DocsFooter);
    }

    private static function getDocs(array &$docs) {
        if (!empty($docs)) {
            foreach ($docs as $valor) {
                if(is_array($valor)){
                    list($ext, $valor) = each($valor);                    
                }elseif (strpos($valor, ':') !== FALSE){
                    $e = explode(":", $valor, 2);
                    $ext = array_shift($e);
                    $valor = array_shift($e);
                }else{
                    $e = explode("?", $valor);
                    $e = explode(".", $e[0]);
                    $ext = strtolower(end($e));
                }
                switch ($ext){
                    case 'css':
                        echo '<link type="text/css" rel="stylesheet" href="'. $valor. '"/>';
                    break;
                    case 'js':
                        echo '<script type="text/javascript" src="'. $valor. '"></script>';
                    break;
                    case 'ico':
                        echo '<link type="image/x-icon" rel="SHORTCUT ICON" href="'. $valor. '"/>';
                    break;
                }
            }
        }
    }
    
    
    
    
    //****************************************************************************/
    //*** Metas do layout ********************************************************/
    //****************************************************************************/
    
    public static function base($href){
        self::$Metas[] = array('base' => array('href' => $href));
    }
    
    public static function metas($name, $content){
        $httpEquiv = array('content-type', 'default-style', 'refresh');
        
        if (is_numeric($name))
            self::$Metas[] = array('meta' => array('http-equiv' => $httpEquiv[$name], 'content' => $content));
        else
            self::$Metas[] = array('meta' => array('name' => $name, 'content' => $content));
    }
    
    protected static function getMetas(){
        foreach (self::$Metas as $meta) {
            list($teg, $attributs) = each($meta);
            echo '<'. $teg ;
            foreach ($attributs as $attr => $value) {
                echo ' '. $attr . '="' . $value . '" ';
            }
            echo '/>';
        }
    }
    
    
    
    
    //****************************************************************************/
    //*** Base da costrucao do layou quando ele é visivel ************************/
    //****************************************************************************/

    public static function header(){
        echo 
        '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
        '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">',
            '<head>',
                '<title>lliure WAP</title>',
                self::getMetas(),
                self::getDocsHead(),
            '</head>',
            '<body>';
    }

    public static function footer(){
        echo 
            self::getDocsFooter(), 
            '</body>', 
        '</html>';
    }
    
    
    
    
    //****************************************************************************/
    //*** Base da costrucao do layou quando ele não é visivel ********************/
    //****************************************************************************/

    public static function layoutOff(){
        header ('Content-type: text/html; charset=ISO-8859-1');
        if(self::$showDocs)self::getDocsHead();
        echo self::content();
        if(self::$showDocs)self::getDocsFooter();
    }
    
    
    
    
    //****************************************************************************/
    //*** Configuracão da parte de visualisação do layout ************************/
    //****************************************************************************/
    
    /**
     * configura para não pararecer o layout e Documentos
     */
    public static function setLayoutAndDocsOff(){
        self::$layoutStatus = FALSE;
        self::$showDocs = FALSE;
    }
    
    /**
     * configura para não pararecer o layout
     */
    public static function setLayoutOff(){
        self::$layoutStatus = FALSE;
        self::$showDocs = TRUE;
    }
    
    /**
     * configura para nao aparecer o html todo.
     * usado para requisisoes que nao mostrarao html.
     */
    public static function setHtmlOff(){
        self::$htmlSattus = FALSE;
    }
    
    /**
     * retona se ira apareceer o html ou nao.
     * @return boolean
     */
    public static function getHtmlSattus(){
        return self::$htmlSattus;
    }
    
    /**
     * retorna se o layout aparece ou nao.
     * @return boolean
     */
    public static function getLayoutStatus(){
        return self::$layoutStatus;
    }
    
    
    
    
    //****************************************************************************/
    //*** Configuracão da parte conteudo do layout *******************************/
    //****************************************************************************/
    
    /**
     * Configura o conteudo que o layout mostra.
     * @param string $content quando passado configura aquele conteudo.
     * @return string|null se <var>$content</var> for null então retorna o conteudo configurado
     */
    public static function content($content = NULL){
        
        if($content === NULL)
            return self::$content;
        
        else
            self::$content = $content;
    }
    
}