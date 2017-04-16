<?php

/**
 * Copyright (c) 2017-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   CssParser\Parsers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-mv-css-parser
 */

namespace GanbaroDigital\CssParser\V1\Parsers;

use GanbaroDigital\CssParser\V1\Grammars\CssColorGrammar;
use GanbaroDigital\TextParser\V1\Lexer\ApplyGrammar;
use GanbaroDigital\TextParser\V1\Lexer\PrintGrammar;
use GanbaroDigital\TextParser\V1\Lexer\WhitespaceAdjuster;

/**
 * parse a CSS color value
 */
class BuildCssColorValue
{
    /**
     * parse a CSS color value
     *
     * @param  string $color
     *         a CSS color's definition
     * @return mixed
     *         the parsed color value
     *
     * @throws CouldNotParseColor
     *         if $color contains something we do not understand
     */
    public static function from($color)
    {
        $language = CssColorGrammar::getLanguage();
        $matches = ApplyGrammar::to($language, 'colorValue', $color, 'color', new WhitespaceAdjuster);

        if ($matches['matched']) {
            return $matches['matched']->evaluate();
        }

        // we do not understand what we are looking at
        throw CouldNotParseColor::newFromInputParams($color, '$color', [
            'expected' => $matches['expected']->getPseudoBNF(),
            'line' => $matches['position']->getLineNumber(),
            'column' => $matches['position']->getLineOffset()
        ]);
    }
}