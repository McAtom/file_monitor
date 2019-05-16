<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 09:47
 */


defined("YAK_ROOT") or define("YAK_ROOT", dirname(dirname(__FILE__)));
defined("YAK_LOG") or define("YAK_LOG", YAK_ROOT."/logs");
defined("YAK_CONF") or define("YAK_CONF", YAK_ROOT."/config");
defined("YAK_CORE") or define("YAK_CORE", YAK_ROOT."/core");

include_once YAK_CORE."/YakTools.php";
include_once YAK_CORE."/YakInotify.php";
include_once YAK_CORE."/YakSwoole.php";
include_once YAK_CORE."/YakLogReader.php";