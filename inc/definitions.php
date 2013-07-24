<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function inc_definitions_dist($def=''){
    
   $definitions=array('dest_admin'=>array('ecta@ecta.org'));
    
    if($def) $definitions=$definitions[$def];
    
    return $definitions;
}