<?php
namespace Framework\Session;

interface SessionInterface
{

    /**
     * Récupére une information en session
     * @param  string $key
     * @param  [type] $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Ajoute une information en session
     * @param string $key   [description]
     * @param [type] $value [description]
     * @return mixed
     */
    public function set(string $key, $value): void;

    /**
     * supprime une clé en session
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function delete(string $key): void;
}
