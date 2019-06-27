</!DOCTYPE html>
<html>
<head>
	<title>地址选择</title>
	<link href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin: 0 auto;">
	<div id="allmap" style="height: 100%;"></div>
</div>
</body>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=usHzWa4rzd22DLO58GmUHUGTwgFrKyW5"></script>
<script type="text/javascript">
	// 百度地图API功能
	var map = new BMap.Map("allmap");
	map.setMapStyle({style:'normal'});
	//var point = new BMap.Point(114.329303,30.475501);
	//map.centerAndZoom(point,12);
	// 创建地址解析器实例
	var myGeo = new BMap.Geocoder();
	var markersArray = []; 
	// 将地址解析结果显示在地图上,并调整地图视野
	myGeo.getPoint("{{ $address }}", function(point){
	    if (point) {
	        map.centerAndZoom(point, 16);
	        map.addOverlay(new BMap.Marker(point));
	        //map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
	        //map.addControl(new BMap.ScaleControl());                    // 添加比例尺控件
	       // map.addControl(new BMap.OverviewMapControl());              //添加缩略地图控件
	        map.enableScrollWheelZoom();                            //启用滚轮放大缩小
	    }else{
	        //alert("您选择地址没有解析到结果!");
	    }
	});
  	map.addEventListener("click", showInfo);  


  	function showInfo(e){
	  	  // e.point.lng;  
	      // e.point.lat;  
		  	myGeo.getLocation(e.point, function (rs) {  
            var addComp = rs.addressComponents;  
            var address = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber;  
            if (confirm("确定选择地址是" + address + "?")) {  
                 javascript:window.parent.call_back_by_map(address,e.point.lng,e.point.lat);
            }  
        });  
	  	  addMarker(e.point);  
  	}

  	//地图上标注  
    function addMarker(point) {  
        var marker = new BMap.Marker(point);  
        markersArray.push(marker);  
        clearOverlays();  
        map.addOverlay(marker);  
    } 

       //清除标识  
    function clearOverlays() {  
        if (markersArray) {  
            for (i in markersArray) {  
                map.removeOverlay(markersArray[i])  
            }  
        }  
    }   

</script>
</html>