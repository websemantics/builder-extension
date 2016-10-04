<?php namespace Websemantics\BuilderExtension\Support;

use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\Array_;
use PhpParser\Builder\Property;

/**
 * Class PhpParser.
 *
 * Helper methods for the php-parser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
class Helper
{
    /**
     * Parse a return array to located and modify one of its entries.
     *
     * @param Stmt_Return $node          contains a php file return array (i.e. addon.php)
     * @param string      $name,         name of the attribute
     * @param Expr_Array  $templateNode, key, value pairs from Template file
     * @param bool        $front,        set location to the front of the array
     *
     * @return Stmt_Return
     */
    public function parseReturnArray(&$node, $name, $templateNode, $front)
    {
        $matchKey = null;
        $array = $this->node2Array($templateNode);

        foreach ($node->expr->items as $key => $property) {
            if ($property->getType() === 'Expr_ArrayItem') {
                if ($name === $property->key->value &&
                 $property->value->getType() === 'Expr_Array') {
                    $matchKey = $key;
                    $array = ($front) ?
                    array_merge(
                        $array,
                        $this->node2Array($property->value)
                    ) :
                    array_merge(
                        $this->node2Array($property->value),
                        $array
                    );
                    break;
                }
            }
        }

        if (!is_null($matchKey)) {
            if(!isset($node->expr->items[$matchKey])){
              $node->expr->items[$matchKey] = $this->makeArrayItem($name, $array);
            }
        } else {
            $node->expr->items[] = $this->makeArrayItem($name, $array);
        }

        return $node;
    }

    /**
     * Parse a class to located and modify one of its properties.
     *
     * @param Stmt_Namespace $node          contains a php namespace container
     * @param string         $name,         name of the attribute
     * @param Expr_Array     $templateNode, key, value pairs from Template file
     * @param bool           $front,        set location to the front of the array
     *
     * @return Stmt_Namespace
     */
    public function parseClassProperty(&$node, $name, $templateNode, $front = fales)
    {
        $array = $this->node2Array($templateNode);

        foreach ($node->stmts as $block) {
            if ($block->getType() === 'Stmt_Class') {
                $matchKey = null;

                foreach ($block->stmts as $key => $property) {
                    if ($property->getType() === 'Stmt_Property' &&
                       $name === $property->props[0]->name) {
                        $matchKey = $key;

                        $array = ($front) ?
                        array_merge(
                            $array,
                            $this->node2Array($property->props[0]->default)
                        ) :
                        array_merge(
                            $this->node2Array($property->props[0]->default),
                            $array
                        );

                        break;
                    }
                }
                // Append merged array,
                $prop = new Property($name);
                $prop->setDefault($array);
                $prop->makeProtected();

                if (!is_null($matchKey)) {
                    $block->stmts[$matchKey] = $prop->getNode();
                } else {
                    $block->stmts[] = $prop->getNode();
                }
            }
        }

        return $node;
    }

    /**
     * Parse ExprArray and return an associative array.
     *
     * @param ExprArray $node
     *
     * @return array
     */
    public function node2Array($node)
    {
        $items = [];

        if ($node->getType() === 'Expr_Array') {
            foreach ($node->items as $item) {
                if ($item->value->getType() == 'Scalar_String') {
                    if ($item->key) {
                        $items[$item->key->value] = $item->value->value;
                    } else {
                        $items[] = $item->value->value;
                    }
                } elseif ($item->value->getType() == 'Expr_Array') {
                    if ($item->key) {
                        $items[$item->key->value] = $this->node2Array($item->value);
                    } else {
                        $items[] = $this->node2Array($item->value);
                    }
                }
            }
        }

        return $items;
    }
    /**
     * Parse an associative array and return ExprArray.
     *
     * @param array $items
     *
     * @return ExprArray
     */
    public function array2Node($items)
    {
        foreach ($items as $key => $value) {
            $value = is_string($value) ? new String_($value) : $this->array2Node($value);
            $items[$key] = new ArrayItem($value, new String_($key));
        }
        return new Array_($items);
    }

    /**
     * Parse an associative array and return ExprArray.
     *
     * @param array $array
     *
     * @return ExprArray
     */
    public function makeArrayItem($name, $items)
    {
        return new ArrayItem(
            $this->array2Node($items),
            new String_($name)
        );
    }
}
