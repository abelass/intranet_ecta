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
function notifications_member_attente_paiement_dist($quoi, $seq, $options) {
    $definitions = charger_fonction('definitions', 'inc');
    $envoyer_mail = charger_fonction('envoyer_mail','inc');
    
    $options['seq']=$seq;  
    
    $member=sql_fetsel('name,surname,email','spip_members','seq='.$seq);
    $options['name']=$member['name']; 
    $options['surname']=$member['surname']; 
    
    $email_client=$member['email'];
    $email_admin=$definitions('dest_admin');
    
    $subject_client='Ecta- Application accepted';
    $subject_admin=$subject_client;  
    
    $message_client=recuperer_fond('notifications/member_attente_paiement_client',$options);
    $message_admin=recuperer_fond('notifications/member_attente_paiement_admin',$options);    
     
    //
    // Envoyer les emails
    //
    //
    //Member
    
    $fichier=realpath(find_in_path('docs/letter_2_payment_form.doc'));
    spip_log('url'.$fichier,'teste');
    
    $corps = array(
       'html' => $message_client,
       'pieces_jointes' => array(
               array('chemin' => $fichier,
               'nom' => 'letter_2_payment_form.doc',
               'encodage' => 'base64',
               'mime' => 'application/msword')
               )
        );
    
    $envoyer_mail($email_client,$subject_client, $corps);
    
    //Admin
    $envoyer_mail($email_admin,$subject_admin,$message_admin);
    
}
?>
