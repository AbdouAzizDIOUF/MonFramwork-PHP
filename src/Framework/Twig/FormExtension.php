<?php
namespace Framework\Twig;

use DateTime;
use Twig_Extension;
use Twig_SimpleFunction;

class FormExtension extends Twig_Extension
{

    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction('field', [$this, 'field'], [
                    'is_safe' => ['html'],
                    'needs_context' => true
                ])
        ];
    }

    /**
        * Génére le code HTML d'un champs
        * @param  array       $context Contexte de la vue Twig
        * @param  string      $key     Clef du champs
        * @param  mixed      $value   Valeur du champs
        * @param  string|null $label   label a utiliser
        * @param  array       $options
        * @return string
    */
    public function field(array $context, string $key, $value, ?string $label = null, array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? '')),
             'name' => $key,
               'id' => $key
        ];
        if ($error) {
            $class .= ' has-danger';
            $attributes['class'] .= ' form-control-danger';
        }
        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        return '
            <div class="' . $class ."\">
                <label for=\"name\">{$label}</label>
                {$input}
                {$error}
            </div>";
    }

    private function convertValue($value): string
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }

    /**
     * Génere l'HTML en fonction des erreurs du contexte
     * @param $context
     * @param $key
     * @return string [type]          [description]
     */
    private function getErrorHtml($context, $key): string
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class=\"form-text text-muted\">{$error}</small>";
        }
        return '';
    }
    private function input(?string $value, array $attributes): string
    {
        return  '<input type="text" ' . $this->getHtmlFromArray($attributes) ." value=\"{$value}\">";
    }

    private function textarea(?string $value, array $attributes): string
    {
        return  '<textarea ' . $this->getHtmlFromArray($attributes) ." rows=\"8\" cols=\"70\">{$value}</textarea>";
    }

    private function getHtmlFromArray(array $attributes)
    {
        return implode(' ', array_map(static function ($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
    }
}
