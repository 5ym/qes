<?php if(!Auth::check()) header("Location: /login"); ?>
@extends('wire')
@section('content')
	<h1>status</h1>
	<table>
		<tbody>
			<tr><th>Name</th><td>{{$name}}</td></tr>
			<tr><th>Contact</th><td>{{$contact}}</td></tr>
			<tr><th>Address</th><td>{{$address}}</td></tr>
			<tr><th>Status</th><td id="status">{{$status}}</td></tr>
			<tr><th>Secret</th><td>{{$secret}}</td></tr>
		</tbody>
	</table>
	<a class="btn waves-effect" href="" id="pay">pay</a>
	<a class="btn waves-effect" href="" id="entry">entry</a>
	<a class="btn waves-effect" href="" id="pe">pey and entry</a>
	<script>
		var jqxhr;
		setup = function() {
			event.preventDefault();
			if(jqxhr) return;
			$("a").addClass("disabled");
			$.ajaxSetup({headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
	　　}});
			jqxhr = $.ajax({
				url: "/status",
				type: "POST",
				dataType: "json",
				data: 'secret={{$secret}}&status='+$(this).attr("id")
			}).then(function(data) {
				jqxhr = false;
				$("a").removeClass("disabled");
				$('#status').text(data.status);
			});
		}
		$('#pay').on('click', setup);
		$('#entry').on('click', setup);
		$('#pe').on('click', setup);
	</script>
@endsection
