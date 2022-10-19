// <!-- Notifications -->

function reqListener () {
	var content = JSON.parse(this.responseText);
	document.querySelector('.noti-icon-badge').setAttribute("style", (!content.length ? 'visibility: hidden' : 'visibility: visible'));

	if(!content.length){
		document.querySelector('.clear-btn').setAttribute('onclick', 'return false;');
	}else{
		document.querySelector('.clear-btn').removeAttribute('onclick');
	}

	var notifications = document.querySelector('.notification-list').querySelector('.simplebar-content');
	notifications.innerHTML = '';

  	for (var key in content){
  		notifications.innerHTML += `
			<a href="javascript:void(0);" class="dropdown-item notify-item">
            	<div class="notify-icon bg-warning">
                	<i class="mdi mdi-exclamation-thick"></i>
				</div>
                <p class="notify-details">` + content[key].text + `
           			<small class="text-muted">Hace 1 min</small>
            	</p>
			</a>
		`;
	}
}

function getNotifications(){
	var oReq = new XMLHttpRequest();
	oReq.addEventListener("load", reqListener);
	oReq.open("GET", "/admin/dashboard/getNotifications");
	oReq.send();

	//setInterval(function(){ getNotifications(); }, 300000);
}

$(document).ready(function() {
    setTimeout(function(){
        $('.alert').alert('close');
    }, 2000);
});