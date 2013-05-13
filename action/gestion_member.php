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
	
    list($action,$id_auteur)=explode($arg);
    
    switch($action){
        case 'delete':
            // SPIP-Liste : Abonnement à la liste "membres" (id = 4)
            sql_delete('spip_auteurs_listes', 'id_auteur = '.$id_auteur);
            sql_delete('spip_auteurs', 'id_auteur = '.$id_auteur);
            sql_delete('spip_members', 'id_auteur = '.$id_auteur);
    
            $message_maj = "The member has been deleted";
            break;

        case 'desactivate':
            sql_updateq('spip_members',array('active'=>'No'),'id_auteur='.$id_auteur);
            sql_updateq('spip_auteurs',array('statut'=>'5poubelle'),'id_auteur='.$id_auteur);             
            $message_maj = "The member has been desactivated";
            break;
            
        case 'activate':
    
            sql_updateq('spip_members',array('active'=>'Yes'),'id_auteur='.$id_auteur);
            sql_updateq('spip_auteurs',array('statut'=>'6forum'),'id_auteur='.$id_auteur);               
            $message_maj = "The member has been activated";
            break;
    
        
    }
    


	return ;
}

?>
