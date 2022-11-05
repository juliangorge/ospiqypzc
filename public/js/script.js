moment.locale('es');

// <!-- Notifications -->

var title = document.title;
function reqListener () {
	var content = JSON.parse(this.responseText);

	document.querySelector('.noti-icon-badge').setAttribute("style", (!content.length ? 'visibility: hidden' : 'visibility: visible'));

	if(!content.length){
		document.querySelector('.clear-btn').setAttribute('onclick', 'return false;');
	}else{
		document.title = '(' + content.length + ') ' + title;
		document.querySelector('.clear-btn').removeAttribute('onclick');
	}

	var notifications = document.querySelector('.notification-list').querySelector('.simplebar-content');
	notifications.innerHTML = '';

  	for (var key in content){
  		notifications.innerHTML += `
			<a href="javascript:void(0);" class="dropdown-item notify-item px-2">
            	<div class="notify-icon bg-warning">
                	<i class="mdi mdi-exclamation-thick"></i>
				</div>
                <p class="notify-details">` + content[key].text + `
           			<small class="text-muted">` + moment(content[key].date.date).fromNow() + `</small>
            	</p>
			</a>
		`;
	}
}

function getNotifications(){
	var oReq = new XMLHttpRequest();
	oReq.addEventListener("load", reqListener);
	oReq.open("GET", "/admin/dashboard/getActiveNotifications");
	oReq.send();
}

setInterval(() => { 
	getNotifications(); 
}, 300000);

$('.notification-list a').click(() => {
	var oReq = new XMLHttpRequest();
	oReq.open("POST", "/admin/dashboard/deactivateNotifications");
	oReq.send();
});

$(document).ready(() => {
    setTimeout(() => {
        $('.alert').alert('close');
    }, 2000);

    $('.loading-effect').click(() => {
    	$(this).html('<i class="mdi mdi-loading mdi-spin"></i> Cargando...');
    	$(this).addClass('disabled');
    });
});