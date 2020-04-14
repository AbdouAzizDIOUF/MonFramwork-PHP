<?php
namespace Framework\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Extension\DebugExtension;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigRendererFactory
{

    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $viewpath = $container->get('views.path');
        $loader = new Twig_Loader_Filesystem($viewpath);
        $twig = new Twig_Environment($loader, ['debug' => true]);
        $twig->addExtension(new DebugExtension());
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        return new TwigRenderer($twig);
    }
}
