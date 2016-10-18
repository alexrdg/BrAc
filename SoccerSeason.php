<?php

/**
 * Created by PhpStorm.
 * User: alexRdg
 * Date: 17/10/2016
 * Time: 13:22
 */
require_once 'FootballData.php';
class SoccerSeason
{
    public $config;
    public $req_prefs = array();
    public $payload;

    /**
     * The object gets instantiated with the payload of a request to a specific
     * soccerseason resource.
     *
     * @param type $payload
     */
    public function __construct($payload) {
        $this->payload = $payload;
        $config = get_option('token_id');

        $this->req_prefs['http']['method'] = 'GET';
        $this->req_prefs['http']['header'] = 'X-Auth-Token: ' . $config;
    }

    /**
     * Function returns all fixtures for the instantiated soccerseason.
     *
     * @return array of fixture objects
     */
    public function get_all_fixtures() {
        $uri = $this->payload->_links->fixtures->href;
        $response = file_get_contents($uri, false, stream_context_create($this->req_prefs));

        return json_decode($response);
    }

    /**
     * Function returns all fixtures for a given matchday.
     *
     * @param type $matchday
     * @return array of fixture objects
     */
    public function get_fixtures_by_matchday($matchday = 1) {
        $uri = $this->payload->_links->fixtures->href . '/?matchday=' . $matchday;

        $response = file_get_contents($uri, false, stream_context_create($this->req_prefs));
        $response = json_decode($response);

        return $response->fixtures;
    }

    /**
     * Function returns all teams participating in the instantiated soccerseason.
     *
     * @return array of team objects
     */
    public function get_teams() {
        $uri = $this->payload->_links->teams->href;
        $response = file_get_contents($uri, false, stream_context_create($this->req_prefs));
        $response = json_decode($response);

        return $response->teams;
    }

    /**
     * Function returns the current league table for the instantiated soccerseason.
     *
     * @return object leagueTable
     */
    public function get_league_table() {
        $uri = $this->payload->_links->leagueTable->href;
        $response = file_get_contents($uri, false, stream_context_create($this->req_prefs));

        return json_decode($response);
    }
}