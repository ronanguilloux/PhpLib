<?php
/*
 * Xml (& SimpleXmlElement) lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Xml.php
 */

namespace PhpLib;

/**
 * Xml lib class
 * @author Ronan GUILLOUX
 *
 */
class Xml
{

    /**
     * source : http://php.net/manual/fr/book.simplexml.php
     * Converts a simpleXML element into an array. Preserves attributes.<br/>
     * You can choose to get your elements either flattened, or stored in a custom
     * index that you define.<br/>
     * For example, for a given element
     * <code>
     * <field name="someName" type="someType"/>
     * </code>
     * <br>
     * if you choose to flatten attributes, you would get:
     * <code>
     * $array['field']['name'] = 'someName';
     * $array['field']['type'] = 'someType';
     * </code>
     * If you choose not to flatten, you get:
     * <code>
     * $array['field']['@attributes']['name'] = 'someName';
     * </code>
     * <br>__________________________________________________________<br>
     * Repeating fields are stored in indexed arrays. so for a markup such as:
     * <code>
     * <parent>
     *     <child>a</child>
     *     <child>b</child>
     *     <child>c</child>
     * ...
     * </code>
     * you array would be:
     * <code>
     * $array['parent']['child'][0] = 'a';
     * $array['parent']['child'][1] = 'b';
     * ...And so on.
     * </code>
     * @param simpleXMLElement $xml           the XML to convert
     * @param boolean|string   $attributesKey if you pass TRUE, all values will be
     *                                            stored under an '@attributes' index.
     *                                            Note that you can also pass a string
     *                                            to change the default index.<br/>
     *                                            defaults to null.
     * @param boolean|string $childrenKey if you pass TRUE, all values will be
     *                                            stored under an '@children' index.
     *                                            Note that you can also pass a string
     *                                            to change the default index.<br/>
     *                                            defaults to null.
     * @param boolean|string $valueKey if you pass TRUE, all values will be
     *                                            stored under an '@values' index. Note
     *                                            that you can also pass a string to
     *                                            change the default index.<br/>
     *                                            defaults to null.
     * @return array the resulting array.
     */
    public static function simpleXMLToArray(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null)
    {
        if ($childrenKey && !is_string($childrenKey)) {$childrenKey = '@children';}
            if ($attributesKey && !is_string($attributesKey)) {$attributesKey = '@attributes';}
                if ($valueKey && !is_string($valueKey)) {$valueKey = '@values';}

                    $return = array();
        $name = $xml->getName();
        $_value = trim((string) $xml);
        if (!strlen($_value)) {$_value = null;};

        if ($_value!==null) {
            if ($valueKey) {$return[$valueKey] = $_value;} else {$return = $_value;}
        }

        $children = array();
        $first = true;
        foreach ($xml->children() as $elementName => $child) {
            $value = self::simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey);
            if (isset($children[$elementName])) {
                if (is_array($children[$elementName])) {
                    if ($first) {
                        $temp = $children[$elementName];
                        unset($children[$elementName]);
                        $children[$elementName][] = $temp;
                        $first=false;
                    }
                    $children[$elementName][] = $value;
                } else {
                    $children[$elementName] = array($children[$elementName],$value);
                }
            } else {
                $children[$elementName] = $value;
            }
        }
        if ($children) {
            if ($childrenKey) {$return[$childrenKey] = $children;} else {$return = array_merge($return,$children);}
        }

        $attributes = array();
        foreach ($xml->attributes() as $name=>$value) {
            $attributes[$name] = trim($value);
        }
        if ($attributes) {
            if ($attributesKey) {$return[$attributesKey] = $attributes;} else {$return = array_merge($return, $attributes);}
        }

        return $return;
    }
}
