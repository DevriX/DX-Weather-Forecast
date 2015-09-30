<?php ob_start() ?>
<div class = "weather">
<h3><?php _e('City of', 'weater-cityes-plugin' )?> <?php echo $weather->name?>, <?php echo $weather->{sys}->{country}?></h3>
<h2> <img src="http://openweathermap.org/img/w/<?php echo $weather->{weather}[0]->{icon}?>.png"> 
    <?php echo ($display_kelvins['wc_opt_in'] === 'on')? 
    $weather->{main}->{temp}.'K ( '.($weather->{main}->{temp} - 274.15). ' °C)':
    ($weather->{main}->{temp} - 274.15). '°C';?>
</h2>
<?php echo $weather->{weather}[0]->{main}?>
    <table class = "table table-striped table-bordered table-condensed">
        <tbody>
            <tr>
                <td><?php _e('Wind', 'weater-cityes-plugin' )?></td>
                <td><?php echo $weather->{wind}->{speed}?> <?php _e('m/s', 'weater-cityes-plugin' )?> <br>
                    <?php echo (isset($weather->{wind}->{deg}))?$weather->{wind}->{deg}.'°':''?>
                </td>
            </tr>
            <tr>
                <td><?php _e('Pressure', 'weater-cityes-plugin' )?><br></td>
                <td><?php echo $weather->{main}->{pressure}?> <?php _e('hpa', 'weater-cityes-plugin' )?></td>
            </tr>
            <tr>
                <td><?php _e('Humidity', 'weater-cityes-plugin' )?></td>
                <td><?php echo $weather->{main}->{humidity}?> %</td>
            </tr>
            <tr>
                <td><?php _e('Sunrise', 'weater-cityes-plugin' )?></td>
                <td id = "sunrise"><?php echo date('H:i',$weather->{sys}->{sunrise})?></td>
            </tr>
            <tr>
                <td><?php _e('Sunset', 'weater-cityes-plugin' )?></td>
                <td id = "sunset"><?php echo date('H:i',$weather->{sys}->{sunset})?></td>
            </tr>
            <tr>
                <td><?php _e('Geo coords', 'weater-cityes-plugin' )?></td>
                <td id = "coord">
                    <a href = "http://openweathermap.org/Maps?zoom=12&lat=<?php echo $weather->{coord}->{lat}?>&lon=<?php echo $weather->{coord}->{lon}?>&layers=B0FTTFF," target="_blank">[<?php echo $weather->{coord}->{lon}?>,<?php echo $weather->{coord}->{lat}?>]</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>