<?php

namespace Arrilot\BitrixBlade;

use Illuminate\View\Compilers\BladeCompiler as BaseCompiler;

class BladeCompiler extends BaseCompiler
{
    /**
     * Compile the given Blade template contents.
     *
     * @param string $value
     *
     * @return string
     */
    public function compileString($value)
    {
        $result = '<?php if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true) die();?>';
        $result .= '<?php if(!empty($arResult)) extract($arResult, EXTR_SKIP);?>';

        return $result . parent::compileString($value);
    }
}
