<?php

spip_query("SET NAMES 'utf8'");
spip_query("SET NAMES 'utf8'");
include_spip('inc/securiser_action');

	function formulaires_mini_profil_charger($id_auteur='',$readonly='') {

		if (!$id_auteur) $id_auteur=get_session('id_auteur');
		if (!autoriser("visiteur")) {
			$valeurs['editable'] = false;
		}
		$u = sql_fetch(sql_select('*','spip_members','id_auteur = '.$id_auteur,'','','',''));
		$valeurs = array(
			"title" => $u['title'],
			"name" => $u['name'],
			"surname" => $u['surname'],
			"addr1" => $u['addr1'],
			"addr2" => $u['addr2'],
			"addr3" => $u['addr3'],
			"addr4" => $u['addr4'],
			"addr5" => $u['addr5'],
			"country" => $u['country'],
			"fax1" => $u['fax1'],
			"fax1_pn" => $u['fax1_pn'],
			"fax1_pl" => $u['fax1_pl'],						
			"tel1" => $u['tel1'],
			"tel1_pn" => $u['tel1_pn'],
			"tel1_pl" => $u['tel1_pl'],						
			"company" => $u['company'],
			"practicein" => $u['practicein'],
			'id_auteur' => $id_auteur,
			'readonly' => $readonly
		);
 
		/* L'interface du logo */
		$chercher_logo = charger_fonction('chercher_logo', 'inc');
		list($fid, $dir, $nom, $format) = $chercher_logo($valeurs['id_auteur'], 'id_auteur', 'on');

		$contenu = '<input type="file" name="image" size="20"/> <br /><br /><input type="submit" name="sousaction1" value="Update">';
		$valeurs['form_upload'] = securiser_action_auteur('iconifier', $valeurs['id_auteur'].'+auton'.$valeurs['id_auteur'], $_SERVER["REQUEST_URI"], $contenu, ' method="POST" enctype="multipart/form-data"');

		$valeurs['url_supp'] = securiser_action_auteur('iconifier', $valeurs['id_auteur']."-".$nom.'.'.$format, $_SERVER["REQUEST_URI"]);
			
		return $valeurs;
	}
		
?>	