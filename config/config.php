<?php
use function DI\factory;
use function DI\object;
use function DI\get;

    return
    [
        'views.path' => dirname(__DIR__).'/views',
        'twig.extensions' => [
            get(\Framework\Router\RouterTwigExtension::class)
        ],
        \Framework\Router::class => object(),
         \Framework\Renderer\RendererInterface::class => factory(\Framework\Renderer\TwigRendererFactory::class)
    ];
