@php
    $form = new \Swarovsky\Core\Helpers\FormHelper($action, $method, $model, $item);

    $form->open();

    foreach($schema as $column => $type):
        if(!in_array($column, ['id', 'updated_at', 'created_at'])):
           echo $form->input($type, $column, $item);
        endif;
    endforeach;

    $form->send();
    $form->close();
@endphp

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
            defer>
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            $('.datetimepicker').datepicker({
                format: 'h'
            });
        })
    </script>
@endpush
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush
<!-- TODO -->
