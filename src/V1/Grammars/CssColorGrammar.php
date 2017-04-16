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
 * @package   CssParser\Grammars
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-mv-color-calc
 */

namespace GanbaroDigital\CssParser\V1\Grammars;

use GanbaroDigital\CssParser\V1\Values;

use GanbaroDigital\TextParser\V1\Evaluators;
use GanbaroDigital\TextParser\V1\Grammars;
use GanbaroDigital\TextParser\V1\Terminals;

/**
 * what is the grammar for a CSS color?
 */
class CssColorGrammar
{
    private static $language = null;

    public static function init()
    {
        self::$language = [
            // grammar
            "colorValue" => new Grammars\AnyOf([
                new Grammars\Reference("T_STD_COLOR_VALUE"),
                new Grammars\Reference("T_SHORT_COLOR_VALUE"),
                new Grammars\Reference("color_function"),
                new Grammars\Reference("named_color")
            ]),
            "color_function" => new Grammars\AnyOf([
                new Grammars\Reference('rgb_function'),
                new Grammars\Reference('rgba_function'),
            ]),
            "rgb_function" => new Grammars\GrammarList([
                new Grammars\PrefixToken("rgb("),
                new Grammars\AnyOf([
                    new Grammars\Reference('rgb_int_params'),
                    new Grammars\Reference('rgb_percent_params'),
                ]),
                new Grammars\Optional(
                    new Grammars\GrammarList([
                        new Terminals\Lazy\T_COMMA,
                        new Grammars\Reference("color_percentage_or_fraction"),
                    ])
                ),
                new Grammars\PrefixToken(")"),
            ]),
            "rgb_int_params" => new Grammars\GrammarList([
                new Grammars\Reference("T_8BIT_VALUE"),
                new Terminals\Lazy\T_COMMA,
                new Grammars\Reference("T_8BIT_VALUE"),
                new Terminals\Lazy\T_COMMA,
                new Grammars\Reference("T_8BIT_VALUE"),
            ]),
            "rgb_percent_params" => new Grammars\GrammarList([
                new Grammars\Reference("T_COLOR_PERCENTAGE"),
                new Terminals\Lazy\T_COMMA,
                new Grammars\Reference("T_COLOR_PERCENTAGE"),
                new Terminals\Lazy\T_COMMA,
                new Grammars\Reference("T_COLOR_PERCENTAGE"),
            ]),
            "rgba_function" => new Grammars\GrammarList([
                new Grammars\PrefixToken("rgba("),
                new Grammars\AnyOf([
                    new Grammars\Reference('rgb_int_params'),
                    new Grammars\Reference('rgb_percent_params'),
                ]),
                new Terminals\Lazy\T_COMMA,
                new Grammars\Reference("color_percentage_or_fraction"),
                new Grammars\PrefixToken(")"),
            ]),
            "color_percentage_or_fraction" => new Grammars\AnyOf([
                new Grammars\Reference("T_COLOR_PERCENTAGE"),
                new Grammars\Reference("T_COLOR_FRACTIONAl_PERCENTAGE"),
            ]),
            "named_color" => new Grammars\Reference("T_COLOR_NAME"),

            // terminals
            'T_8BIT_VALUE' => new Terminals\Meta\T_8BIT_VALUE,
            'T_COLOR_PERCENTAGE' => new Terminals\Meta\T_INT_PERCENTAGE,
            'T_COLOR_FRACTIONAl_PERCENTAGE' => new Terminals\Meta\T_FRACTIONAL_PERCENTAGE,
            'T_SHORT_COLOR_VALUE' => new Grammars\GrammarList([
                new Terminals\Lazy\T_HASH,
                new Terminals\Meta\T_FIXED_WIDTH_HEX_NUMBER(3),
            ], new Evaluators\BuildObjectFromList(Values\CssColorValue::class, [[0, 1]])),
            'T_STD_COLOR_VALUE' => new Grammars\GrammarList([
                new Terminals\Lazy\T_HASH,
                new Terminals\Meta\T_FIXED_WIDTH_HEX_NUMBER(6),
            ], new Evaluators\BuildObjectFromList(Values\CssColorValue::class, [[0, 1]])),
            'T_COLOR_NAME' => new Terminals\Meta\T_WORD(
                new Evaluators\CastToObject(Values\CssNamedColor::class)
            ),
        ];
    }

    /**
     * returns the grammar that our parser needs
     *
     * @return array
     */
    public static function getLanguage()
    {
        return self::$language;
    }
}

CssColorGrammar::init();
