<?php
namespace Framework\Session;

class FlashService
{
    private $session;
    private $sessionKey = 'flash';
    private $message;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * Messsage de success
     * @param  string $message
     * @return void
     */
    public function success(string $message): void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * Message d'erreur
     * @param  string $message
     * @return void
     */
    public function error(string $message): void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['errors'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * recupere le message de la session
     * @param string $type
     * @return string|null
     */
    public function get(string $type): ?string
    {
        if ($this->message === null) {
            $this->message = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }
        if (array_key_exists($type, $this->message)) {
            return $this->message[$type];
        }

        return null;
    }
}
