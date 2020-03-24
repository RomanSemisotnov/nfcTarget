<!DOCTYPE html>
<html>
<body>

<p>Click the button to get your coordinates.</p>

<button onclick="getLocation()">Try It</button>

<p id="demo"></p>

<script>
    window.onload = function(e){

        navigator.geolocation.watchPosition(showPosition);


        function showPosition(position) {
            console.log(position.coords.latitude);
            console.log(position.coords.longitude);
        }

    }
</script>

</body>
</html>