<?php
    $rf_permissions = \Swarovsky\Core\Helpers\CacheHelper::get(\Swarovsky\Core\Models\Permission::class)->pluck('name', 'id')->toArray();
    ksort($rf_permissions);
    echo $form->input(   [
        'type' => 'hasManyRelations',
        'items' => $rf_permissions
    ], 'permissions', $item);
?>

@push('script-push')
    $('#permissions').select2();
@endpush
