<?php
/**
 * @package OaiPmhRepository
 * @subpackage Libraries
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Parent class for all XML-generating classes.
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 */
class Pas_OaiPmhRepository_XmlGeneratorAbstract
{
    const XML_SCHEMA_NAMESPACE_URI = 'http://www.w3.org/2001/XMLSchema-instance';

    /**
     * The XML document being generated.
     * @var DomDocument
     */
    protected $_document;

    /**
     * Creates a new XML element with the specified children
     *
     * Creates a parent element with the given name, with children with names
     * and values as given.  Adds the resulting element as a child of the given
     * element
     *
     * @param DomElement $parent Existing parent of all the new nodes.
     * @param string $name Name of the new parent element.
     * @param array $children Child names and values, as name => value.
     * @return DomElement The new tree of elements.
     */
    protected function createElementWithChildren($parent, $name, $children)
    {
        $document = $this->document;
        $newElement = $document->createElement($name);
        foreach($children as $tag => $value)
        {
            $newElement->appendChild($document->createElement($tag, $value));
        }
        $parent->appendChild($newElement);
        return $newElement;
    }

    /**
     * Creates a parent element with the given name, with text as given.
     *
     * Adds the resulting element as a child of the given parent node.
     *
     * @param DomElement $parent Existing parent of all the new nodes.
     * @param string $name Name of the new parent element.
     * @param string $text Text of the new element.
     * @return DomElement The new element.
     */
    protected function appendNewElement($parent, $name, $text = null)
    {
        $document = $this->document;
        $newElement = $document->createElement($name);
        // Use a TextNode, causes escaping of input text
        if($text) {
            $text = $document->createTextNode($text);
            $newElement->appendChild($text);
        }
        $parent->appendChild($newElement);
        return $newElement;
     }
}