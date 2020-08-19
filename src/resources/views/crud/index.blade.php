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
                    <a class="uk-button uk-button-primary uk-button-small"
                       href="{{route(\Swarovsky\Core\Helpers\StrHelper::getClassModelName($item).'.create')}}">
                        Create new {{class_basename($item)}}
                    </a>
                </div>

                <hr>

                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-small uk-table-striped uk-table-hover uk-table-responsive">
                        <thead>
                        <tr>
                            @if(count($objects) > 0)
                                @foreach($objects[0]->getOrderedColumns() as $column)
                                    <th>
                                        @if(is_array($column))
                                            {{$column[0]}}
                                        @else
                                            {{$column}}
                                        @endif
                                    </th>
                                @endforeach
                                <th>actions</th>
                            @else
                                <th>Table is empty</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($objects as $object)
                            <tr>
                                @foreach($object->getOrderedColumns() as $column)
                                    <td>
                                        @if(is_array($column))
                                            {!! $column[1] !!}
                                        @else
                                            {{ Str::limit($object->$column, $limit = 15, $end = '...')}}
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <div class="row justify-content-between" style="margin: 0;">
                                        <a href="{{route(\Swarovsky\Core\Helpers\StrHelper::getClassModelName($item).'.show', $object)}}"
                                           title="Show" class="uk-icon-button uk-button-primary" uk-icon="info"></a>
                                        <a href="{{route(\Swarovsky\Core\Helpers\StrHelper::getClassModelName($item).'.edit', $object)}}"
                                           title="Edit" class="uk-icon-button uk-button-default" uk-icon="pencil"></a>
                                        <form method="POST"
                                              action="{{route(\Swarovsky\Core\Helpers\StrHelper::getClassModelName($item).'.destroy', $object)}}"
                                              style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')"
                                                    title="Delete" class="uk-icon-button uk-button-danger" uk-icon="trash">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{$objects->links('swarovsky-core::vendor.pagination.uikit')}}
            </div>
        </div>
    </div>
@endsection
