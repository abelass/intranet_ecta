<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * $c est un array ('statut', 'id_parent' = changement de rubrique)
 * statut et rubrique sont lies, car un admin restreint peut deplacer
 * un objet publie vers une rubrique qu'il n'administre pas
 *
 
 * @param int $seq
 * @param array $c
 * @param bool $calcul_rub
 * @return mixed|string
 */
function member_instituer($seq, $c, $calcul_rub=true) {

   
    include_spip('inc/autoriser');
    include_spip('inc/rubriques');
    include_spip('inc/modifier');

    $row = sql_fetsel('statut,id_auteur,name,surname,title,email', 'spip_members','seq='.intval($seq));

    $statut_ancien = $statut = $row['statut'];

    $champs = array();

    $s = isset($c['statut'])?$c['statut']:$statut;

    
    // cf autorisations dans inc/instituer_objet
    if ($s != $statut) {
        if (autoriser('instituer','member', $seq, null, array('statut'=>$s)))
            $statut = $champs['statut'] = $s;
        elseif ($s!='accepte' AND autoriser('modifier','member', $seq))
            $statut = $champs['statut'] = $s;
        else
            spip_log("editer_objet $id refus " . join(' ', $c));

        // En cas de publication, fixer la date a "maintenant"
        // sauf si $c commande autre chose
        // ou si l'objet est deja date dans le futur
        // En cas de proposition d'un objet (mais pas depublication), idem
        if ($champ_date) {
            if ($champs['statut'] == 'publie'
             OR ($champs['statut'] == 'prop' AND !in_array($statut_ancien, array('publie', 'prop')))
             OR $d
            ) {
                if ($d OR strtotime($d=$date)>time())
                    $champs[$champ_date] = $date = $d;
                else
                    $champs[$champ_date] = $date = date('Y-m-d H:i:s');
            }
        }
    }
    

    // Envoyer aux plugins
    $champs = pipeline('pre_edition',
        array(
            'args' => array(
                'table' => $table_sql,
                'id_objet' => $id,
                'action'=>'instituer',
                'statut_ancien' => $statut_ancien,
                'date_ancienne' => $date_ancienne,
                'id_parent_ancien' => $id_rubrique,
            ),
            'data' => $champs
        )
    );

    if (!count($champs)) return '';

    // Envoyer les modifs.
        objet_editer_heritage('member', $seq, $id_rubrique, $statut_ancien, $champs, $calcul_rub);
    // Invalider les caches
    include_spip('inc/invalideur');
    suivre_invalideur("id='$objet/$id'");


    // Pipeline
    pipeline('post_edition',
        array(
            'args' => array(
                'table' => $table_sql,
                'id_objet' => $id,
                'action'=>'instituer',
                'statut_ancien' => $statut_ancien,
                'date_ancienne' => $date_ancienne,
                'id_parent_ancien' => $id_rubrique,
            ),
            'data' => $champs
        )
    );

    //Actions après changement de statut
    if($statut_ancien!=$s){
        $notifications = charger_fonction('notifications', 'inc');
        if($statut_ancien=="application" AND $s=='attente_paiement')$notifications('member_attente_paiement',$seq,$c);
        if($statut_ancien=="attente_paiement" AND $s=='accepte'){
            //Si pas encore lié à un auteur on le crée
            if(!isset($row['id_auteur']) OR $row['id_auteur']==0){
                list($login,$reste)=Explode('@',$row['email']);
                $password=substr(md5(time()),0,10);
                $inserer_auteur=charger_fonction('inserer_auteur','inc');
                      $contexte=array(
                        'title'=>$row['title'],
                        'name'=>$row['name'], 
                        'surname'=>$row['surname'],  
                        'email'=>$row['email'], 
                        'login'=>$login,    
                        'password'=>$password,
                        );
                   $id_auteur = $inserer_auteur($contexte);
                   //lier le membre à l'auteur
                   sql_updateq('spip_members',array('id_auteur'=>$id_auteur,'login'=>$login,'password'=>$password));
                }
            $notifications('member_accepte',$seq,array_merge($c,$contexte)); 
            }
        }


    return ''; // pas d'erreur
}