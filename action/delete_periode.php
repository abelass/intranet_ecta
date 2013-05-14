<?php
/**
 * Plugin Signaler des abus
 * (c) 2012 My Chacra
 * Licence GNU/GPL
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function action_delete_periode_dist($arg=null) {	
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	
    list($id,$action)=explode('-',$arg);
    
    switch($action){
        case 'commitee':
            sql_delete('spip_members_commitees','id_membership='.$id);
            break;
        case 'council':
            sql_delete('spip_members_council','id_membership_council='.$id);
            break;            
    }
    


	return ;
}

?>
