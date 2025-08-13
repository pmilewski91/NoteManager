<?php

namespace App\Service;

class LayoutService
{
    /**
     * [Renders the layout with the given content.]
     *
     * @param string $content
     * @return void
     */
    public static function renderLayout(?string $layoutName = "default"): string
    {
        $layoutFile = __DIR__.'/../Resources/layouts/'.$layoutName.'.php';
        
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout file not found: $layoutFile");
        }
        
        return $layoutFile;
    }
}