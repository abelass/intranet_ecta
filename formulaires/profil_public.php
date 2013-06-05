<?php

spip_query("SET NAMES 'utf8'");
spip_query("SET NAMES 'utf8'");

	function formulaires_profil_public_charger($id_auteur='',$readonly='') {
        incLude_spip('inc/autoriser');
		if (!$id_auteur) $id_auteur=get_session('id_auteur');
		if (!autoriser("visiteur")) {
			$valeurs['editable'] = false;
		}
		$u = sql_fetch(sql_select('*','spip_members','id_auteur = '.$id_auteur,'','','',''));
		
		$tel1=($u['tel1_pn']?'+'.$u['tel1_pn'].' ':'').($u['tel1_pl']?$u['tel1_pl'].' ':'').$u['tel1'];
		$tel2=($u['tel2_pn']?'+'.$u['tel2_pn'].' ':'').($u['tel2_pl']?$u['tel2_pl'].' ':'').$u['tel2'];
		$tel3=($u['tel3_pn']?'+'.$u['tel3_pn'].' ':'').($u['tel3_pl']?$u['tel3_pl'].' ':'').$u['tel3'];
		$fax1=($u['fax1_pn']?'+'.$u['fax1_pn'].' ':'').($u['fax1_pl']?$u['fax1_pl'].' ':'').$u['fax1'];
		$fax2=($u['fax2_pn']?'+'.$u['fax2_pn'].' ':'').($u['fax2_pl']?$u['fax2_pl'].' ':'').$u['fax2'];
		
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
			"fax1" => $fax1,
			"tel1" => $tel1,
			"fax2" => $fax2,
			"tel2" => $tel2,
			"tel3" => $tel3,
			"email" => $u['email'],
			"membernumber" => $u['membernumber'],
			"company" => $u['company'],
			"practicein" => $u['practicein'],
			'id_auteur' => $id_auteur,
			'readonly' => $readonly
		);
			
		return $valeurs;
	}
		
?>		
