<?php
namespace Laravie\Parser\Xml;

require_once DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'probearbeit'.DIRECTORY_SEPARATOR.'xml_import_master'.DIRECTORY_SEPARATOR.'parser-master'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Xml'.DIRECTORY_SEPARATOR.'Reader.php';
require_once DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'probearbeit'.DIRECTORY_SEPARATOR.'xml_import_master'.DIRECTORY_SEPARATOR.'parser-master'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Xml'.DIRECTORY_SEPARATOR.'Document.php';
require_once DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'probearbeit'.DIRECTORY_SEPARATOR.'xml_import_master'.DIRECTORY_SEPARATOR.'import.php';
require_once DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'probearbeit'.DIRECTORY_SEPARATOR.'xml_import_master'.DIRECTORY_SEPARATOR.'save_to_db.php';
/**
require_once('parser-master\src\Xml\Reader.php');
require_once('parser-master\src\Xml\Document.php');
require_once('save_to_db.php');
require_once('import.php');
require_once('save_to_db.php');
*/

use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;
use Throwable;
use Laravie\Parser\Reader as BaseReader;
use Laravie\Parser\FileNotFoundException;
use Laravie\Parser\InvalidContentException;
use Laravie\Parser\Document as BaseDocument;

//$data = file_get_contents("Emya.xml");

$xml = (new Reader(new Document()))->load('Emya.xml');

//echo 123;
//echo "<pre>";


$import = new Import($xml);
//var_dump($import);
$import->edit_data();
$data_to_import = $import->get_data();
//echo "_________________________";
var_dump(count($data_to_import));
//echo "_________________________";
//die;

$db = new \pdo_insert($data_to_import);

$db->insert();







