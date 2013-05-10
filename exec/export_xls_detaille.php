<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');

function exec_export_xls() {

	set_time_limit(300);

	chdir(dirname(__FILE__)."/../lib/php_writeexcel");
	require_once "class.writeexcel_workbookbig.inc.php";
	require_once "class.writeexcel_worksheet.inc.php";
	chdir(dirname(__FILE__));

	$fname = tempnam("/tmp", "bigfile.xls");
	spip_log($fname);
	
	$workbook = &new writeexcel_workbookbig($fname);
	$worksheet = &$workbook->addworksheet();

	$worksheet->set_column(0, 80, 18);

  $worksheet->write('0', '0', 'Seq');
  $worksheet->write('0', '1', 'id_auteur');
  $worksheet->write('0', '2', 'Member Number');
  $worksheet->write('0', '3', 'Gender');
  $worksheet->write('0', '4', 'Title');
  $worksheet->write('0', '5', 'Name');
  $worksheet->write('0', '6', 'Surname');
  $worksheet->write('0', '7', 'Birthdate');
  $worksheet->write('0', '8', 'Email');
  $worksheet->write('0', '9', 'Login');
  $worksheet->write('0', '10', 'Password');
  $worksheet->write('0', '11', 'Addr1');
  $worksheet->write('0', '12', 'Addr2');
  $worksheet->write('0', '13', 'Addr3');
  $worksheet->write('0', '14', 'Addr4');
  $worksheet->write('0', '15', 'Addr5');
  $worksheet->write('0', '16', 'Country');
  $worksheet->write('0', '17', 'Nationality');
  $worksheet->write('0', '18', 'Fax1');
  $worksheet->write('0', '19', 'Fax2');
  $worksheet->write('0', '20', 'Tel1');
  $worksheet->write('0', '21', 'Tel2');
  $worksheet->write('0', '22', 'Tel3');
  $worksheet->write('0', '23', 'Listed in dir');
  $worksheet->write('0', '24', 'OHIM');
  $worksheet->write('0', '25', 'Member type');
  $worksheet->write('0', '26', 'In commitee');
  $worksheet->write('0', '27', 'Commem');
  $worksheet->write('0', '28', 'Executive bodies');
  $worksheet->write('0', '29', 'Member of honour');
  $worksheet->write('0', '30', 'Sponsored by1');
  $worksheet->write('0', '31', 'Sponsored by2');
  $worksheet->write('0', '32', 'Date membership');
  $worksheet->write('0', '33', 'Past council');
  $worksheet->write('0', '34', 'Company');
  $worksheet->write('0', '35', 'Practicein');
  $worksheet->write('0', '36', 'In activity since');
  $worksheet->write('0', '37', 'Categories of professional');
  $worksheet->write('0', '38', 'Membership fee');
  $worksheet->write('0', '39', 'Membership year');
  $worksheet->write('0', '40', 'Payment error');
  $worksheet->write('0', '41', 'Method of payment');
  $worksheet->write('0', '42', 'Date of payment');
  $worksheet->write('0', '43', 'Bank Reference');
  $worksheet->write('0', '44', 'Council member');
  $worksheet->write('0', '45', 'Past president');
  $worksheet->write('0', '46', 'Active');
  $worksheet->write('0', '47', 'VAT number');
  $worksheet->write('0', '48', 'Country Type');
  $worksheet->write('0', '49', 'Special requests');

	// Commitees
	$q = sql_select('*', 'spip_commitees', '', '', 'title');
	$i = 50;
	while ($row = sql_fetch($q)) {
	  $worksheet->write('0', $i, $row['title']);
		$commitee[$row['id_commitee']] = $i;
		$i++;
	}

	// Conferences
	$q = sql_select('*', 'spip_conferencies', '', '', 'year, type DESC');
	while ($row = sql_fetch($q)) {
	  $worksheet->write('0', $i,  "{$row['title']} {$row['year']} ({$row['type']})");
		$conferencie[$row['id_conference']] = $i;
		$i++;
	}

	// create a simple 2-dimensional array

	$cols = array(
		'0' => 'seq', 
		'1' => 'id_auteur', 
		'2' => 'membernumber', 
		'3' => 'gender', 
		'4' => 'title', 
		'5' => 'name', 
		'6' => 'surname', 
		'7' => 'birthdate', 
		'8' => 'email', 
		'9' => 'login', 
		'10' => 'password', 
		'11' => 'addr1', 
		'12' => 'addr2', 
		'13' => 'addr3', 
		'14' => 'addr4', 
		'15' => 'addr5', 
		'16' => 'country', 
		'17' => 'nationality', 
		'18' => 'fax1', 
		'19' => 'fax2', 
		'20' => 'tel1', 
		'21' => 'tel2', 
		'22' => 'tel3', 
		'23' => 'listed_in_dir', 
		'24' => 'OHIM', 
		'25' => 'membertype', 
		'26' => 'incommitee', 
		'27' => 'commem', 
		'28' => 'executivebodies', 
		'29' => 'memberofhonour', 
		'30' => 'sponsoredby1',
		'31' => 'sponsoredby2',
		'32' => 'datemembership',
		'33' => 'pastcouncil', 
		'34' => 'company', 
		'35' => 'practicein', 
		'36' => 'inactivitysince', 
		'37' => 'categoriesofprofessional', 
		'38' => 'membership_fee', 
		'39' => 'membership_year', 
		'40' => 'payment_error', 
		'41' => 'method_of_payment', 
		'42' => 'date_of_payment', 
		'43' => 'reference', 
		'44' => 'councilmem', 
		'45' => 'pastpresident', 
		'46' => 'active',
		'47' => 'vat_number',
		'48' => 'countrytype',
		'49' => 'special_requests'
	);
	
		spip_query("SET NAMES 'utf8'");
		spip_query("SET NAMES 'utf8'");

		// Type membre
		$q = sql_select('*','spip_members_type','','','','','');
		while ($p = sql_fetch($q)) {
			$type_membre[$r['id_member_type']] = supprimer_numero($r['title']);
		}

		// Pays
		$q = sql_select('*','spip_geo_pays');
		while ($p = sql_fetch($q)) {
			$pays[$p['code_iso']] = $p['pays'];
		}


		$where = array();

			if ($s_name = mysql_real_escape_string(strtolower(_request('s_name')))) 
				$where[] = "(LOWER(name) like '%$s_name%' OR LOWER(surname) like '%$s_name%')";
			if ($s_company = mysql_real_escape_string(strtolower(_request('s_company')))) {
				if ($s_company == 'null') $where[] = "(company IS NULL or company = '')";
				else $where[] = "(LOWER(company) like '%$s_company%')";
			}
			if ($s_country = mysql_real_escape_string(strtolower(_request('s_country')))) {
				if ($s_country == 'null') $where[] = "(country IS NULL or country = '')";
				else $where[] = "(LOWER(country) like '%$s_country%')";
			}

			if ($initiale=mysql_real_escape_string(strtolower(_request('init')))) {
				if ($initiale!='all') $where[] = "(LOWER(surname) like '$initiale%')";
			}

			if ($membership_year=mysql_real_escape_string(strtolower(_request('membership_year')))) {
				$where[] = "(LOWER(membership_year) = '$membership_year')";
			}

			if ($membership_fee=mysql_real_escape_string(strtolower(_request('membership_fee')))) {
				$where[] = "(LOWER(membership_fee) = '$membership_fee')";
			}

			if (count($where)) {
				$where = 'WHERE '.implode(' AND ',$where);
			} else $where = '';

			$ORDER_BY = 'surname';
			if ($by = _request('by')) {
				if (in_array($by,array('surname','company','active','country')) && in_array($o = _request('order'),array('ASC','DESC'))) {
					$ORDER_BY = "$by $o";
					if ($by != 'surname') $ORDER_BY .= ", surname";
				}
			}

//echo '<pre>';

		$sql=translitteration("SELECT seq, id_auteur, membernumber, gender, title, name, surname, birthdate, email, password, addr1, addr2, addr3, addr4, addr5, country, nationality, fax1, fax2, tel1, tel2, tel3, listed_in_dir, OHIM, membertype, incommitee, commem, executivebodies, memberofhonour, sponsoredby1,  sponsoredby2, datemembership, pastcouncil, company, practicein, inactivitysince, categoriesofprofessional, membership_fee,  membership_year, payment_error, method_of_payment, date_of_payment, reference, councilmem, 
		pastpresident, active, vat_number, countrytype, special_requests FROM spip_members $where ");
	$reponse = sql_query($sql);
	//echo("$sql\n\n");
	
	$i = 0;
	
	while($row = sql_fetch($reponse)){
		$i++;
		
		$user = sql_fetsel('login','spip_auteurs','id_auteur='.$row['id_auteur']);
		$row['login'] = $user['login'];
		
		$user=array();
		foreach ($cols as $c => $k) {
			if (isset($row[$k])) {
				
				if (in_array($k,array('country','nationality','Practicein')) 
					&& $row[$k]
					&& isset($pays[$row[$k]])
				) { $valeur = $pays[$row[$k]];}
				elseif ($k == 'membertype' 
					&& $row[$k]
					&& isset($type_membre[$row[$k]])
				) { $valeur = $type_membre[$row[$k]];}
				else {
					$valeur = $row[$k];
				}
				$worksheet->write($i, $c, utf8_decode($valeur));
				//echo($row[$k]." | ");
			} else 
				$worksheet->write($i, $c, '-');
		}
		
		// Commitees
		$q = sql_select('*', 'spip_members_commitees', 'id_member = '.$row['seq']);
		while ($row_c = sql_fetch($q)) {
		  $worksheet->write($i, $commitee[$row_c['id_commitee']], 'Yes');
			//echo("\n comm : ".$commitee[$row_c['id_commitee']]." | ".$row_c['id_commitee']);
		}

		// Conferences
		$q = sql_select('*', 'spip_members_conferencies', 'id_member = '.$row['seq']);
		while ($row_c = sql_fetch($q)) {
		  $worksheet->write($i, $conferencie[$row_c['id_conference']], $row_c['participation']);
			//echo("\n conf : ".$conferencie[$row_c['id_conference']]." | ".$row_c['participation']);
		}


	}

	$workbook->close();

	header("Content-Type: application/x-msexcel; name=\"members_".date('Ymd_Hi').".xls\"");
	header("Content-Disposition: inline; filename=\"members_".date('Ymd_Hi').".xls\"");
	$fh=fopen($fname, "rb");
	fpassthru($fh);
	unlink($fname);

}
