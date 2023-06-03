


<html>


    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <!-- main content -->
        <div id='main'>

            <div id='home' class='forthary'>

            </div>
            <div id='header' class='thirdary'>

            </div>


            <div id='sidebar' class='secondary'>
                <?php print file_get_contents('./sidebar.php')?>

            </div>

            <div id='content' class='primary'>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
                </script>


            <center>

                <?php


                    $link = mysqli_connect("url", "username", "password", "database");


                    $getDates = $link -> query('SELECT DISTINCT Date FROM HeartRateData');


                    $content = "[\n";

                    $getArray = [];
                    if (!$link->error ) {
                        while ($row = $getDates->fetch_assoc())
                        {
                            $content .= "    " . json_encode($row) . ",\n";



                            $getArray[] = $row['Date'];
                        }
                        if($getDates->num_rows > 0)
                        {
                            $content = substr($content,0,-2) . "\n]";
                        }
                        else
                        {
                            $content = substr($content,0,-1) . "]";
                        }
                        //echo '<script>console.log(\"{"code":"200","message":"Succesfully Collected Data, "content" : "' . $content. '"}'. '\")'. '</script>';
                      }


                    $get_day = date("Y-m-d");
                    if(isset($_GET["get_date"]))
                    {
                        $get_day = $_GET["get_date"];
                    }

                    echo '<script>console.log(' . $content . ')'. '</script>';
                    echo '
                    <form>
                        <select id = "day" onchange="switchDays()">
                    ';





                    foreach($getArray as $dte) {
                        if($dte == $get_day)
                        {
                            echo '<option value="' . $dte . '" selected="selected">' . $dte . '</option>';
                        }
                        else
                        {
                            echo '<option value="' . $dte . '">' . $dte . '</option>';
                        }

                    }


                    echo '
                        </select>
                    </form>
                    ';
                ?>
            </center>

                <canvas id="hrVsTime" style="width:100%;"></canvas>

                <?php

                    $get_day = date("Y-m-d");
                    if(isset($_GET["get_date"]))
                    {
                        $get_day = $_GET["get_date"];
                    }


                    $queryString = "SELECT * FROM HeartRateData WHERE Date = '" . $get_day . "'";
                    



                    if (mysqli_connect_errno()) {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        exit();
                    }

                     $result = $link -> query($queryString);

                    $content = "[\n";

                    if (!$link->error ) {
                        while ($row = $result->fetch_assoc())
                        {
                            $content .= "    " . json_encode($row) . ",\n";
                        }
                        if($result->num_rows > 0)
                        {
                            $content = substr($content,0,-2) . "\n]";
                        }
                        else
                        {
                            $content = substr($content,0,-1) . "]";
                        }
                        //echo '<script>console.log(\"{"code":"200","message":"Succesfully Collected Data, "content" : "' . $content. '"}'. '\")'. '</script>';
                      } 
                    else {
                        $err_message =  '{"code":"500","message":"SQL Error, ' . $link->error . ',"query:"' . $queryString.'"}';
                        echo '<script>console.log(' . $err_message . ')'. '</script>';
                    }
                    echo '<script>let query = '. $content . '</script>';
                ?> 


                <script>

                function switchDays() {
                    let selection = document.getElementById("day");
                    let text = selection.options[selection.selectedIndex].text;
                    window.location.href = window.location.origin + window.location.pathname + '?get_date=' +  text
                }




                    // let xValues = [];

                    let valueObj = {};

                    query.forEach
                    (
                        item =>
                        {
                            let dateObj = item.Date.split('-');
                            let timeObj = item.Time.split(':');

                            let x = new Date(dateObj[0], dateObj[1] - 1, dateObj[2], timeObj[0], timeObj[1], timeObj[2], 0);


                            x = x.toLocaleDateString("en-AU") + ' ' + x.toTimeString().slice(0,9);

                            //x = x.toTimeString().slice(0,9);

                            if(valueObj[x])
                            {
                                valueObj[x].push(item.Value);
                            }
                            else
                            {
                                valueObj[x] = [item.Value];
                            }
                            
                           
                        }
                    );

                    let xValues = [];
                    let yValues = [];

                    //valueMap = new Map([...valueMap].sort((a, b) => String(a[0]).localeCompare(b[0])));

                    console.log('start');


                    

                    


                    let xKeys = Object.keys(valueObj).sort(
                        function(a,b){
                            return new Date(b.date) - new Date(a.date);
                        }

                    );
                    console.log(xKeys);
                    xKeys.forEach
                    (
                        x =>
                        {

                            xPair = valueObj[x];

                            xPair.forEach
                            (
                                y => 
                                {
                                    xValues.push(x.split(' ')[1]);
                                    yValues.push(y);
                                }
                            );
                            

                        }
                    );


                    const DATA_COUNT = yValues.length;
                    const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};
                    new Chart("hrVsTime", {
                    type: "line",
                    data: {
                        labels: xValues,
                        datasets: [{
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(0,0,255,1.0)",
                            borderColor: "rgba(0,0,255,0.1)",
                            data: yValues
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Heart Rate'
                        },
                        legend: {display: false},
                        scales: {
                            yValues:
                            {
                                min: -100,
                                max: 100
                            }
                        }
                    }
                    });
                </script>
            </div>


            <div id='footer' class='secondary'>

            </div>


            
        </div>

    </body>






</html> 




