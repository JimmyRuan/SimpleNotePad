@extends('layouts.app')

@section('content')
    <div class="title m-b-md">
        BrandBoom
    </div>

    <div class="links">
        <a href="{{route('previewer.index')}}">Image Previewer</a>
        <a href="{{route('previewer.index')}}">Note Taker</a>
    </div>
@endsection
