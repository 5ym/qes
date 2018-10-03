<?php if(!Auth::check()) header("Location: /login"); ?>
@extends('wire')
@section('content')
<h1>status</h1>
<table>
	<tbody>
		<tr><th>Name</th><td>{{$name}}</td></tr>
		<tr><th>Contact</th><td>{{$contact}}</td></tr>
		<tr><th>Address</th><td>{{$address}}</td></tr>
		<tr><th>Status</th><td>{{$status}}</td></tr>
	</tbody>
</table>
<a class="btn waves-effect" href="" id="pay">pay</a>
<a class="btn waves-effect" href="" id="entry">entry</a>
<a class="btn waves-effect" href="" id="pe">pey and entry</a>
<script>
	$('#pay').on('click', function() {
		event.preventDefault();
	});
	$('#entry').on('click', function() {
		event.preventDefault();
	});
	$('#pe').on('click', function() {
		event.preventDefault();
	});
</script>
@endsection
