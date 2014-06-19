
<!-- saved from url=(0133)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/FullName.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/FullName.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/** A view helper for getting fullname of user
 *
 * An example of use:
 *
 * &lt;code&gt;
 * &lt;?php
 * echo $this-&gt;fullName();
 * ?&gt;
 * &lt;/code&gt;
 *
 * @author Daniel Pett &lt;dpett@britishmuseum.org&gt;
 * @version 1
 * @since 1
 * @copyright Daniel Pett &lt;dpett@britishmuseum.org&gt;
 * @package Pas
 * @category Pas_View_Helper
 * @uses Zend_Auth Zend Auth
 * @uses Zend_View_Helper_Escape
 *
 *
 */
class Pas_View_Helper_FullName extends Zend_View_Helper_Abstract
{
    /** The auth object
     * @access protected
     * @var object
     */
    protected $_auth;

    /** Get the auth object
     * @access public
     * @return object
     */
    public function getAuth() {
        $this-&gt;_auth = Zend_Auth::getInstance();

        return $this-&gt;_auth;
    }

    /** The fullname to use and display
     * @access protected
     * @var string
     */
    protected $_fullname;

    /** Get the fullname from the identity
     * @access public
     * @return string
     */
    public function getFullname() {
        $user = $this-&gt;getAuth()-&gt;getIdentity();
        if ($user-&gt;hasIdentity()) {
            $this-&gt;_fullname = $this-&gt;view-&gt;escape(ucfirst($user-&gt;fullname));
        }

        return $this-&gt;_fullname;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this-&gt;getFullname();
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FullName
     */
    public function fullName() {
        return $this;
    }
}
</pre></body></html>