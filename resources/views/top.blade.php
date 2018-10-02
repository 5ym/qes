<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Entry System nightly edition</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
    </style>
  </head>
  <body>
		<div class="container">
			<h1>QR Entry System nightly editionF</h1>
	  	<form>
				<div class="input-field">
					<input id="name" type="text" required>
		      <label for="name">Name</label>
				</div>
				<div class="input-field">
					<input id="contact" type="text" required>
		      <label for="contact">Contact</label>
				</div>
				<div class="input-field">
					<input id="address" type="text" required>
		      <label for="address">Address</label>
				</div>
				<button class="btn waves-effect" type="submit">Submit</button>
			</form>
		</div>
  </body>
</html>
