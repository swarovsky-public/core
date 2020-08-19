<?php

namespace Swarovsky\Core\Builders;

use Illuminate\Support\Str;

class FormBuilder
{
    private array $_Fdata;
    private string $_Flocale;
    private string $_Fmethod;
    private bool $_Fmultipart;
    private string $_Fprefix;
    private array $_attrs;
    private string $_color;
    private bool $_disabled;
    private bool $_full;
    private string $_help;

    /**
     * Icon to form input or button
     *
     * @var string
     * @var boolean
     */
    private $_icon;
    private string $_id;
    private bool $_inline;
    private string $_label;
    private array $_meta;
    private bool $_multiple;
    private string $_name;
    private array $_options;
    private bool $_outline;
    private string $_placeholder;
    private bool $_readonly;
    private string $_size;
    private string $_type;
    private string $_url;
    private $_value;
    private $_render;


    public function __construct()
    {
        $this->_resetFlags();
        $this->_resetFormFlags();
    }

    /**
     * Retrieve a class attribute
     *
     * @param string $attr
     * @return mixed
     */
    public function get(string $attr)
    {
        return $this->{'_' . $attr};
    }

    public function set(string $attr, $value): void
    {
        $this->{'_' . $attr} = $value;
    }

    public function open(): string
    {
        $props = [
            'action' => $this->_url,
            'class' => 'uk-form-stacked',
            'method' => $this->_Fmethod === 'get' ? 'get' : 'post'
        ];

        if ($this->_Fmultipart) {
            $props['enctype'] = 'multipart/form-data';
        }

        $attrs = $this->_buildAttrs($props);

        $ret = '<form ' . $attrs . '>';

        if ($this->_Fmethod !== 'get') {
            $ret .= csrf_field();

            if ($this->_Fmethod !== 'post') {
                $ret .= method_field($this->_Fmethod);
            }
        }

        $this->_resetFlags();

        return $ret;
    }

    public function close(): string
    {
        $ret = '</form>';

        $this->_resetFormFlags();
        $this->_resetFlags();

        return $ret;
    }

    public function anchor(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    public function button(): string
    {
        return $this->_renderButtonOrAnchor();
    }


    public function checkbox(): string
    {
        return $this->_renderCheckboxOrRadio();
    }

    public function email(): string
    {
        return $this->_renderInput('email');
    }

    public function file(): string
    {
        $attrs = $this->_buildAttrs();

        return $this->_renderWrapperCommonField('<input ' . $attrs . '>');
    }

    public function hidden(): string
    {
        $value = $this->_getValue();

        $attrs = $this->_buildAttrs(['value' => $value]);

        $this->_resetFlags();

        return '<input ' . $attrs . '>';
    }

    public function number(): string
    {
        return $this->_renderInput('number');
    }

    public function password(): string
    {
        return $this->_renderInput('password');
    }

    public function range(): string
    {
        return $this->_renderInput('range');
    }

    public function radio(): string
    {
        return $this->_renderCheckboxOrRadio();
    }

    public function reset(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    public function select(): string
    {
        $attrs = $this->_buildAttrs();

        $value = $this->_getValue();

        $options = '';

        if ($this->_multiple) {
            if (!is_array($value)) {
                $value = [$value];
            }

            foreach ($this->_options as $key => $label) {
                if($label === 'None'){
                    $key = null;
                }
                if (in_array($key, $value, true)) {
                    $match = true;
                } else {
                    $match = false;
                }

                $checked = ($match) ? ' selected' : '';

                $options .= '<option value="' . $key . '" ' . $checked . '>' . $label . '</option>';
            }
        } else {
            foreach ($this->_options as $optvalue => $label) {
                if($label === 'None'){
                    $optvalue = null;
                }
                $checked = $optvalue === $value ? ' selected' : '';

                $options .= '<option value="' . $optvalue . '" ' . $checked . '>' . $label . '</option>';
            }
        }

        return $this->_renderWrapperCommonField('<select ' . $attrs . '>' . $options . '</select>');
    }

    public function submit(): string
    {
        return $this->_renderButtonOrAnchor();
    }

    public function text(): string
    {
        return $this->_renderInput();
    }

    public function textarea(): string
    {
        $attrs = $this->_buildAttrs(['rows' => 3]);

        $value = $this->_getValue();

        return $this->_renderWrapperCommonField('<textarea ' . $attrs . '>' . $value . '</textarea>');
    }

    private function _renderInput($type = 'text'): string
    {
        $value = $this->_getValue();

        $attrs = $this->_buildAttrs(['value' => $value, 'type' => $type]);

        return $this->_renderWrapperCommonField('<input ' . $attrs . '>');
    }

    private function _renderButtonOrAnchor(): string
    {
        $disabled = $this->_disabled ? ' disabled' : '';
        $full = $this->_full ? ' uk-width-1-1' : '';
        $icon = $this->_getIcon('button');
        $outline = $this->_outline ? ' uk-button-outline' : '';
        $size = $this->_size ? ' uk-button-' . $this->_size : '';
        $value = $this->_e($this->_value);

        if ($this->_icon && array_key_exists('flip', $this->_icon)) {
            if ($this->_icon['flip']) {
                $value .= $icon;
            } else {
                $value = $icon . $value;
            }
        }

        $color = ' uk-button-' . $this->_color;

        $class = 'uk-button' . $outline . $color . $size . $full;

        if ($this->_type === 'anchor') {
            $href = $this->_url ?: 'javascript:void(0)';

            $attrs = $this->_buildAttrs([
                'class' => $class . $disabled,
                'href' => $href,
                'role' => 'button',
                'aria-disabled' => $disabled ? 'true' : null
            ]);

            $ret = '<div class="uk-margin"><a ' . $attrs . '>' . $value . '</a></div>';
        } else {
            $attrs = $this->_buildAttrs(['class' => $class, 'type' => $this->_type]);

            $ret = '<div class="uk-margin"><button ' . $attrs . ' ' . $disabled . '>' . $value . '</button></div>';
        }

        $this->_resetFlags();

        return $ret;
    }

    private function _renderCheckboxOrRadio(): string
    {
        $attrs = $this->_buildAttrs([
            "type" => $this->_type,
            "value" => $this->_meta['value']
        ]);

        $error = $this->_getValidationFieldMessage();
        $for = $this->_id ?: $this->_name;
        $inline = $this->_inline ? ' uk-inline' : '';
        $label = $this->_e($this->_label);

        $this->_resetFlags();

        return '<div class="uk-margin ' . $inline . '"><label for="' . $for . '"><input ' . $attrs . '> ' . $label . '</label>' . $error . '</div>';
    }

    private function _renderWrapperCommonField(string $field): string
    {
        $error = $this->_getValidationFieldMessage();
        $help = $this->_getHelpText();
        $icon = $this->_getIcon();
        $label = $this->_getLabel();

        $formIcon = '';

        if ($icon && array_key_exists('flip', $this->_icon)) {
            if (!$this->_icon['flip']) {
                $formIcon .= ' uk-form-with-icon';
            } else {
                $formIcon .= ' uk-form-with-icon-flip';
            }
        }

        $this->_resetFlags();

        $input = '<div class="uk-margin' . $formIcon . '">' . $label;

        if ($icon) {
            $input .= '<div class="uk-inline uk-width-1-1">' . $icon;
        }

        $input .= $field;

        if ($icon) {
            $input .= '</div>';
        }

        $input .= $help . $error . '</div>';

        return $input;
    }

    private function _getHelpText(): string
    {
        $id = $this->_getIdHelp();

        return $this->_help ? '<small id="' . $id . '" class="uk-text-muted">' . $this->_e($this->_help) . '</small>' : '';
    }

    private function _getIcon(string $render = 'input'): string
    {
        $icon = $this->_icon;

        $result = '';

        if ($icon) {
            $attrs = '';
            $class = '';

            $clickable = $icon['clickable'];
            $flip = $icon['flip'];
            $name = $icon['icon'];

            if ($render === 'input') {
                $class = 'uk-form-icon';

                if ($flip) {
                    $class .= ' uk-form-icon-flip';
                }
            } else if ($render === 'button') {
                $class = 'uk-margin-small-right';

                if ($flip) {
                    $class = 'uk-margin-small-left';
                }
            }

            if ($icon['attrs']) {
                foreach ($icon['attrs'] as $key => $value) {
                    if ($value === null) {
                        continue;
                    }

                    if ($key === 'class') {
                        $class .= ' ' . htmlspecialchars($value);
                    } else {
                        if (is_string($value)) {
                            $value = '="' . htmlspecialchars($value) . '" ';
                        } else {
                            $value = '';
                        }

                        $attrs .= $key . $value;
                    }
                }
            }

            if ($clickable && $render === 'input') {
                $result = '<a class=" ' . $class . '" uk-icon="' . $name . '" ' . $attrs . '></a>';
            } else {
                $result = '<span class=" ' . $class . '" uk-icon="' . $name . '" ' . $attrs . '></span>';
            }
        }

        return $result;
    }

    private function _getId(): ?string
    {
        $id = $this->_id;

        if (!$id && $this->_name) {
            $id = $this->_name;

            if ($this->_type === 'radio') {
                $id .= '-' . Str::slug($this->_meta['value']);
            }
        }

        if (!$id) {
            return null;
        }

        return $this->_Fprefix . $id;
    }

    private function _getIdHelp(): string
    {
        $id = $this->_getId();

        return $id ? 'help-' . $id : '';
    }

    private function _getLabel(): string
    {
        $label = $this->_label === true ? $this->_name : $this->_label;

        $result = '';

        if ($label) {
            $id = $this->_getId();

            $result = '<label class="uk-form-label" for="' . $id . '">' . $this->_e($label) . '</label>';
        }

        return $result;
    }

    private function _getValidationFieldClass(): string
    {
        if (!$this->_name) {
            return '';
        }

        if (session('errors') === null) {
            return '';
        }

        if ($this->_getValidationFieldMessage()) {
            return ' uk-form-danger';
        }

        return '';
    }


    private function _getValidationFieldMessage(string $prefix = '<span class="uk-label uk-label-danger"><small>', string $suffix = '</small></span>'): ?string
    {
        $errors = session('errors');

        if (!$errors) {
            return null;
        }

        $error = $errors->first($this->_name);

        if (!$error) {
            return null;
        }

        return $prefix . $error . $suffix;
    }


    private function _getValue()
    {
        $name = $this->_name;

        if ($this->_hasOldInput()) {
            return old($name);
        }

        if ($this->_value !== null) {
            return $this->_value;
        }

        return $this->_Fdata[$name] ?? '';
    }


    private function _buildAttrs(array $props = [], array $ignore = []): string
    {
        $ret = '';

        $props['name'] = $this->_name;
        $props['autocomplete'] = $props['name'];

        $props['class'] = $props['class'] ?? '';
        $props['id'] = $this->_getId();
        $props['type'] = $this->_type;

        if ($this->_help) {
            $props['aria-describedby'] = $this->_getIdHelp();
        }

        if ($this->_placeholder) {
            $props['placeholder'] = $this->_e($this->_placeholder);
        }

        if ($this->_type === 'select' && $this->_multiple) {
            $props['name'] .= '[]';
        }

        if (!$props['class']) {
            if (
                $this->_type === 'email' ||
                $this->_type === 'number' ||
                $this->_type === 'password' ||
                $this->_type === 'text'
            ) {
                $props['class'] = 'uk-input';
            } else {
                $props['class'] = 'uk-' . $this->_type;
            }
        }

        if ($this->_size && ($this->_type !== 'button' && $this->_type !== 'submit')) {
            $props['class'] .= ' uk-form-' . $this->_size;
        }

        $props['class'] .= ' ' . $this->_getValidationFieldClass();

        if (isset($this->_attrs['class'])) {
            $props['class'] .= ' ' . $this->_attrs['class'];
        }

        if (isset($this->_attrs['required'])) {
            $props['required'] = "required";
        }

        $props['class'] = trim($props['class']);

        if (!$props['class']) {
            $props['class'] = null;
        }

        if ($this->_disabled) {
            $ret .= 'disabled ';
        }

        if ($this->_type === 'select' && $this->_multiple) {
            $ret .= 'multiple ';
        }

        if ($this->_readonly) {
            $ret .= 'readonly ';
        }

        if (in_array($this->_type, ['radio', 'checkbox'])) {
            $value = $this->_getValue();

            if ($value && ($this->_type === 'checkbox' || ($this->_type === 'radio' && $value === $this->_meta['value']))) {
                $ret .= 'checked ';
            }
        }

        if ($this->_type === 'hidden') {
            unset($props['autocomplete'], $props['class']);
        }

        $allProps = array_merge($this->_attrs, $props);

        foreach ($allProps as $key => $value) {
            if ($value === null) {
                continue;
            }

            $ret .= $key . '="' . htmlspecialchars($value) . '" ';
        }

        return trim($ret);
    }


    private function _e($key): string
    {
        $fieldKey = $key ?: $this->_name;

        return $this->_Flocale ? __($this->_Flocale . '.' . $fieldKey) : $fieldKey;
    }


    private function _hasOldInput(): bool
    {
        return count((array)old()) !== 0;
    }


    private function _resetFormFlags(): void
    {
        $this->_Fdata = [];
        $this->_Flocale = '';
        $this->_Fmethod = 'post';
        $this->_Fmultipart = false;
        $this->_Fprefix = '';
    }

    private function _resetFlags(): void
    {
        $this->_attrs = [];
        $this->_color = 'default';
        $this->_disabled = false;
        $this->_full = false;
        $this->_help = '';
        $this->_id = '';
        $this->_icon = [];
        $this->_inline = false;
        $this->_label = '';
        $this->_meta = [];
        $this->_multiple = false;
        $this->_name = '';
        $this->_options = [];
        $this->_outline = false;
        $this->_placeholder = '';
        $this->_readonly = false;
        $this->_render = null;
        $this->_size = '';
        $this->_type = '';
        $this->_url = '';
        $this->_value = '';
    }
}
