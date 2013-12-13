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

    $row = sql_fetsel('statut,id_auteur,name,surname,title,email,login,password', 'spip_members','seq='.intval($seq));

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
        $id_auteur=isset($row['id_auteur'])?$row['id_auteur']:'';
        if($id_auteur==0)$id_auteur='';
        $email=isset($row['email'])?$row['email']:'';
        $notifications = charger_fonction('notifications', 'inc');
        
        if($s=='attente_paiement')
	$notifications('member_attente_paiement',$seq,$c);
        elseif($s=='accepte'){
            include_spip('action/inscrire_auteur');
            $name=$row['name'];
            $surname=$row['surname'];
            $login=test_login($name.$surname,$email);
                
            $contexte=array(
                    'title'=>$row['title'],
                    'name'=>$name, 
                    'surname'=>$surname,  
                    'email'=>$row['email'], 
                    'login'=>$login,    
                    );
            //Si pas encore lié à un auteur
            if(!$id_auteur){
                //Si il y a déjà un auteur avec ce mail on le prend et on actualise avec les nouvelles données
                if($email){
                    $auteur=sql_fetsel('id_auteur,statut','spip_auteurs','email='.sql_quote($email));
                    $id_auteur=$auteur['id_auteur'];
                    
                    $valeurs=array( 
                        'login' => $login,
                        );
                    //Si le compte est désactivé, on l'active
                    if (!in_array($auteur['statut'],array('6forum','1comite','0minirezo')))$valeurs['statut']='6forum';
                    sql_updateq('spip_auteurs',$valeurs,'id_auteur='.$id_auteur);
                    }
                // si on a toujours pas récupéré l'id_auteur, on crée un nouveau
                if(!$id_auteur){
                    $changer_statut=false;
                    $inserer_auteur=charger_fonction('inserer_auteur','inc');
                    $id_auteur = $inserer_auteur($contexte);                    
                }
                    $password=creer_pass_pour_auteur($id_auteur);
                    $contexte['password']=$password;
                //lier le membre à l'auteur
                   sql_updateq('spip_members',array('id_auteur'=>$id_auteur,'login'=>$login,'password'=>$password),'seq='.$seq);
                }
            $notifications('member_accepte',$seq,array_merge($c,$contexte)); 
            }
        else{
            include_spip('action/editer_auteur');
            $relation_statut=array('application'=>'nouveau','attente_paiement'=>'nouveau','accepte'=>'6forum');
            auteur_instituer($id_auteur,array('statut'=>$relation_statut[$s]));
        }
        
        }


    return ''; // pas d'erreur
}
