@extends(config('swarovsky-core.layout.admin'))

@section('content')
    <div class="uk-section-small">
        <div class="uk-container uk-container-large">
            <div class="uk-card uk-card-default uk-card-body uk-margin-remove uk-shadow-remove">
                <div class="uk-flex uk-flex-between uk-flex-middle">
                    <ul class="uk-breadcrumb">
                        <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                        <li><a href="{{route(\Swarovsky\Core\Helpers\StrHelper::plural($model).'.index')}}">Index</a>
                        </li>
                        <li><span>Edit</span></li>
                    </ul>
                    <div>
                        <a class="uk-button uk-button-primary uk-button-small"
                           href="{{route(\Swarovsky\Core\Helpers\StrHelper::plural($model).'.index')}}">
                            Back to Index
                        </a>
                        <a class="uk-button uk-button-secondary uk-button-small"
                           href="{{route(\Swarovsky\Core\Helpers\StrHelper::plural($model).'.show', [$model => $item])}}">
                            Show
                        </a>
                    </div>
                </div>
                <hr>

                @component('swarovsky-core::crud._form', [
                    'item' => $item,
                    'schema' => $schema,
                    'action' => \Swarovsky\Core\Helpers\StrHelper::plural($model).'.update',
                    'model' => $model,
                    'method' => 'PUT'
                    ])@endcomponent
            </div>
        </div>
    </div>
@endsection
