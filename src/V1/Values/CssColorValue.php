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
 * @package   CssParser\Values
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-mv-css-parser
 */

namespace GanbaroDigital\CssParser\V1\Values;

use GanbaroDigital\CssParser\V1\Checks\IsShortColorValue;

class CssColorValue extends CssValue
{
    protected $red;
    protected $green;
    protected $blue;

    public function __construct($value)
    {
        if (strlen($value) === 4) {
            $this->initFromShortValue($value);
        }
        else {
            $this->initFromStandardValue($value);
        }
    }

    protected function initFromShortValue($value)
    {
        $this->red = hexdec(str_repeat($value{1}, 2));
        $this->green = hexdec(str_repeat($value{2}, 2));
        $this->blue = hexdec(str_repeat($value{3}, 2));
    }

    protected function initFromStandardValue($value)
    {
        $this->red = hexdec(substr($value, 1, 2));
        $this->green = hexdec(substr($value, 3, 2));
        $this->blue = hexdec(substr($value, 5, 2));
    }

    public function getSRGB()
    {
        return [
            $this->red,
            $this->green,
            $this->blue,
            null
        ];
    }

    /**
     * returns the value as valid CSS
     *
     * @return string
     */
    public function __toString()
    {
        // can we use shorthand?
        if (IsShortColorValue::check($this)) {
            // yes, we can :)
            $value = ($this->red / 17 * 256) + ($this->green / 17 * 16) + ($this->blue / 17);
            $precision = 3;
        }
        else {
            $value = ($this->red * 65536) + ($this->green * 256) + $this->blue;
            $precision = 6;
        }

        return '#' . sprintf("%0" . $precision . 'x', $value);
    }
}