@extends('swarovsky-core::layouts.admin')

@section('content')
<div class="uk-section-small">
    <div class="uk-container uk-container-large">
        <div class="uk-flex uk-flex-between uk-flex-middle">
            <ul class="uk-breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">Admin dashboard</a></li>
                <li><span>Index</span></li>
            </ul>
            <a class="uk-button uk-button-primary uk-button-small" href="{{route($model.'.create')}}">
                Create new {{$model}}
            </a>
        </div>

        <hr>
        <table class="uk-table uk-table-striped">
            <thead>
            <tr>
                @foreach($schema as $column => $type)
                    <th>{{$column}}</th>
                @endforeach
                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    @if (view()->exists('admin.'.$model.'.index'))
                        @component('admin.'.$model.'.index', ['item' => $item])@endcomponent
                    @else
                        @foreach($schema as $column => $type)
                            <td>{{ Str::limit($item->$column, $limit = 15, $end = '...')}}</td>
                        @endforeach
                    @endif
                    <td>
                        <div class="row justify-content-between" style="margin: 0;">
                            <a href="{{route($model.'.show', $item)}}">Show</a>
                            <a href="{{route($model.'.edit', $item)}}">Edit</a>
                            <form method="POST" action="{{route($model.'.destroy', $item)}}"
                                  style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')"
                                        style="padding: 0;border: none;background: transparent;cursor: pointer;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{$items->links()}}

    </div>
</div>
@endsection
