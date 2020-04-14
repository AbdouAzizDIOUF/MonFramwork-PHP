<?php
namespace Framework\Session;

class PHPSession implements SessionInterface {

	/**
	 * assure que la session est demarrée
	 * @return mixed
	 */
	private function assurStarted() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	/**
	 * Récupére une information en session
	 * @param  string $key
	 * @param  [type] $default
	 * @return mixed
	 */
	public function get(string $key, $default = null) {
		$this->assurStarted();
		if (array_key_exists($key, $_SESSION)) {
			return $_SESSION[$key];
		}
		return $default;
	}

	/**
	 * Ajoute une information en session
	 * @param string $key
	 * @param $value
	 * @return mixed
	 */
	public function set(string $key, $value): void{
		$this->assurStarted();
		$_SESSION[$key] = $value;
	}

	/**
	 * supprime une clé en session
	 * @param  string $key
	 * @return void
	 */
	public function delete(string $key): void{
		$this->assurStarted();
		unset($_SESSION[$key]);
	}
}
