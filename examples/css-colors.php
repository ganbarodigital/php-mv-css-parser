<?php

use GanbaroDigital\CssParser\V1\Grammars\CssColorGrammar;
use GanbaroDigital\TextParser\V1\Lexer\ApplyGrammar;
use GanbaroDigital\TextParser\V1\Lexer\PrintGrammar;
use GanbaroDigital\TextParser\V1\Lexer\WhitespaceAdjuster;

require_once(__DIR__ . '/../vendor/autoload.php');

$language = CssColorGrammar::getLanguage();

PrintGrammar::using($language);
echo PHP_EOL . PHP_EOL;

//$text = "#fff";
//$text = "#111";
//$text = "#aabbcc";
$text = "mediumslateblue";
//$text = "#DA70D6";
//$text = "rgb(0, 100, 200)";
//$text = "rgb(100%, 0%, 20%)";

echo "*** lexing ***" . PHP_EOL;
echo $text . PHP_EOL;
$matches = ApplyGrammar::to($language, 'colorValue', $text, 'color', new WhitespaceAdjuster);

//echo PHP_EOL . "*** after lexing ***" . PHP_EOL;
//var_dump($matches);

//echo PHP_EOL;
if ($matches['matched']) {
    echo PHP_EOL . "*** matched ***" . PHP_EOL;
    //var_dump($matches['value']);
    echo PHP_EOL . "*** evaluating ***" . PHP_EOL;
    $finalResult = $matches['value']->evaluate();
    var_dump($finalResult);
    echo PHP_EOL . "*** rendering ***" . PHP_EOL;
    echo (string)$finalResult . PHP_EOL;
}
else {
    echo PHP_EOL . "*** NO MATCH ***" . PHP_EOL;
    echo "Expected " . $matches['expected']->getPseudoBNF() . " at line "
         . $matches['position']->getLineNumber() . ', column '
         . $matches['position']->getLineOffset() . PHP_EOL;
}
