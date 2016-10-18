<?php

/**
 * Created by PhpStorm.
 * User: alexRdg
 * Date: 17/10/2016
 * Time: 13:20
 */
class Team
{
    public $config;
    public $req_prefs = array();
    public $_payload;

    /**
     * An object is instantiated with the payload of a request to a team resource.
     *
     * @param type $payload
     */
    public function __construct($payload) {
        $this->_payload = $payload;
        $config = get_option('token_id');

        $this->req_prefs['http']['method'] = 'GET';
        $this->req_prefs['http']['header'] = 'X-Auth-Token: ' . $config;
    }

    /**
     * Function returns all fixtures for the team for this season.
     *
     * @param string $venue
     * @return array of stdObjects representing fixtures
     */
    public function get_fixtures($venue = "", $time_frame = "") {
        $uri = $this->_payload->_links->fixtures->href . '/?venue=' . $venue . '&timeFrame=' . $time_frame;
        $response = file_get_contents($uri, false, stream_context_create($this->req_prefs));

        return json_decode($response);
    }

    /**
     * Function returns all players of the team
     *
     * @return array of fixture objects
     */
    public function get_players() {
        $uri = $this->_payload->_links->players->href;

        $response = file_get_contents($uri, false, stream_context_create($this->req_prefs));
        $response = json_decode($response);

        return $response->players;
    }
}