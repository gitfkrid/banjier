<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
        <h1 class="m-0">Time Series Sensor Data Logger</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-temperature-high"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Temperature</span>
                <span class="info-box-number" id="hitTEMP"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-tint"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Humidity</span>
                <span class="info-box-number" id="hitHUM"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-lightbulb"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Light Itensity</span>
				<span class="info-box-number" id="hitLDR"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-low-vision"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Obstacle Distance</span>
                <span class="info-box-number" id="hitSR04"></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>		
        <!-- /.row -->
				
		<div class="row">
          <div class="col-md-12">
			<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> <strong>Status Koneksi Broker MQTT</strong></h5>
                <div id="messages"></div>
            </div>			
          </div>          
        </div>		

		<div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title"><i class="icon fas fa-chart-bar"></i> Temperature & Humidity Sensor DHT11</h5>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>					
                  </button>                  
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-6">
                    <p class="text-center">
                      <strong>Temperature Data Logger - Bar Chart</strong>
                    </p>
                    <div class="chart">                      
                      <canvas id="chartTEMP" height="180" style="height: 180px;"></canvas>					  
                    </div>                    
                  </div>
				  <div class="col-sm-6">
                    <p class="text-center">
                      <strong>Humidity Data Logger - Bar Chart</strong>
                    </p>
                    <div class="chart">                      
                      <canvas id="chartHUM" height="180" style="height: 180px;"></canvas>					  
                    </div>                    
                  </div>				  
                  <!-- /.col -->                  
                </div>
				
				<style>
					#chartdivtemp {
					  width: 100%;
					  height: 300px;
					  background-color: #FFFFFF;
					}
					
					#chartdivhumi {
					  width: 100%;
					  height: 300px;
					  background-color: #FFFFFF;
					}
				</style>
	
				<div class="row">
				  <div class="col-sm-6">
					<div class="card ">
					  <div class="card-header">
						<h3 class="card-title"><i class="fas fa-temperature-high"></i> Speed Gauge Temperature</h3>
					  </div>
					  <div class="card-body">
						<div id="chartdivtemp"></div>   
					  </div>
					</div>                                 
                  </div>				 				 
				  <div class="col-sm-6">
					<div class="card">
					  <div class="card-header">
						<h3 class="card-title"><i class="fas fa-tint"></i> Speed Gauge Humidity</h3>
					  </div>
					  <div class="card-body">
						<div id="chartdivhumi"></div>
					  </div>
					</div>
                  </div>
                </div>
              </div>
            </div>            
          </div>          
        </div>

		<div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title"><i class="fas fa-lightbulb"></i> Light Intensity Sensor LDR <i class="fas fa-low-vision"></i> Ultrasonic Distance Sensor HC-SR04</h5>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>                  
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-6">
                    <p class="text-center">
                      <strong>Light Intensity Data Logger</strong>
                    </p>
                    <div class="chart">                      
                      <canvas id="chartLDR" height="180" style="height: 180px;"></canvas>					  
                    </div>                    
				  </div>
				  <div class="col-sm-6">
                    <p class="text-center">
                      <strong>Obstacle Distance Data Logger</strong>
                    </p>

                    <div class="chart">                      
                      <canvas id="chartUltrasonic" height="180" style="height: 180px;"></canvas>					  
                    </div>                
                  </div>
                  <!-- /.col -->                  
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->              
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

		<div class="row">
		  <div class="col-md-12">
			<div class="card">
				<div class="card-header">
				  <h3 class="card-title"><i class="fas fa-gamepad"></i> Keypad - Remote IR</h3>
				  <div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
					  <i class="fas fa-minus"></i>
					</button>
					<button type="button" class="btn btn-tool" data-card-widget="remove">
					  <i class="fas fa-times"></i>
					</button>
				  </div>
				</div>
				<div class="card-body">	
					<span class="text-warning" id="kodekeypad"></span>
				</div>
			</div>
		  </div>
		</div>
		        
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-12">
            <!-- MAP & BOX PANE -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-magic"></i> Actuator Controller</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
			  
              <!-- /.card-header -->
              <div class="card-body">	
				<div class="row">				  
				  <div class="col-md-8">
					<div class="card ">
					  <div class="card-header">
						<h3 class="card-title">
						  <i class="fas fa-lightbulb"></i> Activating LED's
						</h3>
					  </div>
					  <div class="card-body">
						<input type="text" class="sliderLED" name="nLED" value="" 
						data-type="single"
						data-min="0"
						data-max="9"
						data-from="0"
						data-step="1"/>
					  </div>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="card ">
					  <div class="card-header">
						<h3 class="card-title">
						  <i class="fas fa-toggle-off"></i> Relay Switch
						</h3>
					  </div>
					  <div class="card-body">								
						<input type="checkbox" onclick="RelayONOFF(this)"> ON - OFF
					  </div>
					</div>
				  </div>
				</div>  
				<div class="row">				  
				  <div class="col-md-8">
					<div class="card ">
					  <div class="card-header">
						<h3 class="card-title">
						  <i class="fas fa-fan"></i> Speed FAN
						</h3>
					  </div>
					  <div class="card-body">
						<input type="text" class="sliderFAN" name="nFAN" value="" 
						data-type="single"
						data-min="0"
						data-max="100"
						data-from="0"
						data-step="5"
						data-grid="true"/>	
					  </div>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="card ">
					  <div class="card-header">
						<h3 class="card-title">
						  <i class="fas fa-volume-up"></i> Speaker Piezo
						</h3>
					  </div>
					  <div class="card-body">
						<input type="checkbox" onclick="PiezoONOFF(this)"> ON - OFF						
					  </div>
					</div>
				  </div>
				</div>  

              </div>	
            </div>
          </div>
          <!-- /.col -->
        </div>        
      </div>
    </section>
    <!-- /.content -->