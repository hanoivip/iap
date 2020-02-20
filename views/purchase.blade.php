@extends('hanoivip::layout') 

@section('title', 'Pay with paypal')

@section('content')

<form method="post" action="{{route('purchase.do')}}">
	{{ csrf_field() }}
	<p>{{$item->merchant_title}}</p>
	<img src="{{$item->merchant_image}}" alt="{{$item->merchant_image}}" />
	<p>Price: {{$item->price}} USD</p>
	<input type="hidden" id="client" name="client" value="{{$client}}" /> <input
		type="hidden" id="order" name="order" value="{{$order}}" /> <input
		type="hidden" id="item" name="item" value="{{$item->merchant_id}}" />
	<button type="submit"
		style="border-style: none; background-color: white;">
		<img src="{{asset('images/pp.jpg')}}" style="width: 100%;" />
	</button>
</form>

@endsection
