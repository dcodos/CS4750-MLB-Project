<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
$conn = new mysqli('stardock.cs.virginia.edu', 'cs4750rml5qe', 'guest', 'cs4750rml5qe');
if ($conn->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']');
}
?>


<html>
    <?php
    include_once 'sql.php';
    ?>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" crossorigin="anonymous">
        <meta charset="UTF-8">
        <title></title>
    </head>


    <body style='max-width: 1200px;margin-left: auto;margin-right: auto'>

        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $conn->query(getPlayer($id));
            $row = $result->fetch_assoc();
            $first_name = $row["first_name"];
            $last_name = $row["last_name"];
            $throws = $row["throws"];
            $bats = $row["bats"];
        }
        ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo $first_name . " " . $last_name; ?></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Batting Stats<span class="sr-only">(current)</span></a></li>
                        <li><a href="pitchers.php">Pitching Stats</a></li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="id">
                            <input type="text" class="form-control" placeholder="Search" name="q">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                    
                    <ul class="nav navbar-nav">
                        DC.ML
                    </ul>
                    
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        
        <?php 
        if (isset($_GET['q'])) : 
            if (strlen($_GET['q']) > 2):
            $result = $conn->query(searchPlayer($_GET['q']))?>
        <h2>Search Results:</h2>
        <ul class="list-group">
        <?php 
        while ($row = $result->fetch_assoc()):
            ?>
            <?php if ($row['position'] == 'P') : ?>
            <li class="list-group-item clickable-row" data-href="pitcher.php?id=<?php echo $row['player_id']; ?>"><?php echo $row['first_name'] . " " . $row['last_name']; ?></li>
            <?php else: ?>
            <li class="list-group-item clickable-row" data-href="player.php?id=<?php echo $row['player_id']; ?>"><?php echo $row['first_name'] . " " . $row['last_name']; ?></li>
            
            <?php endif; endwhile; ?>
        </ul>
        
        <?php endif; endif; ?>
        <br />

                                <?php
        echo "Bats: " . $bats . " ";
        echo "Throws: " . $throws;
        ?>
        <div class="row">
            <div class="col-md-2">
                <img class="img-responsive" src="<?php echo "http://mlb.mlb.com/mlb/images/players/head_shot/$id.jpg"?>" />
            </div>
            <div class="col-md-5">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Year</th>
                        <th>Number</th>
                        <th>Position</th>
                        <th>Team</th>
                        <th>Salary</th>
                    </tr>
                    <?php
                    $result = $conn->query(getInfo($id));
                    while ($row = $result->fetch_assoc()) :
                        ?>
                    <tr>
                            <td><?php echo $row['year'] ?></td>
                            <td><?php echo $row['num'] ?></td>
                            <td><?php echo $row['position'] ?></td>
                            <td><?php echo strtoupper($row['abbrev']) ?></td>
                            <td><?php echo $row['salary'] ?></td>
                        </tr>
                        <?php
                    endwhile;
                    ?>
                </table>
            </div>

            <div class="col-md-5">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Year</th>
                        <th>AVG</th>
                        <th>OBP</th>
                        <th>SLG</th>
                        <th>OPS</th>
                    </tr>
                    <?php
                    $result = $conn->query(getFraction($id));
                    while ($row = $result->fetch_assoc()) :
                        ?>
                    <tr>
                            <td><?php echo $row['year'] ?></td>
                            <td><?php echo $row['average'] ?></td>
                            <td><?php echo $row['obp'] ?></td>
                            <td><?php echo $row['slg'] ?></td>
                            <td><?php echo $row['ops'] ?></td>
                        </tr>
                        <?php
                    endwhile;
                    ?>
                </table>
            </div> 
        </div>
        <br>
        
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Year</th>
                        <th>Hits</th>
                        <th>2B</th>
                        <th>3B</th>
                        <th>HR</th>
                        <th>Runs</th>
                        <th>RBI</th>
                        <th>BB</th>
                        <th>HBP</th>
                        <th>SB</th>
                        <th>CS</th>
                    </tr>
                    <?php
                    $result = $conn->query(getStats($id));
                    while ($row = $result->fetch_assoc()) :
                        ?>
                        <tr>
                            <td><?php echo $row['year'] ?></td>
                            <td><?php echo $row['hits'] ?></td>
                            <td><?php echo $row['doubles'] ?></td>
                            <td><?php echo $row['triples'] ?></td>
                            <td><?php echo $row['hr'] ?></td>
                            <td><?php echo $row['runs'] ?></td>
                            <td><?php echo $row['rbi'] ?></td>
                            <td><?php echo $row['bb'] ?></td>
                            <td><?php echo $row['hbp'] ?></td>
                            <td><?php echo $row['sb'] ?></td>
                            <td><?php echo $row['cs'] ?></td>
                        </tr>
                        <?php
                    endwhile; 
                    ?>
                </table>
            </div>
        </div>



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="js/script.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" crossorigin="anonymous"></script>
    </body>
</html>
