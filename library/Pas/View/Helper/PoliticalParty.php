
<!-- saved from url=(0139)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/PoliticalParty.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/PoliticalParty.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/**
 * A view helper for  creating an image based on political party
 *
 * Example use:
 *
 * &lt;code&gt;
 * &lt;?php
 * echo $this-&gt;politicalParty()-&gt;setParty('Conservative');
 * ?&gt;
 * &lt;/code&gt;
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_PoliticalParty extends Zend_View_Helper_Abstract
{

    /** The conservatives logo url
     * @access protected
     * @var string
     */
    protected $_conservatives = '/assets/political/logos/conservatives.png';
    /** The labour logo image url
     * @access protected
     * @var string
     */
    protected $_labour = '/assets/political/logos/labour.jpg';
    /** The lib dem logo image url
     * @access protected
     * @var type
     */
    protected $_libdem = '/assets/political/logos/libdem.jpg';

    /** Initiate a cache
     * @access protected
     * @var Zend_Cache
     */
    protected $_cache;

    /** The party to query
     * @access protected
     * @var string
     */
    protected $_party;

    /** Get the party you are querying
     * @access public
     * @return string
     */
    public function getParty() {
        return $this-&gt;_party;
    }

    /** Set the party to query
     * @access public
     * @param string $party
     * @return \Pas_View_Helper_Politicalparty
     */
    public function setParty($party) {
        $this-&gt;_party = $party;
        return $this;
    }

    /** Get the cache
     * @access public
     * @return Zend_Cache
     */
    public function getCache() {
        $this-&gt;_cache = Zend_Registry::get('cache');
        return $this-&gt;_cache;
    }
    /** Build the image
     * @access public
     * @param string $image
     * @param string $party
     */
    public function buildImage($image, $party) {
        $party = str_replace(' ','_',$party);
        $html = '';
        list($w, $h, $type, $attr) = getimagesize('./'.$image);
        $html .= '&lt;img src="';
        $html .= $image;
        $html .= '" alt="Party political logo" width="';
        $html .= $w;
        $html .= '" height="';
        $html .= $h;
        $html .= '" /&gt;';
        return $html;
    }
    /** Determine which image to build based on political party
     * @access public
     * @param string
     */
    public function __toString() {
        $party = $this-&gt;getParty();
        if (!is_null($party) || $party != "") {
        switch ($party) {
            case($party == 'Labour'):
                $partyImage = $this-&gt;buildImage($this-&gt;_labour,$party);
                break;
            case($party == 'Conservative'):
                $partyImage = $this-&gt;buildImage($this-&gt;_conservatives,$party);
                break;
            case($party == 'Liberal Democrat');
                $partyImage = $this-&gt;buildImage($this-&gt;_libdem,$party);
                break;
            default:
                $partyImage = NULL;
        }

        return $partyImage;
        }
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_Politicalparty
     */
    public function politicalParty() {
        return $this;
    }

}</pre></body></html>