<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#C2_OFF").click(function(){
                    $.get("laserHandler.php?request=GEN+C2%3AOUTP+OFF");
                    $.get("laserHandler.php?request=GEN+C2%3AOUTP%3F",function(data,status){
                        location.reload();
                    });
                });
                $("#C2_ON").click(function(){
                    $.get("laserHandler.php?request=GEN+C2%3AOUTP+ON");
                    $.get("laserHandler.php?request=GEN+C2%3AOUTP%3F",function(data,status){
                        location.reload();
                    });
                });
            });
        </script>
    </head>
    <body>
        <button id="C2_OFF">C2 OFF</button>
        <button id="C2_ON">C2 ON</button>
        <br>
        <?php
            function get_query($point) {
                return "SELECT `name`, `value` FROM `current` WHERE `name` = '${point}';";
            }
            $con = mysqli_connect("localhost", "henlablaser", "inJub6bMZeXQhdUp", "henlablaser");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            $query = get_query("GEN_C2_OUT");
            $result = $con->query($query);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "Name: ".$row["name"]." Value: ".$row["value"]."<br>";
                }
            } else {
                echo "Empty";
            }
            $conn->close();
        ?>
    </body>
</html>