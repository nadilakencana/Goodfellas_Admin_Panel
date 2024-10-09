<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('asset/tamplate/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('asset/assets/css/custom.css') }}">
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    {{-- <div class="pop-notification">
        <div class="position-object">
             <div class="card">
                <div class="text-pop">
                    <p class="text">tekan ok agar notifikasi suara berbunyi</p>
                </div>
                <div class="action-pop">
                    <button type="focus()" class="btn btn-primary confirm-notif">Ok</button>
                </div>
            </div>
        </div>
       
    </div> --}}
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset('asset/assets/image/LOGO PUTIH.png') }}" alt="AdminLTELogo" height="60" width="60">
        </div>
        @include('layout.navbar')
        @include('layout.sidebar')
        <div class="content-wrapper">
            @yield('content')
        </div>
        <div id="triggerElementId"></div>
    </div>
	{{-- <iframe class="frameHolder" src="{{route('notif')}}" allow="autoplay" style="display:none;"></iframe> --}}



    <script src="{{ asset('asset/tamplate/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/js/adminlte.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/js/pages/dashboard2.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    {{-- <script src="http://192.168.88.22:3388/socket.io/socket.io.js"></script> --}}
    <script src="http://192.168.1.22:8000//socket.io/socket.io.js"></script>
    <script type="text/javascript" src="{{ asset('asset/assets/js/custom_js.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


    <script>
        // Simple Datatable
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);

            $(function () {
                bsCustomFileInput.init();

              });
    </script>
     {{--  check connetion  --}}
     <script>

        const socket = io('http://192.168.1.22:8000');
        socket.on('connect', () => {
            const statusDiv = document.getElementById('status');
            const notifStatus = document.getElementById('notif-status');
           
            statusDiv.textContent = 'Connected';
            notifStatus.style.background = 'green';
            console.log("connected");
        });
        socket.on('disconnect', () => {
            const statusDiv = document.getElementById('status');
            const notifStatus = document.getElementById('notif-status');
           
            statusDiv.textContent = 'Disconnected';
            addLogConnectLocalStorage(status, 'Disconnect', 'los connection')
            notifStatus.style.background = 'red';
            
            console.log("disconnected");
        });
        socket.on('internet-status', (isConnected) => {
            const statusDiv = document.getElementById('status');
            const notifStatus = document.getElementById('notif-status');
            if (isConnected) {
                statusDiv.textContent = 'Connected';
                notifStatus.style.background = 'green';
            } else {
                statusDiv.textContent = 'Disconnected';
                notifStatus.style.background = 'red';
            }
        });

        if (!localStorage.getItem('pcguid')) {
            localStorage.setItem('pcguid', makeid(8));
        }
        function makeid(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
            }
            return result;
        }
        
        function addLogConnectLocalStorage(dataStream, fromAction, result){
            let logStream = localStorage.getItem('data_log_stream');
            
            let uid = localStorage.getItem('pcguid');
            let newLog = {
                'data-Log-connect-stream': dataStream,
                'timestamp':new Date().toISOString(), 
                'Date_Time': formatDate(new Date()),
                'from-action': fromAction,
                'result': result
            };

            
            if (!logStream) {
                // Jika logStream belum ada, buat objek baru
                logStream = {
                    'date': newLog.timestamp,
                    'uid': uid,
                    'logList': [newLog]
                };
            } else {
                // Jika logStream sudah ada, parse string JSON menjadi objek
                logStream = JSON.parse(logStream);

                if (logStream.uid === uid) {
                    // Jika UID sama, tambahkan log baru ke logList dan update date
                    logStream.logList.push(newLog);
                    logStream.date = newLog.timestamp;

                    if (logStream.logList.length > 1000) {
                        logStream.logList.shift();
                    }
                } else {
                    // Jika UID berbeda, buat objek baru (ini skenario yang jarang terjadi)
                    logStream = {
                        'date': newLog.timestamp,
                        'uid': uid,
                        'logList': [newLog]
                    };
                }
            }
            localStorage.setItem('data_log_stream', JSON.stringify(logStream));
        }

        function formatDate(date) {
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();
            let hours = ("0" + date.getHours()).slice(-2);
            let minutes = ("0" + date.getMinutes()).slice(-2);
            let seconds = ("0" + date.getSeconds()).slice(-2);

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }

        function getLocalstorage() {
            let logStream = localStorage.getItem('data_log_stream');
            if(!logStream){
                logStream = [];
            }else{
                logStream = JSON.parse(logStream);
            }
            return logStream
        }

        function RemoveLocalStorage() {
            let logStream = localStorage.getItem('data-log-stream');
            if(logStream){
                logStream = JSON.parse(logStream);
                let newDate =  new Date().getTime();
                logStream = logStream.filter(log => {
                    let logDate = new Date(log.timestamp).getTime();
                   return (now - logDate) < 86400000;
                });
                localStorage.setItem('data_log_stream', JSON.stringify(logStream));
            }
        }

        RemoveLocalStorage();

        let logs = getLocalstorage();
        console.log(logs)


    </script>


    {{-- <script>
            const socket = io('http://192.168.88.22:3388');
            var audioNotif = false;
            //socket.emit('notif-server', 'cms tes');

            $(()=>{
                $('.confirm-notif').focus();
				setTimeout(function(){
					
                        $('.pop-notification').on('keypress', function(e){
                            var key = e.which;
                            if(key === 13){
                                $('.pop-notification').hide();
                                audioNotif = true;
                                //$('.confirm-notif').click();
                                $('input').focus();
                            }
                           
                        })
                        $('.confirm-notif').on('click', function(e){
                            
                                $('.pop-notification').hide();
                                audioNotif = true;
                                //$('.confirm-notif').click();
                                $('input').focus();
                            
                        })
						
				}, 2000)

				socket.on('notif-server', (msg) => {
                    if(audioNotif){
                        var $tgtFrame = $('.frameHolder');
					    $tgtFrame[0].contentWindow.postMessage(true, "*");
                    }


				});
			})
            
            socket.on('error', (error) => {
                console.error('Socket Error:', error);
            });
           
            
    </script> --}}

    @yield('script')
</body>
</html>
