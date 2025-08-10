<header class="header">
    <h2 class="u-name">Task <b>Manager</b>
        <label for="checkbox">
            <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
        </label>
    </h2>
    <span class="notification" id ="notification-btn">
        <i class="fas fa-bell" aria-hidden="true"></i>
        <div class="notification-badge" id="notificationNum"></div>
    </span>
</header>
<div class = "notification-bar" id="notification-bar">
    <ul id="notifications">
       
    </ul>
</div>

<script type="text/javascript">
    var openNotification = false;

	const notification = ()=> {
		let notificationBar = document.querySelector("#notification-bar");
		if (openNotification) {
			notificationBar.classList.remove('open-notification');
			openNotification = false;
		}else {
			notificationBar.classList.add('open-notification');
			openNotification = true;
		}
	}
	let notificationBtn = document.querySelector("#notification-btn");
	notificationBtn.addEventListener("click", notification);
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#notificationNum").load("app/notification-count.php", function(response){
            if ($.trim(response) == "" || response == "0") {
                $("#notificationNum").hide();
            } else {
                $("#notificationNum").show();
            }
        });
        $("#notifications").load("app/notification.php");
    });
</script>