<?php

namespace Arrilot\BitrixBlade;

use Illuminate\View\FileViewFinder;

class ViewFinder extends FileViewFinder
{
    /**
     * Setter for paths.
     *
     * @param array $paths
     *
     * @return void
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
    }
}
