<?php
namespace Framework\Validator;

class ValidationError
{
    private $key;
    private $rule;
    private $attributes;

    private $messages = [
             'required' => 'Le champs %s est vide',
                'empty' => 'Le champs ne peut être vide',
                 'slug' => 'le champs %s n\'est pas un slug valide',
            'minLength' => 'Le champs %s doit contenir plus de %d carractéres',
            'maxLength' => 'Le champs %s doit contenir moin de %d carractéres',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d carractéres',
             'datetime' => 'Le champs %s doit etre une date valide (%s)'
    ];

    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);
        return (string)sprintf(...$params);
    }
}
