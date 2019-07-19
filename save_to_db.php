<?php
/**
 * Created by PhpStorm.
 * User: yennguyen
 * Date: 18.07.2019
 * Time: 19:05
 */
class pdo_insert
{
    public $connection;
    public $data_to_import;

    public function __construct($data_to_import)
    {
        $this->data_to_import = $data_to_import;
		
$host = "127.0.0.1";
$dbname = "Probe_DB";
$user = "probearbeit_db";
$password = "probearbeit";
		
		
		
        $this->connection = new PDO("mysql:host=".$host.";dbname=".$dbname, $user, $password);
        if (!$this->connection)
        {
            echo "Failed to connect to MySQL.";
        }
    }
    public function insert() {
        $this->insert_datensatz();
    }
    function insert_datensatz() {
        $sql_query = "INSERT INTO museum (
                            landoriginal,
                            landdeutsch,
                            ortoriginal,
                            ortdeutsch,
                            nameoriginal,
                            namedeutsch,
                            emyajahr
                        ) VALUES (
                          ?,
                          ?, 
                          ?,
                          ?,
                          ?,
                          ?,
                          ?
                        )";
        $statement1 = $this->connection->prepare($sql_query);
        foreach($this->data_to_import as $key_d=>$value) {
//            var_dump($this->data_to_import);
////            var_dump($value);
//            die;
            if(is_array($value['emyajahr'])) {
                foreach($value['emyajahr'] as $k=>$v) {
					if(empty($value['emyajahr1'])) {
						$value['emyajahr1'] = $v;
					} else {
						$value['emyajahr1'] .= '; '.$v;
					}
                    
                }
            } else {
                $value['emyajahr1'] = $value['emyajahr'];

            }
//            var_dump($this->data_to_import[1]["landoriginal"]);
//            var_dump($value['landoriginal']);
//            die;
            //$value['landoriginal'] = utf8_decode($value['landoriginal']);
            if(is_array($value['landoriginal'])) {
                $value['landoriginal'] = json_decode($value['landoriginal']);
            }
            //$value['landdeutsch'] = utf8_decode($value['landdeutsch']);
            if(is_array($value['landdeutsch'])) {
                $value['landdeutsch'] = json_encode($value['landdeutsch']);
            }
            //$value['ortoriginal'] = utf8_decode($value['ortoriginal']);
            if(is_array($value['ortoriginal'])) {
                $value['ortoriginal'] = json_encode($value['ortoriginal']);
            }
            //$value['ortdeutsch'] = utf8_decode($value['ortdeutsch']);
            if(is_array($value['ortdeutsch'])) {
                $value['ortdeutsch'] = json_encode($value['ortdeutsch']);
            }
            if(is_array($value['nameoriginal'])) {
                $value['nameoriginal'] = utf8_decode($value['nameoriginal'][0]);
            } else {
                //$value['nameoriginal'] = utf8_decode($value['nameoriginal']);

            }
            if(is_array($value['nameoriginal'])) {
                $value['nameoriginal'] = json_encode($value['nameoriginal']);
            }
            if(is_array($value['namedeutsch'])) {
                $value['namedeutsch'] = json_encode($value['namedeutsch']);
            } else {
                //$value['namedeutsch'] = utf8_decode($value['namedeutsch']);

            }

            $value['landoriginal'] = trim($value['landoriginal'], '"');
            $value['landdeutsch'] = trim($value['landdeutsch'], '"');
            $value['ortoriginal'] = trim($value['ortoriginal'], '"');
            $value['ortdeutsch'] = trim($value['ortdeutsch'], '"' );
            $value['nameoriginal'] = trim($value['nameoriginal'], '"');
            $value['namedeutsch'] = trim($value['namedeutsch'], '"');
            $value['emyajahr'] = trim($value['emyajahr1'], '"');
//            var_dump($value);die;


            $museum_value['landoriginal'] = $value['landoriginal'];
            $museum_value['landdeutsch'] = $value['landdeutsch'];
            $museum_value['ortoriginal'] = $value['ortoriginal'];
            $museum_value['ortdeutsch'] = $value['ortdeutsch'];
            $museum_value['nameoriginal'] = $value['nameoriginal'];
            $museum_value['namedeutsch'] = $value['namedeutsch'];
            $museum_value['emyajahr'] = $value['emyajahr'];
//            var_dump(count($museum_value));
            $statement1->execute(
                array(
                    $museum_value['landoriginal'],
                    $museum_value['landdeutsch'],
                    $museum_value['ortoriginal'],
                    $museum_value['ortdeutsch'],
                    $museum_value['nameoriginal'],
                    $museum_value['namedeutsch'],
                    $museum_value['emyajahr']
                )
            );
            $museum_value = [];


            $last_id = $this->connection->lastInsertId();
//            var_dump($this->data_to_import);
//            var_dump($value);
//            die;
            if(isset($value['werbung']) && !empty($value['werbung'])) {
                if(is_array($value['werbung'])) {
                    foreach($value['werbung'] as $kv => $vv) {
                        $ad_value = [];
                        $ad_value['museum_id'] = $last_id;
                        $index = 1;
                        foreach($vv as $vvk => $vvv) {
//                            var_dump($kv);
//                            var_dump($index);
                            $ad_value['advertisement_type'] = $kv."_".$index;
                            //$ad_value['advertisement_content'] = utf8_decode($vvv);
							$ad_value['advertisement_content'] = $vvv;
							
                            $sql_query = "INSERT INTO advertisement (
                                        museum_id,
                                        advertisement_type,
                                        advertisement_content
                                    ) VALUES (
                                      ?,
                                      ?, 
                                      ?
                                    )";
                            $statement2 = $this->connection->prepare($sql_query);
//                            var_dump($ad_value['museum_id']);die;
                            $ad_value['museum_id'] = $last_id;
//                            var_dump($ad_value);
                            if(isset($ad_value['museum_id'])) {
                                $statement2->execute(
                                    array(
                                        $ad_value['museum_id'],
                                        $ad_value['advertisement_type'],
                                        $ad_value['advertisement_content']
                                    )
                                );
                                $index++;

                            }
                            $ad_value = [];
//                        var_dump($value);die;
                        }
//                        die;
                    }
                }
            }
            $value['werbung'] = [];
        }
    }
}