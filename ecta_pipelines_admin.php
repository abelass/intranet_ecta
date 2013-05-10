<?php

$p=explode(basename(_DIR_PLUGINS)."/",str_replace('\\','/',realpath(dirname(__FILE__))));

define('_DIR_PLUGIN_ECTA',"/plugins/intranet_ecta/");



function spip_ajouter_boutons($flux){

	$flux['naviguer']->sousmenu['membre_spip_list']= new Bouton("../"._DIR_PLUGIN_ECTA."/img_pack/ico_membres.png",_T('ecta:Members_manager'));

	return $flux;

}

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

