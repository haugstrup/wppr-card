@extends("layout")
@section("title")
  Home
@stop
@section("content")
<h4>WPPR Cards</h4>

{{ Form::open(array('route' => array('search'), 'method' => 'get', 'class' => 'search-form')) }}
<div class="input-group">
  {{ Form::text('query', null, array('class' => 'form-control', 'autocorrect' => 'off', 'autocapitalize' => 'off', 'placeholder' => 'Enter name, player no. or email...')) }}
  <span class="input-group-btn">
    {{ Form::button('Search', array('class' => 'btn btn-info', 'type' => 'submit')) }}
  </span>
</div>
{{ Form::close() }}

<p>Create your own mobile-friendly <a href="http://ifpapinball.com/">IFPA</a> calling cards by searching below. Add your calling card to your phone's home screen for easy access when a tournament director asks for your IFPA player number.</p>

<p>This is a small experiment created by <a href="http://www.solitude.dk/">Andreas Haugstrup Pedersen</a> to play with the IFPA API. I'm using <a href="https://github.com/heyrocker/IfpaApi">Greg Dunlap's API client</a>.</p>
@stop
