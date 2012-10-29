<?php

spip_query("SET NAMES 'utf8'",'ectamembersdev');
spip_query("SET NAMES 'utf8'");

	function formulaires_form_public_charger() {
		if (!autoriser("visiteur")) {
			$valeurs['editable'] = false;
		}
		$u = sql_fetch(sql_select('*','ecta_members','id_auteur = '.session_get('id_auteur'),'','','','','ectamembersdev'));

		$valeurs = $u;
		$valeurs['password'] = '';
		$valeurs['lastname'] = $u['name'];
		$valeurs["firstname"] = $u['surname'];

		$liste_conf = sql_allfetsel('*','ecta_conferencies','','','year DESC,type','','','ectamembersdev');
		foreach($liste_conf as $conf) {
			if ($conf['type']=='autumn')
				$liste_autumn[$conf['id_conference']] = $conf;
			else
				$liste_spring[$conf['id_conference']] = $conf;
			/// $l_conference[$conf['id_conference']] = $conf; Rollback du 24/12/09
		}
		
		$l = sql_allfetsel('*','ecta_members_conferencies','id_member = "'.$u['seq'].'"','','','','','ectamembersdev');
		foreach($l as $emc) {
			$participation[$emc['id_conference']] = $emc['participation'];
		}
		
		/*
		function option_asso($asso) {
			$str = '';
			$vals = sql_allfetsel('DISTINCT title','ecta_associations','1','','title ASC','','','ectamembersdev');
			foreach ($vals as $e) {
				$str .= '<option value="'.$e['title'].'"'.($asso==$e['title']?' selected="selected"':'').">".$e['title']."</option>\n";
			}
			return $str;
		}
		$valeurs['li_associations'] = option_asso($u['otherassociations']); */
		
		function option_participation($participation) {
			$str = '';
			$vals = array('No','Speaker', 'Chair', 'Delegate');
			foreach ($vals as $o) {
				$str .= '<option value="'.$o.'"'.($participation==$o?' selected="selected"':'').">".$o."</option>\n";
			}
			return $str;
		}
		
		$user = sql_fetsel('login','spip_auteurs','id_auteur='.$u['id_auteur']);
		$valeurs['login'] = $user['login'];
		
		$mt = sql_fetsel('*','ecta_members_type','id_member_type='.$u['membertype'],'','','','','ectamembersdev');
		$valeurs['membertype'] = supprimer_numero($mt['title']);
		
		$valeurs['list_commitee_member'] = array();
		$sql="select ecta_commitees.title FROM ecta_commitees,ecta_members_commitees 
					where ecta_members_commitees.id_commitee=ecta_commitees.id_commitee and id_member='{$u['seq']}'";
		$q = sql_query($sql,'ectamembersdev');
		while ($emc = sql_fetch($q,'ectamembersdev'))
			$valeurs['list_commitee_member'][] = $emc['title'];
		if (!count($valeurs['list_commitee_member'])) $valeurs['list_commitee_member'] = '-';
		else $valeurs['list_commitee_member'] = implode(', ',$valeurs['list_commitee_member']);
		
		$valeurs['chaircommitee'] = "No";
		$sql="select ecta_commitees.title FROM ecta_commitees,ecta_members 
					where ecta_members.id_chaircommitee=ecta_commitees.id_commitee and seq='{$u['seq']}'";
		$q = sql_query($sql,'ectamembersdev');
		while ($emc = sql_fetch($q,'ectamembersdev'))
			$valeurs['chaircommitee'] = $emc['title'];
		
		$valeurs['vicechaircommitee'] = "No";
		$sql="select ecta_commitees.title FROM ecta_commitees,ecta_members 
					where ecta_members.id_vicechaircommitee=ecta_commitees.id_commitee and seq='{$u['seq']}'";
		$q = sql_query($sql,'ectamembersdev');
		while ($emc = sql_fetch($q,'ectamembersdev'))
			$valeurs['vicechaircommitee'] = $emc['title'];
		
		$valeurs['secretarycommitee'] = "No";
		$sql="select ecta_commitees.title FROM ecta_commitees,ecta_members 
					where ecta_members.id_secretarycommitee=ecta_commitees.id_commitee and seq='{$u['seq']}'";
		$q = sql_query($sql,'ectamembersdev');
		while ($emc = sql_fetch($q,'ectamembersdev'))
			$valeurs['secretarycommitee'] = $emc['title'];
		
		$valeurs['li_spring'] = $valeurs['li_autumn'] = '';
		
		foreach($liste_autumn as $id=>$conf) {
			$valeurs['li_autumn'] .= "<li>\n<label>".$conf['title']." ".$conf['year']."</label>\n".
				'<select name="conferences['.$id.']">'."\n".
				option_participation($participation[$id]).
				"\n</select>\n</li>\n\n";
		}
		
		foreach($liste_spring as $id=>$conf) {
			$valeurs['li_spring'] .= "<li>\n<label>".$conf['title']." ".$conf['year']."</label>\n".
				'<select name="conferences['.$id.']">'."\n".
				option_participation($participation[$id]).
				"\n</select>\n</li>\n\n";
		} 
		
		/* Code mort : on ne devait pas distinguer été et hiver
		   Mais Rollback le 24/10
		foreach($l_conference as $id=>$conf) {
			$valeurs['li_conf'] .= "<li>\n<label>".$conf['title']." ".$conf['year']."</label>\n".
				'<select name="conferences['.$id.']">'."\n".
				option_participation($participation[$id]).
				"\n</select>\n</li>\n\n";
		} */
		

		$q = sql_query("select id_association, title, 0+title AS num_order FROM ecta_associations order by num_order",'ectamembersdev');
		
		while($association = spip_fetch_array($q)) {
			$associations['title'] = supprimer_numero($associations['title']);
		
			$q2 = sql_query("select id_association FROM ecta_members_associations 
					where id_association='{$association['id_association']}' and id_member='{$u['seq']}'",'ectamembersdev');
		
			if (spip_fetch_array($q2)) $checked=" checked "; else $checked = '';
		
			$valeurs['li_associations'] .= "
					<li>
						<input name=\"associations[{$association['id_association']}]\" type=\"checkbox\" value=\"{$association['id_association']}\" $checked> ".supprimer_numero($association['title'])."
					</li>
				";
		}


		/* Categories of professionnal */
		$valeurs['li_cat_professionnal'] = '';
		
		$q = spip_query("select id_category, title, 0+title AS num_order FROM ecta_categories_of_professional order by num_order",'ectamembersdev');
		
		while($category = spip_fetch_array($q)) {
			$category['title'] = supprimer_numero($category['title']);
		
			$q2 = sql_query("select id_category FROM ecta_members_categories_of_professional 
					where id_category='{$category['id_category']}' and id_member='{$u['seq']}'",'ectamembersdev');
		
			if (spip_fetch_array($q2)) $checked=" checked "; else $checked = '';
		
			$valeurs['li_cat_professionnal'] .= "
					<li>
						<input name=\"categories_of_professional[{$category['id_category']}]\" type=\"checkbox\" value=\"{$category['id_category']}\" $checked>&nbsp; {$category['title']}
					</li>
				";
		} 
		
		
		return $valeurs;
	}
	
	function formulaires_form_public_verifier() {
		$erreurs = array();
		foreach(array('gender','email','country') as $champ) {
			if (!_request($champ)) {
				$erreurs[$champ] = "This field is mandatory !";
			}
		}
		if (count($erreurs)) {
			$erreurs['message_erreur'] = "Please correct the errors bellow";
		}
		return $erreurs;
	}
	
	function formulaires_form_public_traiter() {
		$update = array(
			"gender" => _request('gender'),
			"birthdate" => _request('birthdate'),
			"title" => _request('title'),
//			"name" => _request('firstname'),
//			"surname" => _request('lastname'),
			"email" => _request('email'),
			"addr1" => _request('addr1'),
			"addr2" => _request('addr2'),
			"addr3" => _request('addr3'),
			"addr4" => _request('addr4'),
			"addr5" => _request('addr5'),
			"country" => _request('country'),
			"nationality" => _request('nationality'),
			"fax1" => _request('fax1'),
			"fax2" => _request('fax2'),
			"tel1" => _request('tel1'),
			"tel2" => _request('tel2'),
			"tel3" => _request('tel3'),
			"listed_in_dir" => _request('listed_in_dir'),
			"OHIM" => _request('OHIM'),
			"company" => _request('company'),
			"practicein" => _request('practicein'),
			"inactivitysince" => _request('inactivitysince'),
			"categoriesofprofessional" => _request('categoriesofprofessional'),
			"datemembership" => _request('datemembership'),
			"otherassociations" => _request('otherassociations')
		);
		if (_request('password')) {
			$update["password"] = _request('password');

			$sql = "UPDATE `spip_auteurs` SET `pass` = MD5( '1545607746460151d1d63984.51604272".addslashes(_request('password'))."' ) ,
			  `maj` = NOW( ) , 
			  `alea_actuel` = '1545607746460151d1d63984.51604272' 
			  WHERE id_auteur = ".session_get('id_auteur')."
			  ";
			 
			$res=sql_query($sql);
		}
		
		$sql = "UPDATE `spip_auteurs` SET email = '".addslashes(_request('email'))."'
		  WHERE id_auteur = ".session_get('id_auteur');
		$res=sql_query($sql);
		
		foreach ($update as $k => $v) {$tsql[] = "$k = '".addslashes($v)."'";}
		sql_query('update ecta_members SET '.implode(',',$tsql).' where id_auteur='.session_get('id_auteur'),'ectamembersdev');

		/* Conferences */
		$member = sql_fetsel('seq','ecta_members','id_auteur='.session_get('id_auteur'),'','','','','ectamembersdev');
		sql_query('delete from ecta_members_conferencies where id_member='.$member['seq'],'ectamembersdev');
		
		if (_request('conferences'))
		foreach(_request('conferences') as $id_conf => $participation)
			sql_query('INSERT INTO ecta_members_conferencies(id_member, id_conference, participation) VALUES ('. $member['seq']. ', '. (int)$id_conf . ',"'. $participation.'")','ectamembersdev');
			
		/* Associations */
		sql_query('delete from ecta_members_associations where id_member='.$member['seq'],'ectamembersdev');
		
		if (_request('associations'))
		foreach(_request('associations') as $id_association)
			sql_query('INSERT INTO ecta_members_associations(id_member, id_association) VALUES ('. $member['seq']. ', '. (int)$id_association.')','ectamembersdev');
			

		/* categories_of_professional */
		spip_query("delete from ecta_members_categories_of_professional where id_member='".$member['seq']."'",'ectamembersdev');
		if (isset($_POST['categories_of_professional']))
			{foreach ($_POST['categories_of_professional'] as $value) {
				spip_query("insert into ecta_members_categories_of_professional(id_member,id_category) VALUES('".$member['seq']."','$value')",'ectamembersdev');
			}
		}
			


		$envoyer_mail = charger_fonction('envoyer_mail','inc');
    $envoyer_mail($GLOBALS['meta']['email_webmaster'], "mise à jour d'un profil public ({$member['seq']})",
                 "L'utilisateur a mis à jour son profil :\n\n".
								"seq = {$member['seq']}\n\n".
								'$_POST = '.print_r($_POST, true));

		return array('message_ok'=>"Modification done with success");
	}
	
?>	