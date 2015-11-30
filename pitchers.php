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
                    <a class="navbar-brand" href="#">CS 4750 Baseball Database</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Batting Stats<span class="sr-only">(current)</span></a></li>
                        <li class="active"><a href="#">Pitching Stats</a></li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search" name="q">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Select Year <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php
                                $result = $conn->query($year_query);
                                while ($row = $result->fetch_assoc()) :
                                    $year = $row['year'];
                                    ?>
                                    <li><a href=pitchers.php?year="<?php echo $row['year']; ?>"><?php echo $row['year']; ?></a></li>

                                    <?php
                                endwhile;
                                if (isset($_GET['year'])) {
                                    $year = $_GET['year'];
                                }
                                
                                ?>
                            </ul>
                        </li>
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

        <h1>Batting Leaders</h1>
        <h4>Click any player to view more information</h4>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Player Name</th>
                        <th>Team</th>
                        <th>ERA</th>
                    </tr>
                    <?php
                    $result = $conn->query(getEra($year));
                    while ($row = $result->fetch_assoc()) :
                        ?>
                        <tr class="clickable-row" data-href="pitcher.php?id=<?php echo $row['player_id']; ?>">
                            <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td><?php echo $row['team']; ?></td>
                            <td><?php echo $row['era']; ?></td>
                        </tr>
                        <?php
                    endwhile;
                    ?>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-striped">
                    <tr>
                        <th>Player Name</th>
                        <th>Team</th>
                        <th>Wins</th>
                        <th>Losses</th>
                    </tr>
                    <?php
                    $result = $conn->query(getRecord($year));
                    while ($row = $result->fetch_assoc()) :
                        ?>
                        <tr class="clickable-row" data-href="pitcher.php?id=<?php echo $row['player_id']; ?>">
                            <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td><?php echo $row['team']; ?></td>
                            <td><?php echo $row['wins']; ?></td>
                            <td><?php echo $row['losses']; ?></td>
                        </tr>
                        <?php
                    endwhile;
                    ?>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="js/script.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" crossorigin="anonymous"></script>
    </body>
</html>
