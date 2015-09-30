<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-base-template"><br></div>
    <h2><?php  _e( "Weather cityes plugin page", 'weater-cityes-plugin' ); ?></h2>
    <form id="wc-plugin-find-city-form" action="<?php echo $_SERVER['PHP_SELF']?>?page=weater-cityes" method="POST">
        <?php $find_city = wp_create_nonce( 'find_city' );?>
        <input type="hidden" name="find_city_nonce" value="<?php echo $find_city ?>" />
        <input id="find-city" type="text" name="find_city" size="40" />
        <input type="submit" value="Find" />
    </form>
    <?php if(isset($_POST['find_city'])):?>
        <div id="shortcode"></div>
        <div id="map"></div>
        <?php $api_key = get_option( 'wc_setting', '' );?>
        <?php if(isset($api_key['wc_api_key'])):?>
        <script type="text/javascript">
            var map;
            function initMap() {
                var myLatLng = {lat: 0, lng: 0};
                var map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 1,
                  center: myLatLng
                });
                <?php foreach ($this->WC_Plugin->{results} as $key=>$item):?>
                    var marker<?php echo $key?> = new google.maps.Marker({
                        position: {
                            lat:<?php echo $item->{geometry}->{location}->{lat};?>,
                            lng:<?php echo $item->{geometry}->{location}->{lng};?>
                        },
                        map: map,
                        animation: google.maps.Animation.DROP,
                        title: '<?php  _e( "Shortcode:", 'weater-cityes-plugin' ); ?> [wc_shortcode coord="<?php echo $item->{geometry}->{location}->{lat};?>,<?php echo $item->{geometry}->{location}->{lng};?>"]'
                    }); 
                google.maps.event.addListener(marker<?php echo $key?>, 'click', function(event) {   
                    document.getElementById('shortcode').innerHTML = '[wc_shortcode coord="'+event.latLng.H+','+event.latLng.L+'"]';
                });
                <?php endforeach;?>  
                
            }
        </script>
        <script async defer
          src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key['wc_api_key']; ?>&callback=initMap">
        </script>
        <?php endif;?>
        <h3><?php  _e( "Results:", 'weater-cityes-plugin' ); ?></h3>
        <div class="find-city-results">
            <?php foreach ($this->WC_Plugin->{results} as $item):?>
                <p>
                    <?php echo $item->formatted_address?><br />
                    <?php  _e( "Shortcode:", 'weater-cityes-plugin' ); ?> [wc_shortcode coord="<?php echo $item->{geometry}->{location}->{lat};?>,<?php echo $item->{geometry}->{location}->{lng};?>"]
                </p>
            <?php endforeach;?>
        </div>
    <?php endif;?>
    <form id="dx-plugin-base-form" action="options.php" method="POST">
        <?php 
            settings_fields( 'wc_setting' );
            do_settings_sections( 'weater-cityes' );
            submit_button(); 
        ?>
    </form> <!-- end of #dxtemplate-form -->
</div>