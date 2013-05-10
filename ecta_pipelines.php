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



function spip_post_typo($texte) {


 $texte=preg_replace('/\n= /','-', $texte);

    return $texte;
}

function spip_formulaire_traiter($flux){
    //actualiser le email dans ecta members si changé via le formulaire editer_auteur
    if ($flux['args']['form'] == 'editer_auteur') {
        sql_updateq('spip_members',array('email'=>_request('email')),'id_auteur='._request('id_auteur'));
    }
    return $flux;
}
 


?>