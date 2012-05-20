webcam.set_api_url( 'ghip.com/backend/test.php' );
webcam.set_quality( 90 ); // JPEG quality (1 - 100)
webcam.set_shutter_sound( true ); // play shutter click sound
document.write( webcam.get_html(320, 240) );
