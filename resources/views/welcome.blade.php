<!DOCTYPE html>
<html>
<body>
Идет переадресация

<p id="demo"></p>

<script>
    window.onload = function(e){

        navigator.geolocation.watchPosition(showPosition);

        function showPosition(position) {
            console.log(position.coords.latitude);
            console.log(position.coords.longitude);



            var xhr = new XMLHttpRequest();

            var params = 'first=' + encodeURIComponent(position.coords.latitude) +
                '&second=' + encodeURIComponent(position.coords.longitude);

            xhr.open("GET", '/api/coords?' + params, true);
            

            xhr.send();
        }

    }
</script>

</body>
</html>