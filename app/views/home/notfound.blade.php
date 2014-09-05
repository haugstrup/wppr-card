@extends("layout")
@section("content")
@include('header')
<p class="alert alert-danger">Ack! I can't find what you're looking for.</p>

<a class="card__button-full-width btn" href="{{{URL::route('front')}}}">Back to front</a>
@stop
