<?php
/*
 * Plugin Intranet Ecta
 * (c) 2009-2013 SPIP
 * Distribue sous licence GPL
 *
 */

if (!defined("_ECRIRE_INC_VERSION")) return;


/**
 * cette notification s'envoie quand un membre s'inscrit,
 *
 * @param string $quoi
 * @param int $id_forum
 */
function notifications_member_accepte_dist($quoi, $seq, $options) {
    $definitions = charger_fonction('definitions', 'inc');
    $envoyer_mail = charger_fonction('envoyer_mail','inc');
    
    $options['seq']=$seq;  
    
    $member=sql_fetsel('name,surname,email','spip_members','seq='.$seq);
    $options['name']=$member['name']; 
    $options['surname']=$member['surname']; 
    
    $email_client=$member['email'];
    $email_admin=$definitions('dest_admin');
    
    $subject_client='Ecta- Payment received';
    $subject_admin=$subject_client;  
    
    $message_client=recuperer_fond('notifications/member_accepte_client',$options);
    $message_admin=recuperer_fond('notifications/member_accepte_admin',$options);   
     
    //
    // Envoyer les emails
    //
    //
    //Member
	spip_log($message_client,'teste');
    $envoyer_mail($email_client,$subject_client,array(
        'html'=>$message_client)
       );
    
    //Admin
    $envoyer_mail( $email_admin,$subject_admin,$message_admin);
    
}
?>
