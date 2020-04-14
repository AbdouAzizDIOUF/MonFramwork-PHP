<?php
namespace Framework;

use Framework\Validator\ValidationError;

class Validator
{
    private $params;
    private $errors = [];

    /**
     * prend en paramettre la liste des paramettre issue du contenu de la page de soumission
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

/**
 * verifie si les champs sont presents sur le tableau
 * @param array
 * @return self
 */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addErroor($key, 'required');
            }
        }
        return $this;
    }
/**
 * verifie ou assure que la taille des elements sont biens respecté chaque element doit avoir un taille bien définie
 * @param  string   $key
 * @param  int      $min
 * @param  int|null $max
 * @return self
 */
    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);
        if (!is_null($min) && !is_null($max) && ($length < $min || $length > $max)) {
            $this->addErroor($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (!is_null($min) && $length < $min) {
            $this->addErroor($key, 'minLength', [$min]);
            return $this;
        }
        if (!is_null($max) && $length > $max) {
            $this->addErroor($key, 'maxLength', [$max]);
        }
        return $this;
    }
    /**
     * verifie que l'element est un slug valide
     * @param  string $key
     * @return self
     */
    public function slug(string $key): self
    {
        $value = $this->getValue($key);
        $patern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
        if (!is_null($value) && !preg_match($patern, $value)) {
            $this->addErroor($key, 'slug');
        }
        return $this;
    }

    /**
     * verifie que le champs n'est pas vide
     * @param   $keys
     * @return self
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addErroor($key, 'empty');
            }
        }
    }

    /**
     * le format de la date de mise a jour estb respecté
     * @param  string $key
     * @param  string $format
     * @return
     */
    public function dateTime(string $key, string $format = "Y-m-d H:i:s"): self
    {
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addErroor($key, 'datetime', [$format]);
        }
        return $this;
    }

    /**
     * Si l'element est valide
     * @return boolean
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }
    /**
     * recupere les erreurs
     * @return
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function addErroor(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    /**
     * recupre la valeur de l'element sinon elle renvoie null
     * @param  string $key
     * @return
     */
    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}
