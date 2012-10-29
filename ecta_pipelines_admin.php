<?php

$p=explode(basename(_DIR_PLUGINS)."/",str_replace('\\','/',realpath(dirname(__FILE__))));

define('_DIR_PLUGIN_ECTA',"/plugins/intranet_ecta/");



function ecta_ajouter_boutons($flux){

	$flux['naviguer']->sousmenu['membre_ecta_list']= new Bouton("../"._DIR_PLUGIN_ECTA."/img_pack/ico_membres.png",_T('ecta:Members_manager'));

	return $flux;

}

function ecta_post_typo($texte) {


 $texte=preg_replace('/\n= /','-', $texte);

    return $texte;
}

?>

