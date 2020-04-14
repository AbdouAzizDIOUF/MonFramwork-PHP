<?php
namespace App\Admin;

use Framework\Module;
use Framework\Renderer\RendererInterface;

class AdminModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config.php';

    /**
     * Chemin de la vue de l'administration
     * AdminModule constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $renderer->addPath('admin', __DIR__ . '/views');
    }
}
