<?php
/**
 * Plugin Signaler des abus
 * (c) 2012 My Chacra
 * Licence GNU/GPL
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function action_gestion_member_dist($arg=null) {	
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	
    list($action,$seq)=explode($arg);
    
    switch($action){
        case 'delete':
            $id_auteur=sql_getfetsel('id_auteur','spip_members','seq='.$seq);

            // SPIP-Liste : Abonnement à la liste "membres" (id = 4)
            sql_delete('spip_auteurs_listes', 'id_auteur = '.$id_auteur);
            sql_delete('spip_auteurs', 'id_auteur = '.$id_auteur);
            sql_delete('spip_members', 'id_auteur = '.$id_auteur);
    
            $message_maj = "The member has been deleted";
            break;
    
        
    }
    sql_delete('spip_members','id_membership='.$arg);


	return ;
}

?>
