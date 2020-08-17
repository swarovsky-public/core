<?php

namespace Swarovsky\Core\Services;
use Swarovsky\Core\Builders\FormBuilder;

class FormService
{
    private array $_allowedRenders = [
        'anchor',
        'button',
        'checkbox',
        'close',
        'email',
        'file',
        'hidden',
        'number',
        'open',
        'password',
        'radio',
        'range',
        'reset',
        'select',
        'submit',
        'text',
        'textarea',
    ];

    private FormBuilder $_builder;
    private string $_render;


    public function __construct()
    {
        $this->_builder = new FormBuilder;
    }


    public function __toString(): string
    {
        $output = '';

        if (in_array($this->_render, $this->_allowedRenders, true)) {
            $output = $this->_builder->{$this->_render}();
        }

        $this->_render = '';

        return $output;
    }


    public function anchor(string $value, $url = null): FormService
    {
        if ($url) {
            $this->url($url);
        }

        return $this->button($value)->type('anchor');
    }


    public function attrs(array $attrs = []): FormService
    {
        return $this->_set('attrs', $attrs);
    }


    public function button(string $value = null): FormService
    {
        return $this->type('button')->value($value);
    }


    public function checkbox(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('checkbox', $name, $label, $value, $default);
    }

    public function checked(bool $checked = true): FormService
    {
        $type = $this->_builder->get('type');
        $meta = $this->_builder->get('meta');

        if ($type === 'radio' && $checked) {
            $checked = $meta['value'];
        }

        return $this->value($checked);
    }

    public function close(): FormService
    {
        return $this->render('close');
    }

    public function color(string $color = null): FormService
    {
        return $this->_set('color', $color);
    }

    public function disabled($status = true): FormService
    {
        return $this->_set('disabled', $status);
    }


    public function file(string $name = null, string $label = null): FormService
    {
        return $this->name($name)->label($label)->type('file');
    }


    public function fill($data): FormService
    {
        if (method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            $data = [];
        }

        return $this->_set('Fdata', $data);
    }


    public function full(bool $status = true): FormService
    {
        return $this->_set('full', $status);
    }

    public function help(string $text): FormService
    {
        return $this->_set('help', $text);
    }

    public function hidden(string $name = null, string $default = null): FormService
    {
        return $this->name($name)->value($default)->type('hidden');
    }

    public function icon(string $icon = '', bool $flip = false, bool $clickable = false, array $attrs = []): FormService
    {
        return $this->_set('icon', ['icon' => $icon, 'flip' => $flip, 'clickable' => $clickable, 'attrs' => $attrs]);
    }

    public function id($id): FormService
    {
        return $this->_set('id', $id);
    }

    public function inline(bool $inline = true): FormService
    {
        return $this->_set('inline', $inline);
    }

    public function label($label): FormService
    {
        return $this->_set('label', $label);
    }

    public function locale(string $path): FormService
    {
        return $this->_set('Flocale', $path);
    }

    public function method(string $method): FormService
    {
        return $this->_set('Fmethod', $method);
    }

    public function multipart(bool $multipart = true): FormService
    {
        return $this->_set('Fmultipart', $multipart);
    }

    public function multiple(bool $multiple = true): FormService
    {
        return $this->_set('multiple', $multiple);
    }

    public function name($name): FormService
    {
        return $this->_set('name', $name);
    }

    public function open(): FormService
    {
        return $this->render('open');
    }

    public function options(array $options = []): FormService
    {
        $items = is_iterable($options) ? $options : [0 => 'Must be iterable'];

        return $this->_set('options', $items);
    }

    public function outline(bool $outline = true): FormService
    {
        return $this->_set('outline', $outline);
    }

    public function placeholder($placeholder): FormService
    {
        return $this->_set('placeholder', $placeholder);
    }

    public function prefix(string $prefix = ''): FormService
    {
        return $this->_set('Fprefix', $prefix);
    }

    public function radio(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('radio', $name, $label, $value, $default);
    }

    public function range(string $name = null, string $label = null, string $default = null, string $min = '0', string $max = '10', string $step = '0.1'): FormService
    {
        return $this->type('range')->name($name)->label($label)->value($default)->attrs(['min' => $min, 'max' => $max, 'step' => $step]);
    }

    public function readonly($status = true): FormService
    {
        return $this->_set('readonly', $status);
    }

    public function render(string $render): FormService
    {
        $this->_render = $render;

        return $this;
    }


    public function reset(string $value): FormService
    {
        return $this->type('reset')->button($value);
    }

    public function route(string $route, array $params = []): FormService
    {
        return $this->_set('url', route($route, $params));
    }

    public function select(string $name = null, string $label = null, $options = [], $default = null): FormService
    {
        return $this->name($name)->label($label)->options($options)->value($default)->type('select');
    }

    public function size(string $size = null): FormService
    {
        return $this->_set('size', $size);
    }

    public function submit(string $value): FormService
    {
        return $this->button($value)->type('submit');
    }

    public function text(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('text')->name($name)->label($label)->value($default);
    }

    public function textarea(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('textarea')->name($name)->value($default)->label($label);
    }


    public function type($type): FormService
    {
        return $this->_set('type', $type)->render($type);
    }

    public function url(string $url): FormService
    {
        return $this->_set('url', url($url));
    }

    public function value($value = null): FormService
    {
        if ($value !== null) {
            return $this->_set('value', $value);
        }
        return $this;
    }


    private function _set($attr, $value): FormService
    {
        $this->_builder->set($attr, $value);

        return $this;
    }

    private function _checkboxRadio($type, $name, $label, $value, $default): FormService
    {
        $inputValue = $value ?? $name;

        if ($default) {
            $default = $inputValue;
        }

        return $this->_set('meta', ['value' => $inputValue])->type($type)->name($name)->label($label)->value($default);
    }
}
