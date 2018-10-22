<?php
namespace Framework\Renderer;

class TwigRenderer implements RendererInterface
{
    private $loader;
    private $twig;

    public function __construct(string $path)
    {
        $this->loader = new \Twig_Loader_Filesystem($path);
        $this->twig = new \Twig_Environment($this->loader, []);
    }
    /**
     * Permet de rajouter un chemin pour charger les vues
     * @param string $namespace
     * @param null | string $path
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }
    /**
     *  Permet de rendre une vue
     *  le chemin peut etre precise avec des des namespaces rajoutés via addPath
     *  $this->render('@blog/view');
     * @param string $view
     * @param array $params
     */
    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view.'.twig', $params);
    }
    /**
     *  permet de rajouter des variable globales a ttes les vues
     *
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
