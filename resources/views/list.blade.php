<?php if(!Auth::check()) header("Location: /login"); ?>
@extends('wire')
@section('content')
	<h1>List</h1>
	<table>
		<thead><tr><th>Name</th><th>Contact</th><th>Address</th><th>Secret</th><th>Status</th></tr></thead>
		<tbody>
			<?php foreach($list as $value): ?>
				<tr><td><a href="/status?secret=<?=$value->randum?>" target="_blank"><?=$value->name?></a></td><td><?=$value->contact?></td><td><?=$value->address?></td><td><?=$value->randum?></td><td id="<?=$value->randum?>"></td></tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<script>
		$.ajaxSetup({headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
　　}});
		$.ajax({
			url: "/list",
			type: "POST",
			dataType: "json",
			data: ''
		}).then(function(data) {
			Object.keys(data).forEach(function (key) {
				$('#'+key).text(data[key]);
			});
		});
	</script>
@endsection
