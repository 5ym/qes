@extends('wire')
@section('content')
	<h1>QR Entry System nightly edition</h1>
	<form id="entry">
		<div class="input-field">
			<input id="name" type="text" name="name" required>
      <label for="name">Name</label>
		</div>
		<div class="input-field">
			<input id="contact" type="text" name="contact" required>
      <label for="contact">Contact</label>
		</div>
		<div class="input-field">
			<input id="address" type="text" name="address" required>
      <label for="address">Address</label>
		</div>
		<button class="btn waves-effect" type="submit">Submit</button>
	</form>
	<form id="reent">
		<h2>Re get qr ticket</h2>
		<div class="input-field">
			<input id="secret" type="text" name="secret" required>
      <label for="secret">Secret</label>
			<span class="helper-text" data-error="wrong"></span>
		</div>
		<button class="btn waves-effect" type="submit">Submit</button>
	</form>
	<div id="qrcode"></div>
	<script>
		var jqxhr;
		$('#entry').on('submit', function() {
			event.preventDefault();
			if(jqxhr) return;
			$("form").find("button").addClass("disabled");
			$.ajaxSetup({headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
	　　	}});
			jqxhr = $.ajax({
				url: "/entry",
				type: "POST",
				dataType: "json",
				data: $(this).serialize()
			}).then(function(data) {
				$('form').remove();
				$('#qrcode').before('<h2>Your secret: '+data.randum+'</h2>');
				$('#qrcode').qrcode({
					text: "https://l.siteyui.site/status?secret="+String(data.randum),
					render: 'div'
				});
			}, function(data) {
				alert('Error');
				$("form").find("button").removeClass("disabled");
				jqxhr = false;
			});
		});
		$('#reent').on('submit', function() {
			event.preventDefault();
			if(jqxhr) return;
			$("form").find("button").addClass("disabled");
			$.ajaxSetup({headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
	　　	}});
			secret = $(this).find('input').val();
			jqxhr = $.ajax({
				url: "/entry?secret="+secret,
				type: "GET",
				dataType: "json",
			}).then(function(data) {
				$('form').remove();
				$('#qrcode').before('<h2>Your secret: '+secret+'</h2>');
				$('#qrcode').qrcode({
					text: "https://l.siteyui.site/status?secret="+String(secret),
					render: 'div'
				});
			}, function() {
				$('#reent').find('input').addClass('invalid');
				$("form").find("button").removeClass("disabled");
				jqxhr = false;
			});
		});
	</script>
@endsection
