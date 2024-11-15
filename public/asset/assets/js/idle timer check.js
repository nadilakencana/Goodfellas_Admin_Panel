var reloaded = 0;
var idleTime = 0;
if(window.localStorage.reloadTimes){
	reloaded = parseInt(window.localStorage.reloadTimes); // buat cek aja berapa kali di refresh pakai methode ini 
}

function timerIncrement() {
	idleTime = idleTime + 1;
	if (idleTime > 9) { // 5 menit ( atau atur disini untuk n menit )
		reloaded = reloaded + 1;
		window.localStorage.reloadTimes = reloaded+'';
		window.location.reload();
	}
}


$(document).ready(function(){
	var idleInterval = setInterval(timerIncrement, 60000); // bisa di sesuaikan pengecekan interval mau dalam menit atau detik ( tapi lebih aman di 30 dtk ++ );
	
	$(this).mousemove(function (e) {
		idleTime = 0;
	});
	$(this).keypress(function (e) {
		idleTime = 0;
	});
})