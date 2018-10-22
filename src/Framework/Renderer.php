<?php
namespace Framework;

class Renderer
{

    const DEFAULT_NAMESPACE = '__MAIN';

    /**
     *  tableau de chemin des vues
     *  @var array
     */
    private $paths = [];

    /**
     *  Variables globalement accessible pour toutes les vues
     *  @var array
     */
    private $globals = [];

    /**
     * Permet de rajouter un chemin pour charger les vues
     * @param string $namespace
     * @param null | string $path
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
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
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }
        ob_start();
        $renderer = $this;
        extract($this->globals);
        extract($params);
        require($path);
        return ob_get_clean();
    }

    /**
     *  permet de rajouter des variable globales a ttes les vues
     *
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    private function hasNamespace(string $view): bool
    {
        return $view[0] === '@';
    }

    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }

    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }
}
