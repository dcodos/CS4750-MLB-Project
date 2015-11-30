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