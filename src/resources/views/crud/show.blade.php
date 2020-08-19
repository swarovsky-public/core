@extends(config('swarovsky-core.layout.admin'))

@section('content')
    <div class="uk-section-small">
        <div class="uk-container uk-container-large">
            <div class="uk-card uk-card-default uk-card-body uk-margin-remove uk-shadow-remove">
                <div class="uk-flex uk-flex-between uk-flex-middle">
                    <ul class="uk-breadcrumb">
                        <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                        <li><span>Index</span></li>
                    </ul>
                    <div>
                        <a class="uk-button uk-button-primary uk-button-small"
                           href="{{route(\Swarovsky\Core\Helpers\StrHelper::plural($model).'.index')}}">
                            Back to Index
                        </a>
                        <a class="uk-button uk-button-secondary uk-button-small"
                           href="{{route(\Swarovsky\Core\Helpers\StrHelper::plural($model).'.edit', [$model => $item])}}">
                            Edit
                        </a>
                    </div>
                </div>
                <hr>


                @foreach($schema as $column => $type)
                    <h6><span  class="uk-text-bold">{{$column}}</span>: {{$item->$column}}</h6>
                @endforeach

            </div>
        </div>
    </div>
@endsection
