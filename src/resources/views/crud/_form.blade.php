<?php
    $scriptPush = '';
    $form = new \Swarovsky\Core\Helpers\FormHelper($action, $method, $model, $item);

    $form->open();

    foreach($schema as $column => $type):
        if(!in_array($column, ['id', 'updated_at', 'created_at'])):
           echo $form->input($type, $column, $item);
        endif;
    endforeach;

    foreach ($item->getHasManyRelations() as $relation){
          $class = app($relation);
          $column = \Swarovsky\Core\Helpers\StrHelper::getClassModelName($class);
          $parsedObjects = \Swarovsky\Core\Helpers\CacheHelper::get($relation)->pluck('name', 'id')->toArray();
          echo $form->input(
              [
                  'type' => 'hasManyRelations',
                  'items' => $parsedObjects
              ], $column, $item);
          $scriptPush .= "$('#{$column}').select2();";
    }
    if(view()->exists('swarovsky-core::'.$model.'._form')){ ?>
        @include('swarovsky-core::'.$model.'._form')
    <?php
    }

    $form->send();
    $form->close();
?>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
            defer>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            $('.datetimepicker').datepicker({
                format: 'h'
            });
            {!! $scriptPush !!}
            @stack('script-push')
        })
    </script>
@endpush
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
@endpush
<!-- TODO -->
