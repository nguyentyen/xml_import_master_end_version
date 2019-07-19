<?php
/**
 * Created by PhpStorm.
 * User: yennguyen
 * Date: 18.07.2019
 * Time: 18:40
 */

namespace Laravie\Parser\Xml;
class import {
    public $xml;
    public $data_to_import;
    public $datensatz_arr; //array von einzel obj xml
    public $datensatz;

    function __construct($data_obj) {
        $this->xml = $data_obj;
    }

    function set_single_em_value($value_name, $value_key) {
//        if($value_name == "EMYAJAHR" && is_array($this->datensatz[$value_name])) {
//            $this->data_to_import[$this->datensatz['KEY']][$value_key] = $this->datensatz[$value_name][0];
//        }
        if(isset($this->datensatz[$value_name])) {
            if(($value_name == "ORTORIGINAL" | $value_name == "ORTDEUTSCH") && is_array($this->datensatz[$value_name])) {
                $this->data_to_import[$this->datensatz['KEY']][$value_key] = $this->datensatz[$value_name][0];
            } else {
                $this->data_to_import[$this->datensatz['KEY']][$value_key] = $this->datensatz[$value_name];
            }
        } else {
            $this->data_to_import[$this->datensatz['KEY']][$value_key] = '';
        }

    }

    function set_em_value() {
        $this->set_single_em_value('LANDORIGINAL', 'landoriginal');
        $this->set_single_em_value('LANDDEUTSCH', 'landdeutsch');
        $this->set_single_em_value('ORTORIGINAL', 'ortoriginal');
        $this->set_single_em_value('ORTDEUTSCH', 'ortdeutsch');
        $this->set_single_em_value('NAMEORIGINAL', 'nameoriginal');
        $this->set_single_em_value('NAMEDEUTSCH', 'namedeutsch');
        $this->set_single_em_value('EMYAJAHR', 'emyajahr');
    }

    function edit_advert_in_more_tags () { // key 1814
//        $d = $this->datensatz;
//        file_put_contents(time(), $d);
        foreach($this->datensatz['WERBUNG'] as $advert_key=>$advert_value) {
            $advert_value_arr = get_object_vars($advert_value);
            foreach($advert_value_arr as $advert_arr_key=>$advert_arr_value) {
                if(!is_array($advert_arr_value)) {
                    $this->data_to_import[$this->datensatz['KEY']]['werbung'][$advert_arr_key][] = $advert_arr_value;

                } else {
                    foreach($advert_arr_value as $key3=>$value3) {
                        $this->data_to_import[$this->datensatz['KEY']]['werbung'][$advert_key][] = $value3;
                    }
                }
            }

        }
//        var_dump($this->data_to_import);die;
    }

    function edit_advert_as_obj () { //key 3 existiert nur ein Tag
        $advert_array = get_object_vars($this->datensatz['WERBUNG']);
        foreach($advert_array as $advert_key=>$advert_value) {
            if(!is_array($advert_value)) {
                $this->data_to_import[$this->datensatz['KEY']]['werbung'][$advert_key][] = $advert_value;
            } else {
                foreach($advert_value as $key3=>$value3) {
                    $this->data_to_import[$this->datensatz['KEY']]['werbung'][$advert_key][] = $value3;
                }
            }
        }
    }

    function edit_data() {
        echo "edit data";
        foreach($this->xml->content->EM as $value) {
            $this->datensatz_arr = get_object_vars($value);
//            var_dump($value);
////            die;
//            if($this->datensatz_arr["KEY"] == 1814 | $this->datensatz_arr["KEY"] == 1813) { //test werbung in unterarray/verschiedenen Tags
            $this->datensatz = get_object_vars($value);
            $this->set_em_value();
            if(isset($this->datensatz['WERBUNG'])) { //werbung exist
                if(is_array($this->datensatz['WERBUNG'])) { //werbung in verschiedene Tags
                    $this->edit_advert_in_more_tags();
                } else if (is_object($this->datensatz['WERBUNG'])){
                    $this->edit_advert_as_obj();
                }
            }
//            }
        }
    }

    function get_data() {

        return $this->data_to_import;

    }
}
