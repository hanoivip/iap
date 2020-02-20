@extends('hanoivip::layout') 
 
@section('title', 'Congratulation!')

@section('content')


@if (isset($message))
<form>
<p class="success">
{{$message}}
</p>
<img src="{{asset('images/success.png')}}"/>
</form>
@endif


@if (isset($error))
<form>
<p class="error">
{{$error}}
</p>
</form>
@endif

@endsection