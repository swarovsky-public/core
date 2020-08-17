@extends('layouts.admin')

@section('content')


<div class="uk-section-small">
    <div class="uk-container uk-container-large">
        <div class="uk-flex uk-flex-between uk-flex-middle">
            <ul class="uk-breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                <li><a href="{{route("{$model}.index")}}">Index</a></li>
                <li><span>Create</span></li>
            </ul>
            <a class="uk-button uk-button-primary uk-button-small" href="{{route($model.'.index')}}">
                Back to Index
            </a>
        </div>
        <hr>

        @component('crud._form', [
                'item' => $item,
                'schema' => $schema,
                'action' => "{$model}.store",
                'model' => $model,
                'method' => 'POST'
                ])@endcomponent
    </div>
</div>
@endsection

