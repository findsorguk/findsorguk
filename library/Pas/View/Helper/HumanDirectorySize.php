<?php
/** 
 * A view helper for returning the file size as an integer for a directory.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $this->humanDirectorySize()
 * ->setPath($path)
 * ->dirSize();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package View_Helper
 * @example /app/modules/admin/views/scripts/system/serverconfig.phtml
 * 
 */
class Pas_View_Helper_HumanDirectorySize extends Zend_View_Helper_Abstract {
   
    /** The path
     * @access protected
     * @var string
     */
    protected $_path;
    
    /** Set the path
     * @access public
     * @param string $path
     * @return \Pas_View_Helper_HumanDirectorySize
     */
    public function setPath($path) {
        $this->_path = $path;
        return $this;
    }
    
    /** Get the path
     * @access public
     * @return string
     */
    public function getPath() {
        return $this->_path;
    }
    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_HumanDirectorySize
     */
    public function humanDirectorySize() {
        return $this;
    }
    
    /** Get the directory size
     * @param directory $directory
     * @return integer
     */
    public function dirSize() {
        $size = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->getPath())) as $file){
            $size+=$file->getSize();
        }
        return $size;
    }
}