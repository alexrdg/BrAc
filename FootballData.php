<?php

/**
 * Created by PhpStorm.
 * User: alexRdg
 * Date: 17/10/2016
 * Time: 13:25
 */
require_once 'Team.php';
require_once 'SoccerSeason.php';
class FootballData
{

    public $config;
    public $base_uri;
    public $req_prefs = array();

    public function __construct() {
        $this->config = get_option('token_id');

        // some lame hint for the impatient
        if($this->config == 'YOUR_AUTH_TOKEN' || !isset($this->config)) {
            exit('Get your API-Key first and edit config.ini');
        }

        $this->base_uri = 'http://api.football-data.org/v1/';

        $this->req_prefs['http']['method'] = 'GET';
        $this->req_prefs['http']['header'] = 'X-Auth-Token: ' . $this->config;
    }

    /**
     * Function returns a specific soccer season identified by an id.
     *
     * @param Integer $id
     * @return \Soccerseason object
     */
    public function get_soccerseason_by_id($id) {
        $resource = 'soccerseasons/' . $id;
        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));
        $result = json_decode($response);

        return new Soccerseason($result);
    }

    /**
     * Function returns all soccer seasons
     *
     * @param Integer $id
     * @return \Soccerseason object
     */
    public function get_soccer_season() {
        $resource = 'soccerseasons/';
        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));
        $result = json_decode($response);

        return new Soccerseason($result);
    }

    /**
     * Function returns all available fixtures for a given date range.
     *
     * @param DateString 'Y-m-d' $start
     * @param DateString 'Y-m-d' $end
     * @return array of fixture objects
     */
    public function get_fixtures_for_date_range($start, $end) {
        $resource = 'fixtures/?timeFrameStart=' . $start . '&timeFrameEnd=' . $end;

        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));

        return json_decode($response);
    }
    /**
     * Function returns all available fixtures for a given date range.
     *
     * @param DateString 'Y-m-d' $start
     * @param DateString 'Y-m-d' $end
     * @return array of fixture objects
     */
    public function get_fixtures_for_league_and_match($match_day) {
        $resource = '/competitions/'.get_option('league_id').'/fixtures?matchday='.$match_day;

        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));

        return json_decode($response);
    }

    /**
     * Function returns one unique fixture identified by a given id.
     *
     * @param int $id
     * @return stdObject fixture
     */
    public function get_fixture_by_id($id) {
        $resource = 'fixtures/' . $id;
        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));

        return json_decode($response);
    }

    /**
     * Function returns one unique team identified by a given id.
     *
     * @param int $id
     * @return stdObject team
     */
    public function get_team_by_id($id) {
        $resource = 'teams/' . $id;
        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));

        $result = json_decode($response);

        return new Team($result);
    }

    /**
     * Function returns all teams matching a given keyword.
     *
     * @param string $keyword
     * @return list of team objects
     */
    public function search_team($keyword) {
        $resource = 'teams/?name=' . $keyword;
        $response = file_get_contents($this->base_uri . $resource, false,
            stream_context_create($this->req_prefs));

        return json_decode($response);
    }
}