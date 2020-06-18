<?php
namespace GoneTone;

class ApiClient {
    private $host;
    private $serverPort;

    /**
     * @param $host
     * @param int $serverPort
     */
    public function __construct($host, $serverPort = 25565) {
        // Setup host and port of minecraft server
        $this->host = $host;
        $this->serverPort = $serverPort;
    }

    /**
     * @return array|mixed|null
     */
    public function pingCall() {
        require_once dirname(__FILE__) . '/minecraft_query/MinecraftPing.php';
        require_once dirname(__FILE__) . '/minecraft_query/MinecraftPingException.php';

        $InfoPing = false;
        $QueryPing = null;

        try {
            $QueryPing = new xPaw\MinecraftPing($this->host, $this->serverPort, 1);

            $InfoPing = $QueryPing->Query();

            if ($InfoPing === false) {
                $QueryPing->Close();
                $QueryPing->Connect();

                $InfoPing = $QueryPing->QueryOldPre17();
            }

            $version = explode(" ", $InfoPing['version']['name'], 2);

            // Server info
            $data['server'] = array(
        		'server_motd' => $InfoPing['description']['text'],
                'host' => $this->host,
                'port' => $this->serverPort,
                'players_max'    => $InfoPing['players']['max'],
                'players_online' => $InfoPing['players']['online'],
                'version' => array(
                    'version' => $version[1],
                    'software' => $version[0]
                )
            );

            // Players

            if (array_key_exists('sample', $InfoPing['players'])) {
                $players_list_raw = $InfoPing['players']['sample'];
            } else {
                $players_list_raw = "empty";
            }
            
            $data['players'] = array();

            if ($players_list_raw != "empty") {
                foreach ($players_list_raw as $value) {
                    array_push($data['players'], $value['name']);
                }
            }

            return $data;
        } catch(xPaw\MinecraftPingException $e) {
            //echo $e->getMessage();
            return null;
        }

        if ($QueryPing !== null) {
            $QueryPing->Close();
        }
    }
}
?>
