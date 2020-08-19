@extends('layouts.app')

@section('content')
    <div class="uk-container">

        <h2>Profile: {{$user->name}}</h2>
        @if (Storage::disk('s3')->exists('users/' . $user->id.'.jpg'))
            <img src="data:image/png;base64,{!! base64_encode(Storage::disk('s3')->get('users/' . $user->id.'.jpg')) !!}"
                 alt="{{$user->name . ' avatar'}}">
        @endif
        <form action="{{route('user.update_profile')}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="file" name="avatar" id="avatar">
            <button type="submit">Upload</button>
        </form>


    </div>
@endsection
