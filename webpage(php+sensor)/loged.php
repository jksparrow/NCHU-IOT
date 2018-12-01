<!DOCTYPE html>
<html>

<head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        /* Always set the map height explicitly to define the size of the div
        * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.css" />        
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--include the highcharts library-->
    <script src="http://code.highcharts.com/highcharts.js"></script>
</head>

<body>
<?php
//使用會話記憶體儲的變數值之前必須先開啟會話
session_start();
//使用一個會話變數檢查登入狀態
if(isset($_SESSION['username'])){
	if(isset($_POST['submit'])){
		$_SESSION = array();
    	//如果存在一個會話cookie，通過將到期時間設定為之前1個小時從而將其刪除
    	if(isset($_COOKIE[session_name()])){
        	setcookie(session_name(),'',time()-3600);
    	}
    	session_destroy();				//使用內建session_destroy()函式呼叫撤銷會話
    	$home_url = 'index.php';		//location首部使瀏覽器重定向到另一個頁面
		header('Location:'.$home_url);
	}
	else{
		echo '<div class="container-fluid">
				<ul class="nav navbar-nav navbar-right">
					<li><button type="button" class="btn btn-default">'.$_SESSION['username'].'</button></li>
					<li><form method = "post" class="form-inline" action="'.$_SERVER['PHP_SELF'].'">
						<button type="submit" class="btn btn-default" name="submit"/>Log Out</button>
						</form></li>
					</ul>
				</div>';
	}
}
else{
	$home_url = 'needtolog.html';
    header('Location: '.$home_url);
}
?>
    <div id="map"></div>
    <script>
        var map;
        var maker;
        var geocoder;
        //var myLats = [];
        //var myLngs = [];
        var myAddress = [];
        var myCenter = { lat: 24.1209446, lng: 120.6742369 };
        //var myLatLngs = [{lat: 24.1209446, lng: 120.6742369},{lat: 25.0470542, lng: 121.5151335}];




        function initMap() {
            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById('map'), {
                center: myCenter,
                title: '中興大學',
                zoom: 8
            });

            $.ajax({
                url: 'getAddress.php',
                data: '',
                dataType: 'json',
                success: getDataSuccess
            });

        }



        function getDataSuccess(data) {
            for (i = 0; i < 11; i++) { //最多11個
                codeAddress(data[i].toString());
            }
        }


        function codeAddress(address1) {
            geocoder.geocode({ 'address': address1 }, function (results, status) {
                if (status == 'OK') {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    }); // end maker

                    //maker
                    var myContent = '<iframe src="http://localhost/iot/highchart.html?address=' + address1 + ' " width="450" height="410" frameborder="0" scrolling="no"></iframe> ';
                    var infowindow = new google.maps.InfoWindow({
                        content: myContent
                    });

                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });

                } // end if 
                else {
                    alert('Geocode was not successful for the following reason: ' + status);
                } // end else
            }); // end geocoder
        } // end function


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAtM83_HNs2MhPvu2YnahlOEzrnntmAJI&callback=initMap"
        async defer></script>
</body>

</html>