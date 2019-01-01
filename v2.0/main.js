
function update_points(data, status){
    var response = JSON.parse(data);
    $.each(response["values"],
        function(i, point) {
            $('#' + point["name"]).text(point["value"]);
        }
    );
}

function laserServer(request_parameter) {
    $.post("lib/laserServer.php", {
        random:  Math.random(),
        request: request_parameter
    },
        function(data, status) {
            update_points(data, status);
        }
    );
}

var json_request = '{ "values" : [' +
        '"GEN_C2_OUT",' +
        '"LAS_GMTE_DIODE",' +
        '"LAS_GMTE_CRYSTAL",' +
        '"LAS_GMTE_ELECTRONICSINK",' +
        '"LAS_GMTE_HEATSINK"' +
    '] }';

$(document).ready(
    function() {
        laserServer(json_request);
        $("button").click(
            function() {
                laserServer($(this).attr("req"));
            }
        );
    }
);
