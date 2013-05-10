<?php

spip_query("SET NAMES 'utf8'",'ectamembersdev');
spip_query("SET NAMES 'utf8'");
include_spip('inc/autoriser');

	function formulaires_directory_charger() {
		if (!autoriser("visiteur")) {
			$valeurs['editable'] = false;
		}


		function option_entr($entr) {
			$str = '';
			$vals = sql_allfetsel('DISTINCT company','spip_members','1','','company ASC','','','ectamembersdev');
			foreach ($vals as $e) {
				$replace=str_replace("'","__",$e['company']);
				$str .= '<option value="'.$replace.'"'.($entr==$e['company']?' selected="selected"':'').">".$e['company']."</option>\n";
			}
			return $str;
			
			echo $str;
		}
		
		function option_pays($pays) {
			$str = '';
			$c = sql_allfetsel('DISTINCT country','spip_members','1','','','','','ectamembersdev');
			foreach ($c as $lc) {
				$liste_c[] = $lc['country'];
			}
			$vals = sql_allfetsel('*','spip_geo_pays','code_iso IN ("'.implode('","',$liste_c).'")','','pays ASC');
			foreach ($vals as $p) {
				$str .= '<option value="'.$p['code_iso'].'"'.($pays==$p['code_iso']?' selected="selected"':'').">".$p['pays']."</option>\n";
			}
			return $str;
		}
		
		$where=array();
		
		if (_request('qm')) {
			$qm = _request('qm');
			if ($qm['firstname']) $where[] = 'name like "%'.translitteration(addslashes($qm['firstname'])).'%"';
			if ($qm['lastname']) $where[] = 'surname like "%'.translitteration(addslashes($qm['lastname'])).'%"';
			if ($qm['country']) {
				if ($qm['country'] != '%') 
					$where[] = 'country = "'.translitteration(addslashes($qm['country'])).'"';
			}
			if ($qm['company']) {
				$qm['company'] =str_replace("__","'",$qm['company']);
				if ($qm['company'] != '%') 
					$where[] = 'company = "'.translitteration(addslashes($qm['company'])).'"';
			}
			$valeurs['qm_firstname'] = $qm['firstname'];
			$valeurs['qm_lastname'] =$qm['lastname'];
			$valeurs['list_members'] = array();
		
			$i = 1;
		
			$where[] = "listed_in_dir = 'Yes'";
			
			$q = sql_query("SELECT country, id_auteur, title, name, surname, company from spip_members WHERE ".implode(' AND ',$where).' ORDER BY surname LIMIT 0,100','ectamembersdev');

			while ($emc = sql_fetch($q,'ectamembersdev')) {
				$valeurs['list_members'][$i++] = $emc;
			}
		}
		
		// Recherche par initiale
		
		if (_request('init')) {
			$q = sql_query("SELECT country, id_auteur, title, name, surname, company from spip_members WHERE surname LIKE '".translitteration(addslashes(_request('init')))."%' AND listed_in_dir = 'Yes' ORDER BY surname LIMIT 0,100",'ectamembersdev');

			while ($emc = sql_fetch($q,'ectamembersdev')) {
				$valeurs['list_members'][$i++] = $emc;
			}

		}
		
		$valeurs['option_qm_company'] = option_entr($qm['company']);
		$valeurs['option_qm_country'] = option_pays($qm['country']);

		return $valeurs;
	}
	
