<?php ob_start() ?>
<div class = "weather">
<h3><?php _e('City of', 'DX-Weather-Forecast' )?> <?php echo $weather->name?>, <?php echo $weather->{sys}->{country}?></h3>
<h2> <img src="http://openweathermap.org/img/w/<?php echo $weather->{weather}[0]->{icon}?>.png"> 
    <?php echo ( $display_kelvins['wf_opt_in'] === 'on' )? 
    $weather->{main}->{temp}.'K ( '.( $weather->{main}->{temp} - 274.15 ). ' °C)':
    ( $weather->{main}->{temp} - 274.15 ). '°C';?>
</h2>
<?php echo $weather->{weather}[0]->{main}?>
    <table class = "table table-striped table-bordered table-condensed">
        <tbody>
            <tr>
                <td><?php _e( 'Wind', 'DX-Weather-Forecast' )?></td>
                <td><?php echo $weather->{wind}->{speed}?> <?php _e( 'm/s', 'DX-Weather-Forecast' )?> <br>
                    <?php echo (isset($weather->{wind}->{deg}))?$weather->{wind}->{deg}.'°':''?>
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Pressure', 'DX-Weather-Forecast' )?><br></td>
                <td><?php echo $weather->{main}->{pressure}?> <?php _e( 'hpa', 'DX-Weather-Forecast' )?></td>
            </tr>
            <tr>
                <td><?php _e( 'Humidity', 'DX-Weather-Forecast' )?></td>
                <td><?php echo $weather->{main}->{humidity}?> %</td>
            </tr>
            <tr>
                <td><?php _e( 'Sunrise', 'DX-Weather-Forecast' )?></td>
                <td id = "sunrise"><?php echo date('H:i',$weather->{sys}->{sunrise})?></td>
            </tr>
            <tr>
                <td><?php _e( 'Sunset', 'DX-Weather-Forecast' )?></td>
                <td id = "sunset"><?php echo date('H:i',$weather->{sys}->{sunset})?></td>
            </tr>
            <tr>
                <td><?php _e( 'Geo coords', 'DX-Weather-Forecast' )?></td>
                <td id = "coord">
                    <a href = "http://openweathermap.org/Maps?zoom=12&lat=<?php echo $weather->{coord}->{lat}?>&lon=<?php echo $weather->{coord}->{lon}?>&layers=B0FTTFF," target="_blank">[<?php echo $weather->{coord}->{lon}?>,<?php echo $weather->{coord}->{lat}?>]</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>