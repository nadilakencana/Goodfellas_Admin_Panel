<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notif Frame</title>
    <link rel="stylesheet" href="{{ asset('asset/tamplate/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="text/javascript" src="{{ asset('asset/assets/js/custom_js.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/assets/css/custom.css') }}">
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<audio class="audioPlace" controls autoplay>
		<source src="{{ asset('asset/assets/audio_notif.wav') }}" type="audio/wav">
	</audio>
    
    <script src="{{ asset('asset/tamplate/plugins/jquery/jquery.min.js') }}"></script>
    <script>
			$(()=>{

				
				window.addEventListener('message', function(event) {
					console.log("Message received from the parent: " + event.data); // Message received from parent
					console.log('play Notif sound');
					var notifAudio = $('.audioPlace')[0];
					notifAudio.play();
				});
				
			});
    </script>
</body>
</html>
