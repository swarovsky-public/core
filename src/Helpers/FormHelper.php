<?php

namespace Swarovsky\Core\Helpers;
use Swarovsky\Core\Models\AdvancedModel;
use Swarovsky\Core\Services\FormService;


class FormHelper
{
    private FormService $formService;
    private string $route;
    private string $method;
    private string $modelName;
    private object $model;

    public function __construct(string $route, string $method, string $modelName, object $model)
    {
        $this->formService = new FormService();
        $this->route = $route;
        $this->method = $method;
        $this->modelName = $modelName;
        $this->model = $model;
    }

    public function open(): void
    {
        echo $this->formService->open()
        ->route($this->route, [$this->modelName => $this->model])
        ->method($this->method)
        ->fill($this->model);
    }

    public function close(): void
    {
        echo $this->formService->close();
    }

    public function input(array $input, string $column, AdvancedModel $item): string
    {
        foreach ($item->getRelations() as $key => $relation) {
            if ($key === $column) {
                $rels = $relation::all();
                $array = ['None'];
                foreach ($rels as $rel) {
                    $array[$rel->id] = $rel->name ?: ($rel->title ?: "undefined");
                }
                return $this->formService->select($column, $column, $array)->value($this->model->$column);
            }
        }


        switch ($input['type']) {

            case in_array($input['type'], ['int', 'bigint']):
                $print_input = $this->formService->text($column, $column.'|'.$input['type'])->type('number')->value($this->model->$column);
                break;

            case $input['type'] === 'text':
                $print_input = $this->formService->textarea($column, $column.'|'.$input['type'])->value($this->model->$column);
                break;

            case $input['type'] === 'timestamp':
                $print_input = '<input required type="text"  class="uk-input datetimepicker" id="' . $column . '" name="' . $column . '" value="' . $item->$column . '">';
                break;

            default:
                $print_input = $this->formService->text($column, $column.'|'.$input['type'])->value($this->model->$column);

        }

        if($input['required']){
            $print_input->attrs(['required' => true]);
        }

        return $print_input;
    }

    public function send(): void
    {
        echo  '<hr>
        <div class="uk-flex uk-flex-right">
            <input type="submit" class="uk-button uk-button-primary uk-button-small" value="Save">
        </div>';
    }

}
