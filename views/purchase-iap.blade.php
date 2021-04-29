@extends('hanoivip::layout') 

@section('title', 'Pay with paypal')

@section('content')


@foreach ($items as $item)

<form method="post" action="{{route('purchase.do')}}" id="purchase.form" name="purchase.form">
	{{ csrf_field() }}
	<p>{{$item->merchant_title}}</p>
	<img src="{{$item->merchant_image}}" alt="{{$item->merchant_image}}" />
	<p>Price: {{$item->price}}</p>
	<input type="hidden" id="client" name="client" value="{{$client}}" /> 
	<input type="hidden" id="item" name="item" value="{{$item->merchant_id}}" />
	<button onclick="purchaseAndroid(this);">Purchase</button>
</form>
@endforeach 

@endsection
