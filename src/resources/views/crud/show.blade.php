@extends('swarovsky-core::layouts.admin')

@section('content')
<div class="uk-section-small">
    <div class="uk-container uk-container-large">

        <div class="uk-flex uk-flex-between uk-flex-middle">
            <ul class="uk-breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                <li><span>Index</span></li>
            </ul>
            <div>
                <a class="uk-button uk-button-primary uk-button-small" href="{{route($model.'.index')}}">
                    Back to Index
                </a>
                <a class="uk-button uk-button-secondary uk-button-small" href="{{route($model.'.edit', [$model => $item])}}">
                    Edit
                </a>
            </div>
        </div>
        <hr>


        @foreach($schema as $column => $type)
            <h4 class="title">{{$column}}</h4>
            <p>{{$item->$column}}</p>
        @endforeach


        </div>
    </div>
@endsection
