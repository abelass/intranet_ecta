<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

function inc_inserer_auteur_dist($contexte){
    $nom = (isset($contexte['title'])?$contexte['title'].' ':'').$contexte['name'].' '.$contexte['surname'];
            if (!trim($nom)) $nom = '-';
    $email=$contexte['email'];
    $sql = array( 
        'nom' => $nom, 
        'bio' => '', 
        'email' => $email, 
        'nom_site' => '', 
        'url_site' => '', 
        'login' => $contexte['login'], 
        'pass' => md5( '1545607746460151d1d63984.51604272'.$contexte['password']) , 
        'low_sec' => '', 
        'statut' => '6forum', 
        'maj' =>  date('Y-m-d H:i:s'), 
        'pgp' => '', 
        'htpass' => '', 
        'en_ligne' => '', 
        'alea_actuel' => '1545607746460151d1d63984.51604272', 
        'alea_futur' => '157799821346015be7c75233.74847129', 
        'prefs' => 'a:1:{s:3:\"cnx\";s:0:\"\";}', 
        'cookie_oubli' => '', 
        'source' => 'spip', 
        'lang' => 'en'
        );
                
    $id_auteur=sql_insertq('spip_auteurs', $sql);
    
    set_request('id_auteur',$id_auteur);
    
    // SPIP-Liste : Abonnement à la liste "membres" (id = 4)
    $sql = array('id_auteur' => $id_auteur,'id_liste'=>4,'statut'=>'valide','format'=>'html');
    sql_insertq('spip_auteurs_listes', $sql);
    
    spip_log('actualisation profil intranet','sclp');
    $flux=array(
        'data'=>array('id_auteur'=>$id_auteur)
        );
    $flux['args']['args'][4]['email']=$email;    
    
    $traitement=charger_fonction('editer_auteur_traiter_listes','inc');
    $flux=$traitement($flux);
    return $id_auteur;
    
}