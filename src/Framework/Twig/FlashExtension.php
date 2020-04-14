<?php
namespace Framework\Twig;

use Framework\Session\FlashService;
use Twig_Extension;
use Twig_SimpleFunction;

class FlashExtension extends Twig_Extension
{
    private $flashService;

    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction('flash', [$this, 'getFlash'])
        ];
    }
    public function getFlash($type): ?string
    {
        return $this->flashService->get($type);
    }
}
