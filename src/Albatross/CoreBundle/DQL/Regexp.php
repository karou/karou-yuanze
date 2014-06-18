<?php

namespace Albatross\CoreBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * RegexpFunction ::= "Regexp" "(" StringPrimary "," StringPrimary ")"
 */
class Regexp extends FunctionNode
{
    // $MyStr = le champ qui doit etre compare, $MyRegexp =l'expression reguliere
    public $MyStr = null;
    public $MyRegexp = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->MyStr = $parser->StringPrimary(); // (4)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->MyRegexp = $parser->StringPrimary(); // (6)
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return $this->MyStr->dispatch($sqlWalker) . ' REGEXP ' . $this->MyRegexp->dispatch($sqlWalker) ;
    }
}