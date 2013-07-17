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
        if($seq=$flux['data']['seq'] AND _request('new')){
        $flux['data']['redirect']=generer_url_ecrire($objet,'seq='.$seq);
        }
    }
    
    return $flux;
}

//Faire en sorte que l'id:auteur crée soit enregistré dans la bd  
function ecta_pre_insertion($flux){

    if ($flux['args']['table']=='spip_members' AND _request('exec')){
      $inserer_auteur=charger_fonction('inserer_auteur','inc');
      $contexte=array(
            'title'=>_request('title'),
            'name'=>_request('name'), 
            'surname'=>_request('surname'),  
            'email'=>_request('email'), 
            'login'=>_request('login'),    
            'password'=>_request('password'),                                       
            );
        $flux['data']['id_auteur'] = $inserer_auteur($contexte);
    }
    return $flux;
}

function ecta_header_prive($flux){
    $flux .= '<link rel="stylesheet" href="' .find_in_path('css/ecta_prive.css').'" type="text/css" media="all" />';
    return $flux;
}

function ecta_insert_head($flux){
    $flux .= '<link rel="stylesheet" href="' .find_in_path('css/ecta_public.css').'" type="text/css" media="all" />';
    return $flux;
}
function ecta_jqueryui_plugins($scripts){
   $scripts[] = "jquery.ui.autocomplete";
   $scripts[] = "jquery.ui.tabs";
   $scripts[] = "jquery.ui.datepicker";   
   return $scripts;

}



?>