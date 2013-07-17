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
function notifications_member_application_dist($quoi, $seq, $options) {
    $options['seq']=$seq;  
    $email_client=$options['email'];
    $definitions = charger_fonction('definitions', 'inc');
    $envoyer_mail = charger_fonction('envoyer_mail','inc');
    
    $subject_client='Ecta- Confirmation of your application';
    $subject_admin='Ecta- Confirmation of application';  
    
    $message_client=recuperer_fond('notifications/member_application_client',$options);
    $message_admin=recuperer_fond('notifications/member_application_admin',$options);    
     
    $dest_admin=$definitions('dest_admin');
    
    spip_log($definitions('dest_admin'),'teste');

    //
    // Envoyer les emails
    //
    //
    //Member
    $envoyer_mail($email_client,$subject_client,$message_client);
    
    //Admin
    $envoyer_mail($dest_admin,$subject_admin,array('html'=>$message_admin));
    
}
?>
