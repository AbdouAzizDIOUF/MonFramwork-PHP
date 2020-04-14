<?php
namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * Permet de rajouter un chemin pour charger les vues
     * @param string $namespace
     * @param null | string $path
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     *  Permet de rendre une vue
     *  le chemin peut etre precise avec des des namespaces rajoutés via addPath
     *  $this->render('@blog/view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string;

    /**
     *  permet de rajouter des variable globales a ttes les vues
     * @param string $key
     * @param $value
     */
    public function addGlobal(string $key, $value): void;
}
