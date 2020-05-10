<?php
namespace Framework\Session;

interface SessionInterface {

    /**
     * Ajoute une information en session
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value): void;

	/**
	 * Récupére une information en session
	 * @param  string $key
	 * @param  $default
	 * @return mixed
	 */
	public function get(string $key, $default = null);

	/**
	 * supprime une clé en session
	 * @param  string $key
	 * @return mixed
	 */
	public function delete(string $key): void;
}
