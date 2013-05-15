<?php
/**
 * Utilisations de pipelines par Intranete Ecta
 *
 * @plugin     Intranete Ecta
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Ecta\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;
	

/*
 * Un fichier de pipelines permet de regrouper
 * les fonctions de branchement de votre plugin
 * sur des pipelines existants.
 */

function ecta_post_typo($texte) {


 $texte=preg_replace('/\n= /','-', $texte);

    return $texte;
}

function ecta_formulaire_traiter($flux){
    //actualiser le email dans ecta members si changé via le formulaire editer_auteur
    if ($flux['args']['form'] == 'editer_auteur') {
        sql_updateq('spip_members',array('email'=>_request('email')),'id_auteur='._request('id_auteur'));
    }
         $form = $flux['args']['form'];
         $objet=str_replace('editer_','',$form);
    
    //Redirection pour le members    
    if($objet=='member'){
        if($seq=$flux['data']['seq']){
        $flux['data']['redirect']=generer_url_ecrire($objet,'seq='.$seq);
        }
    }
    
    return $flux;
}

//Faire en sorte que l'id:auteur crée soit enregistré dans la bd  
function ecta_pre_insertion($flux){
    if ($flux['args']['table']=='spip_members'){
                $sql = array( 
                'nom' => addslashes(_request('title')." "._request('surname')." "._request('name')), 
                'bio' => '', 
                'email' => addslashes(_request('email')), 
                'nom_site' => '', 
                'url_site' => '', 
                'login' => $login, 
                'pass' => md5( '1545607746460151d1d63984.51604272'._request('password')) , 
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
                'lang' => 'en');
                
        $id_auteur=sql_insertq('spip_auteurs', $sql);
        set_request('id_auteur',$id_auteur);
        // SPIP-Liste : Abonnement à la liste "membres" (id = 4)
        $sql = array('id_auteur' => $id_auteur,'id_liste'=>4,'statut'=>'valide','format'=>'html');
        sql_insertq('spip_auteurs_listes', $sql);
        
        
        $flux['data']['id_auteur'] = $id_auteur;
    }
    return $flux;
}
function ecta_header_prive($flux){
    $flux .= '<link rel="stylesheet" href="' .find_in_path('css/ecta_prive.css').'" type="text/css" media="all" />';
    return $flux;
}


function ecta_jqueryui_plugins($scripts){

   $scripts[] = "jquery.ui.autocomplete";

   return $scripts;

}



?>