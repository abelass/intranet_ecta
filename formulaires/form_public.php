<?php

spip_query("SET NAMES 'utf8'",'ectamembersdev');
spip_query("SET NAMES 'utf8'");



	function formulaires_form_public_charger() {
		include_spip('inc/autoriser');
		include_spip('inc/session');
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
				"\n</select>\n</li>\n\n
				<input name=\"conferences_test[$id]\" type=\"hidden\" value=\"$participation[$id]\"/>";
		}
		
		foreach($liste_spring as $id=>$conf) {
			$valeurs['li_spring'] .= "<li>\n<label>".$conf['title']." ".$conf['year']."</label>\n".
				'<select name="conferences['.$id.']">'."\n".
				option_participation($participation[$id]).
				"\n</select>\n</li>\n\n
				<input name=\"conferences_test[$id]\" type=\"hidden\" value=\"$participation[$id]\"/>";
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
			if($checked){$hidden="<input name=\"associations_test[{$association['id_association']}]\" type=\"hidden\" value=\"{$association['id_association']}\">";}
			$valeurs['li_associations'] .= "
					<li>
						<input name=\"associations[{$association['id_association']}]\" type=\"checkbox\" value=\"{$association['id_association']}\" $checked> ".supprimer_numero($association['title']).$hidden.
						
					"</li>
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
			if($checked) $hidden="<input name=\"categories_of_professional_test[{$category['id_category']}]\" type=\"hidden\" value=\"{$category['id_category']}\"/>";
			$valeurs['li_cat_professionnal'] .= "
					<li>
						<input name=\"categories_of_professional[{$category['id_category']}]\" type=\"checkbox\" value=\"{$category['id_category']}\" $checked>&nbsp; {$category['title']}$hidden

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
		include_spip('inc/session');
		$id_auteur=session_get('id_auteur');	
		$email=session_get('email');			
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
			"fax1_pn" => _request('fax1_pn'),
			"fax1_pl" => _request('fax1_pl'),						
			"fax2" => _request('fax2'),
			"fax2_pn" => _request('fax2_pn'),
			"fax2_pl" => _request('fax2_pl'),			
			"tel1" => _request('tel1'),
			"tel1_pn" => _request('tel1_pn'),
			"tel1_pl" => _request('tel1_pl'),			
			"tel2" => _request('tel2'),
			"tel2_pn" => _request('tel2_pn'),
			"tel2_pl" => _request('tel2_pl'),			
			"tel3" => _request('tel3'),
			"tel3_pn" => _request('tel3_pn'),
			"tel3_pl" => _request('tel3_pl'),			
			"listed_in_dir" => _request('listed_in_dir'),
			"OHIM" => _request('OHIM'),
			"company" => _request('company'),
			"practicein" => _request('practicein'),
			"inactivitysince" => _request('inactivitysince'),
			"categoriesofprofessional" => _request('categoriesofprofessional'),
			"datemembership" => _request('datemembership'),
			"otherassociations" => _request('otherassociations'),
			"datestamp" => date('d-m-Y H:i:s')
		);
		if (_request('password')) {
			$update["password"] = _request('password');

			$sql = "UPDATE `spip_auteurs` SET `pass` = MD5( '1545607746460151d1d63984.51604272".addslashes(_request('password'))."' ) ,
			  `maj` = NOW( ) , 
			  `alea_actuel` = '1545607746460151d1d63984.51604272' 
			  WHERE id_auteur = ".$id_auteur."
			  ";
			 
			$res=sql_query($sql);
		}
		
		$sql = "UPDATE `spip_auteurs` SET email = '".addslashes(_request('email'))."'
		  WHERE id_auteur = ".$id_auteur;
		$res=sql_query($sql);
		
		foreach ($update as $k => $v) {$tsql[] = "$k = '".addslashes($v)."'";}
		sql_query('update ecta_members SET '.implode(',',$tsql).' where id_auteur='.$id_auteur,'ectamembersdev');

		/* Conferences */
		$member = sql_fetsel('seq','ecta_members','id_auteur='.$id_auteur,'','','','','ectamembersdev');
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
			
		reset($_POST); 
		$ch = array();		
		while (list($key, $val) = each($_POST)) { 
			if(preg_match('/_test/', $key)){
				$name_field=str_replace('_test','',$key); 
				$or=$_POST[$key];
				if($or!=$_POST[$name_field])$ch[$name_field]=$_POST[$name_field];					
				}	
		}
		
		
		
		if(count($ch)>0){
			
		//actualisation mailchimp
			spip_log('actualisation profil','sclp');
			$flux=array(
				'data'=>array('id_auteur'=>$id_auteur)
				);
			$flux['args']['args'][4]['email']=_request('email_test');	
            include_spip('sclp_fonctions');
			$traitement=charger_fonction('editer_auteur_traiter_listes','inc');
			$flux=$traitement($flux);
		
			
			
			$donnees_membre=sql_fetsel('name,surname','ecta_members','id_auteur='.$id_auteur);
			$membre=$donnees_membre['name'].($donnees_membre['surname']?' '.$donnees_membre['surname']:'');
			if(!$membre)$membre='name not detected ,id_auteur'.$id_auteur;
			$message_mail .="Last modification done from the frontend on : ".$update['datestamp']."  by ".$membre."\n\n";
			$message_mail .="Modification - Member number:".$_POST['membernumber']."\n\n";
			$message_mail .="The following modifications have been made:\n\n";									

			foreach($ch as $key=>$val){
				if(is_array($val))$val=implode(',',$val);
				$message_mail .= $key.': '.$val."\n\n";
				}
				
			$envoyer_mail = charger_fonction('envoyer_mail','inc');
   			//$envoyer_mail($GLOBALS['meta']['email_webmaster314040'], "Modification of a public profile (Nr ".$_POST['membernumber'].")", $message_mail, true);
			$envoyer_mail('ecta@ecta.org', "Modification of a public profile (Nr ".$_POST['membernumber'].")", $message_mail, true);
   			//$envoyer_mail('websolutions@mychacra.net', "Modification of a public profile (Nr ".$_POST['membernumber'].")", $message_mail, true);
			}				
			spip_log($message_mail,'teste');
		return array('message_ok'=>"Modification done with success");
	}
	
?>	
