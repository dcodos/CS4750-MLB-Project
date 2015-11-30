<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$year_query = "select DISTINCT year from player_team order by year asc";

function getAvg($year) {
    $avg_query = "SELECT p.player_id, p.first_name, 
    p.last_name, 
    bs.hits / bs.ab as avg, 
    t.abbrev as 'team' 
    FROM batting_stats bs
    INNER JOIN players p on p.player_id = bs.player_id
    INNER JOIN player_team pt on pt.player_id = p.player_id
    INNER JOIN teams t on t.team_id = pt.team_id
    WHERE bs.year = $year
    AND pt.year = $year
    AND bs.ab > 40
    AND t.abbrev != 'nas'
    AND t.abbrev != 'aas'
    ORDER BY avg DESC
    LIMIT 10";
    return $avg_query;
}

function getOps($year) {
    $ops_query = "SELECT p.player_id, p.first_name, p.last_name, t.abbrev AS 'team', o.ops FROM ops o
        INNER JOIN batting_stats bs ON o.player_id = bs.player_id
        INNER JOIN players p ON o.player_id = p.player_id
        INNER JOIN player_team pt ON pt.player_id = p.player_id
        INNER JOIN teams t ON t.team_id = pt.team_id
        WHERE o.year = bs.year
        AND pt.year = o.year
        AND o.year = $year
        AND t.abbrev != 'nas'
        AND t.abbrev != 'aas'
        AND bs.ab > 40
        ORDER BY ops DESC
        LIMIT 10";
    return $ops_query;
}

function searchPlayer($query) {
    $search_query = "SELECT p.player_id, p.first_name, p.last_name, t.abbrev AS 'team'
        FROM players p
        INNER JOIN player_team pt on pt.player_id = p.player_id
        INNER JOIN teams t ON t.team_id = pt.team_id
        WHERE p.first_name LIKE '%" . $query . "%'
            OR p.last_name LIKE '%" . $query . "%'
            GROUP BY p.player_id, p.first_name, p.last_name";
    return $search_query;
}

function getPlayer($id) {
    $player_query = "SELECT * from players p
        WHERE p.player_id = $id
        LIMIT 1";
    return $player_query;
}

function getInfo($id) {
    $info_query = "SELECT pt.year, pt.num, pt.position, t.abbrev, s.salary
        FROM player_team pt 
        INNER JOIN players p ON p.player_id = pt.player_id
        LEFT OUTER JOIN salaries s ON s.player_id = pt.player_id AND s.year = pt.year
        INNER JOIN teams t on pt.team_id = t.team_id
        WHERE p.player_id = $id
        GROUP BY p.player_id, pt.year";
    return $info_query;
}

function getFraction($id) {
    $fraction_query = "SELECT pt.year, hits / ab AS average, (hits+bb+hbp)/(ab+bb+hbp+sf) AS obp, (hits+doubles+2*triples+3*hr)/ab AS slg, ops
        FROM player_team pt
        INNER JOIN batting_stats bs on pt.player_id = bs.player_id and pt.year = bs.year
        INNER JOIN ops o on pt.player_id = o.player_id and pt.year = o.year
        INNER JOIN sac_flys s on pt.player_id = s.batter_id and pt.year = s.year
        WHERE pt.player_id = $id
        GROUP BY pt.player_id, pt.year";
    return $fraction_query;
}

function getStats($id) {
    $stats_query = "SELECT *
        FROM player_team pt
        INNER JOIN batting_stats bs on pt.player_id = bs.player_id and pt.year = bs.year
        WHERE pt.player_id = $id
        GROUP BY pt.player_id, pt.year";
    return $stats_query;

}