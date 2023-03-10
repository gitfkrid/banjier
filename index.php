<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>IoT Dashboard</title>
		<?php include 'css.php';?>
	</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-dark">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
			</li>
			</ul>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
			<!-- Navbar Search -->
			<li class="nav-item">
				<a class="nav-link" data-widget="navbar-search" href="#" role="button">
				<i class="fas fa-search"></i>
				</a>
				<div class="navbar-search-block">
				<form class="form-inline">
					<div class="input-group input-group-sm">
					<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
					<div class="input-group-append">
						<button class="btn btn-navbar" type="submit">
						<i class="fas fa-search"></i>
						</button>
						<button class="btn btn-navbar" type="button" data-widget="navbar-search">
						<i class="fas fa-times"></i>
						</button>
					</div>
					</div>
				</form>
				</div>
			</li>      
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="" class="brand-link">
			<img src="AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
			<span class="brand-text font-weight-light">Banjier</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
			<!-- Sidebar Menu -->
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="" class="nav-link active">
					<i class="nav-icon fas fa-chart-pie"></i>
					<p>Dashboard</p>
					</a>
				</li>                   
				</ul>
			</nav>
			<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<?php include 'dashboard.php';?>
		</div>
		<!-- /.content-wrapper -->
		
		<!-- Footer -->
		<?php include 'footer.php';?>
	</div>
	<!-- ./wrapper -->

<?php include 'js.php';?>

<script>
/*-----------------------------------------------------
	BAGIAN MQTT YANG TERKONEKSI DENGAN MESSAGE BROKER
  -----------------------------------------------------*/		
// Menentuan alamat IP dan PORT message broker
var host = "192.168.0.103";  
var port = 9001; 

// Konstruktor koneksi antara client dan message broker
var client = new Paho.MQTT.Client(host, port, "/ws",
            "myclientid_" + parseInt(Math.random() * 100, 10));		

// Menjalin koneksi antara client dan message broker
client.onConnectionLost = function (responseObject) {            
	document.getElementById("messages").innerHTML += "Koneksi Ke Broker MQTT Putus - " + responseObject.errorMessage + "<br/>";
};

// variabel global data sensor IoT Development Board
// website berposisi sebagai subscriber
var humi = 0;
var temp = 0;
var sr04 = 0;
var ldr = 0;
var keypad = "";

// Mendapatkan payload dari transimisi data IoT Development Board
// kemudian memilah dan melimpahkanya ke varibael berdasarkan TOPIC.
client.onMessageArrived = function (message) {		
	if (message.destinationName == "/ldr") {
		ldr = message.payloadString;
	} else if (message.destinationName == "/sr04") {
		sr04 = message.payloadString;
	} else if (message.destinationName == "/dht") {
		var dht = JSON.parse(message.payloadString);
		humi = dht.kelembaban;
		temp = dht.suhu;
	} else if (message.destinationName == "/remoteir") {
		keypad = message.payloadString;
	}
	
	document.getElementById("hitTEMP").innerHTML = temp + " ??C";
	document.getElementById("hitHUM").innerHTML = humi + " H";
	document.getElementById("hitLDR").innerHTML = ldr + " Lux";
	document.getElementById("hitSR04").innerHTML = sr04 + " cm";	
	document.getElementById("kodekeypad").innerHTML = keypad;	
};

// Option mqtt dengan mode subscribe dan qos diset 1
var options = {
    timeout: 3,
    keepAliveInterval: 30,
    onSuccess: function () {
		document.getElementById("messages").innerHTML += "Koneksi Ke Broker MQTT Sukses" + "<br/>";				
        client.subscribe("/dht", {qos: 1});
		client.subscribe("/ldr", {qos: 1});
		client.subscribe("/sr04", {qos: 1});
		client.subscribe("/remoteir", {qos: 1});
    },

    onFailure: function (message) {
		document.getElementById("messages").innerHTML += "Koneksi ke Broker MQTT Gagal - " + message.errorMessage + "<br/>";                
    },
			
	userName:"AdminMQTT",
	password:"pwd123"
};

if (location.protocol == "https:") {
    options.useSSL = true;
}
        
document.getElementById("messages").innerHTML += "Koneksi Ke Broker MQTT - Alamat: " + host + ":" + port + "<br/>";
client.connect(options);
</script>

<script>
/*------------------------------------------------------------
	BAGIAN CHART CANVAS
	https://nagix.github.io/chartjs-plugin-streaming/latest/
  ------------------------------------------------------------*/  
// Enumerasi tipe warna  
var chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

var color = Chart.helpers.color;
var saiki = new Date();
var dinoiki = saiki.toString();

/*--------------------------
	CHART TEMPERATUR DHT11
  --------------------------*/ 
// Update data sensor dht11
function onRefreshTEMP(chart) {
	chart.data.datasets[0].data.push({
		x: Date.now(),
		y: temp
	});
}

var configTEMP = {
	type: 'bar',
	data: {
		datasets: [			
		{
			label: 'Temperatur (??C)',
			backgroundColor: color(chartColors.red).alpha(0.6).rgbString(),
			borderColor: chartColors.red,
			borderWidth: 1,			
			data: []
		}]
	},
	
	options: {
		title: {
			display: true,
			text: dinoiki
		},

		scales: {
			xAxes: [{
				type: 'realtime',
				realtime: {
					duration: 10000,
					refresh: 1500,
					delay: 2000,
					onRefresh: onRefreshTEMP
				}
				}],
				
				yAxes: [{
					type: 'linear',
					display: true,
					scaleLabel: {
					display: true,
					labelString: 'value'
				}
			}]
		},

		tooltips: {
			mode: 'nearest',
			intersect: false
		},

		hover: {
			mode: 'nearest',
			intersect: false
		}			
	}
};

/*--------------------------
	CHART KELEMBABAN DHT11
  --------------------------*/ 
// Update data sensor dht11
function onRefreshHUM(chart) {
	chart.data.datasets[0].data.push({
		x: Date.now(),
		y: humi
	});
}

var configHUM = {
	type: 'bar',
	data: {
		datasets: [			
		{
			label: 'Kelembaban (H)',
			backgroundColor: color(chartColors.blue).alpha(0.6).rgbString(),
			borderColor: chartColors.blue,
			borderWidth: 1,			
			data: []
		}]
	},
	
	options: {
		title: {
			display: true,
			text: dinoiki
		},

		scales: {
			xAxes: [{
				type: 'realtime',
				realtime: {
					duration: 10000,
					refresh: 1500,
					delay: 2000,
					onRefresh: onRefreshHUM
				}
				}],
				
				yAxes: [{
					type: 'linear',
					display: true,
					scaleLabel: {
					display: true,
					labelString: 'value'
				}
			}]
		},

		tooltips: {
			mode: 'nearest',
			intersect: false
		},

		hover: {
			mode: 'nearest',
			intersect: false
		}			
	}
};


/*--------------------------------------
	CHART INTENSITAS CAHAYA SENSOR LDR
  --------------------------------------*/ 
// Update data sensor LDR
function onRefreshLDR(chart) {
	chart.data.datasets[0].data.push({
		x: Date.now(),
		y: ldr
	});
}

// Chart canvas & konfigurasi
// Mode line sensor LDR
var configLDR = {
	type: 'line',
	data: {
		datasets: [{
			label: 'Level Cahaya (Lux)',
			backgroundColor: color(chartColors.yellow).alpha(0.5).rgbString(),
			borderColor: chartColors.yellow,
			fill: false,
			lineTension: 0,
			borderDash: [8, 4],
			data: []
		}]
	},
	options: {
		title: {
			display: true,
			text: dinoiki
		},
		scales: {
			xAxes: [{
				type: 'realtime',
				realtime: {
					duration: 10000,
					refresh: 300,
					delay: 500,
					onRefresh: onRefreshLDR
				}
			}],
			yAxes: [{
				scaleLabel: {
					display: true,
					labelString: 'value'
				}
			}]
		},
		tooltips: {
			mode: 'nearest',
			intersect: false
		},
		hover: {
			mode: 'nearest',
			intersect: false
		}
	}
};

/*-----------------------------------------
	CHART JARAK SENSOR ULTRASONIC HC-SR04
  -----------------------------------------*/ 
// Update data sensor ultrasnic HC-SR04  
function onRefreshsr04(chart) {
	chart.data.datasets[0].data.push({
		x: Date.now(),
		y: sr04
	});
}

// Chart canvas & konfigurasi
// Mode line sensor HC-SR04  
var configSR04 = {
	type: 'line',
	data: {
		datasets: [{
			label: 'Jarak (cm)',
			backgroundColor: color(chartColors.purple).alpha(0.5).rgbString(),
			borderColor: chartColors.purple,
			fill: false,
			cubicInterpolationMode: 'monotone',
			data: []
		}]
	},
	options: {
		title: {
			display: true,
			text: dinoiki
		},
		scales: {
			xAxes: [{
				type: 'realtime',
				realtime: {
					duration: 10000,
					refresh: 500,
					delay: 2000,
					onRefresh: onRefreshsr04
				}
			}],
			yAxes: [{
				scaleLabel: {
					display: true,
					labelString: 'value'
				}
			}]
		},
		tooltips: {
			mode: 'nearest',
			intersect: false
		},
		hover: {
			mode: 'nearest',
			intersect: false
		}
	}
};

//Onload semua Chart
window.onload = function() {
	// onload chart temperatur sensor DHT11
	var ctxTEMP = document.getElementById('chartTEMP').getContext('2d');
	window.chartTEMP = new Chart(ctxTEMP, configTEMP);
	
	// onload chart kelembaban sensor DHT11
	var ctxHUM = document.getElementById('chartHUM').getContext('2d');
	window.chartHUM = new Chart(ctxHUM, configHUM);
	
	// onload chart intensitas cahaya sensor LDR
	var ctxLDR = document.getElementById('chartLDR').getContext('2d');
	window.chartLDR = new Chart(ctxLDR, configLDR);
	
	// onload chart jarak penghalang sensor Ultrasonic
	var ctxSR04 = document.getElementById('chartUltrasonic').getContext('2d');
	window.chartUltrasonic = new Chart(ctxSR04, configSR04);
};
</script>

<script>
/*----------------------------
	BAGIAN SPEED CHART DHT11
  ----------------------------*/
	am4core.ready(function() {
	
	// Themes begin
	am4core.useTheme(am4themes_dataviz);
	am4core.useTheme(am4themes_animated);
	// Themes end
	
	//------------------------------------------
	//                Temperature
	//------------------------------------------
	
	// create chart
	var charttemp = am4core.create("chartdivtemp", am4charts.GaugeChart);
	charttemp.innerRadius = am4core.percent(82);
	
	/**
	 * Normal axis
	 */
	
	var axistemp = charttemp.xAxes.push(new am4charts.ValueAxis());
	axistemp.min = 0;
	axistemp.max = 100;
	axistemp.strictMinMax = true;
	axistemp.renderer.radius = am4core.percent(80);
	axistemp.renderer.inside = true;
	axistemp.renderer.line.strokeOpacity = 1;
	axistemp.renderer.ticks.template.disabled = false
	axistemp.renderer.ticks.template.strokeOpacity = 1;
	axistemp.renderer.ticks.template.length = 10;
	axistemp.renderer.grid.template.disabled = true;
	axistemp.renderer.labels.template.radius = 40;
	axistemp.renderer.labels.template.adapter.add("text", function(text) {
	  return text + "??C";
	})
	
	/**
	 * Axis for ranges
	 */
	
	var colorSet = new am4core.ColorSet();
	
	var axis2temp = charttemp.xAxes.push(new am4charts.ValueAxis());
	axis2temp.min = 0;
	axis2temp.max = 100;
	axis2temp.strictMinMax = true;
	axis2temp.renderer.labels.template.disabled = true;
	axis2temp.renderer.ticks.template.disabled = true;
	axis2temp.renderer.grid.template.disabled = true;
	
	var range0temp = axis2temp.axisRanges.create();
	range0temp.value = 0;
	range0temp.endValue = 50;
	range0temp.axisFill.fillOpacity = 1;
	range0temp.axisFill.fill = colorSet.getIndex(0);
	
	var range1temp = axis2temp.axisRanges.create();
	range1temp.value = 50;
	range1temp.endValue = 100;
	range1temp.axisFill.fillOpacity = 1;
	range1temp.axisFill.fill = colorSet.getIndex(2);
	
	/**
	 * Label
	 */
	
	var labeltemp = charttemp.radarContainer.createChild(am4core.Label);
	labeltemp.isMeasured = false;
	labeltemp.fontSize = 45;
	labeltemp.x = am4core.percent(50);
	labeltemp.y = am4core.percent(100);
	labeltemp.horizontalCenter = "middle";
	labeltemp.verticalCenter = "bottom";
	labeltemp.text = "50%";
	
	
	/**
	 * Hand
	 */
	
	var handtemp = charttemp.hands.push(new am4charts.ClockHand());
	handtemp.axis = axis2temp;
	handtemp.innerRadius = am4core.percent(20);
	handtemp.startWidth = 10;
	handtemp.pin.disabled = true;
	handtemp.value = 50;
	
	handtemp.events.on("propertychanged", function(ev) {
	  range0temp.endValue = ev.target.value;
	  range1temp.value = ev.target.value;
	  labeltemp.text = axis2temp.positionToValue(handtemp.currentPosition).toFixed(1);
	  axis2temp.invalidate();
	});

	//------------------------------------------
	//                Humidity
	//------------------------------------------	

	// create chart
	var charthumi = am4core.create("chartdivhumi", am4charts.GaugeChart);
	charthumi.innerRadius = am4core.percent(82);
	
	/**
	 * Normal axis
	 */
	
	var axishumi = charthumi.xAxes.push(new am4charts.ValueAxis());
	axishumi.min = 0;
	axishumi.max = 100;
	axishumi.strictMinMax = true;
	axishumi.renderer.radius = am4core.percent(80);
	axishumi.renderer.inside = true;
	axishumi.renderer.line.strokeOpacity = 1;
	axishumi.renderer.ticks.template.disabled = false
	axishumi.renderer.ticks.template.strokeOpacity = 1;
	axishumi.renderer.ticks.template.length = 10;
	axishumi.renderer.grid.template.disabled = true;
	axishumi.renderer.labels.template.radius = 40;
	axishumi.renderer.labels.template.adapter.add("text", function(text) {
	  return text + "H";
	})
	
	/**
	 * Axis for ranges
	 */	
	
	var axis2humi = charthumi.xAxes.push(new am4charts.ValueAxis());
	axis2humi.min = 0;
	axis2humi.max = 100;
	axis2humi.strictMinMax = true;
	axis2humi.renderer.labels.template.disabled = true;
	axis2humi.renderer.ticks.template.disabled = true;
	axis2humi.renderer.grid.template.disabled = true;
	
	var range0humi = axis2humi.axisRanges.create();
	range0humi.value = 0;
	range0humi.endValue = 50;
	range0humi.axisFill.fillOpacity = 1;
	range0humi.axisFill.fill = colorSet.getIndex(0);
	
	var range1humi = axis2humi.axisRanges.create();
	range1humi.value = 50;
	range1humi.endValue = 100;
	range1humi.axisFill.fillOpacity = 1;
	range1humi.axisFill.fill = colorSet.getIndex(2);
	
	/**
	 * Label
	 */
	
	var labelhumi = charthumi.radarContainer.createChild(am4core.Label);
	labelhumi.isMeasured = false;
	labelhumi.fontSize = 45;
	labelhumi.x = am4core.percent(50);
	labelhumi.y = am4core.percent(100);
	labelhumi.horizontalCenter = "middle";
	labelhumi.verticalCenter = "bottom";
	labelhumi.text = "50%";
	
	
	/**
	 * Hand
	 */
	
	var handhumi = charthumi.hands.push(new am4charts.ClockHand());
	handhumi.axis = axis2humi;
	handhumi.innerRadius = am4core.percent(20);
	handhumi.startWidth = 10;
	handhumi.pin.disabled = true;
	handhumi.value = 50;
	
	handhumi.events.on("propertychanged", function(ev) {
	  range0humi.endValue = ev.target.value;
	  range1humi.value = ev.target.value;
	  labelhumi.text = axis2humi.positionToValue(handhumi.currentPosition).toFixed(1);
	  axis2humi.invalidate();
	});

	//------------------------------------------
	//             Animasi & Data
	//------------------------------------------
	setInterval(function() {	  
	  var valuetemp = Math.round(temp);
	  var valuehumi = Math.round(humi);

	  var animationtemp = new am4core.Animation(handtemp, {
		property: "value",
		to: valuetemp
	  }, 1000, am4core.ease.cubicOut).start();

	  var animationhumi = new am4core.Animation(handhumi, {
		property: "value",
		to: valuehumi
	  }, 1000, am4core.ease.cubicOut).start();

	}, 1500);
	
	}); 	
</script>

<script>
/*---------------------------
	BAGIAN KONTROL AKTUATOR
  ---------------------------*/
  
/*----------------------------------------
	MENGAKTIFKAN DAN MENONAKTIFKAN LED X9
  ----------------------------------------*/
$(".sliderLED").ionRangeSlider({
	onFinish: function (data) {			
		var valled = data.from;			
		var clientPub = new Paho.MQTT.Client(host, port, "/ws", "myclientidPub_" + parseInt(Math.random() * 100, 10));
			
		var optionsPub = {
			userName:"AdminMQTT",
			password:"pwd123",
			timeout: 3,
			keepAliveInterval: 30,
			onSuccess: function () {											
				ledanimPub = new Paho.MQTT.Message(valled.toString());
				ledanimPub.destinationName = "/ledanim";			
				clientPub.send(ledanimPub);				
				clientPub.disconnect();
			},				
		};									
		clientPub.connect(optionsPub);            		
    },
});

/*------------------------------------
	MENGATUR KECEPATAN PUTAR FAN-PWM
  ------------------------------------*/
$(".sliderFAN").ionRangeSlider({
	onFinish: function (data) {			
	var valfan = data.from;			
	var clientPub = new Paho.MQTT.Client(host, port, "/ws", "myclientidPub_" + parseInt(Math.random() * 100, 10));
			
	var optionsPub = {
		userName:"AdminMQTT",
		password:"pwd123",
		timeout: 3,
		keepAliveInterval: 30,
		onSuccess: function () {
			fanpwmPub = new Paho.MQTT.Message(valfan.toString());
			fanpwmPub.destinationName = "/fanpwm";
			clientPub.send(fanpwmPub);
			clientPub.disconnect();									
			},				
		};									
	clientPub.connect(optionsPub);            		
    },
});

/*-------------------
	RELAY ON / OFF
  -------------------*/
function RelayONOFF(checkbox)
{
	var statusRelay;
	if (checkbox.checked)
	{
		statusRelay = "ON";			
	} else {
		statusRelay = "OFF";			
	}		
		
	var clientPub = new Paho.MQTT.Client(host, port, "/ws", "myclientidPub_" + parseInt(Math.random() * 100, 10));
	var optionsPub = {
		userName:"AdminMQTT",
		password:"pwd123",
		timeout: 3,
		keepAliveInterval: 30,
		onSuccess: function () {								
			relayPub = new Paho.MQTT.Message(statusRelay);
			relayPub.destinationName = "/relay";
			clientPub.send(relayPub);		
			clientPub.disconnect();
		},				
	};									
	clientPub.connect(optionsPub);
}

/*------------------
	PIEZO ON / OFF
  ------------------*/
function PiezoONOFF(checkbox)
{
	var statusBuzz;
	if (checkbox.checked)
	{
		statusBuzz = "ON";
	} else {
		statusBuzz = "OFF";
	}		
		
	var clientPub = new Paho.MQTT.Client(host, port, "/ws", "myclientidPub_" + parseInt(Math.random() * 100, 10));
	var optionsPub = {
		userName:"AdminMQTT",
		password:"pwd123",
		timeout: 3,
		keepAliveInterval: 30,
		onSuccess: function () {								
			var buzzPub = new Paho.MQTT.Message(statusBuzz);
			buzzPub.destinationName = "/piezo";
			clientPub.send(buzzPub);		
			clientPub.disconnect();
		},				
	};									
	clientPub.connect(optionsPub);
}

</script>
</body>
</html>
