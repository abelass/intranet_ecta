<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');

function exec_export_csv() {
	spip_query("SET NAMES 'utf8'",'ectamembersdev');
	spip_query("SET NAMES 'utf8'");

	header("Content-type: application/vnd.ms-excel;");
	header("Content-disposition: attachment; filename=membres-".date('Ymd').".csv"); 

echo "seq;id_auteur;membernumber;gender;title;name;surname;birthdate;email;login;password;addr1;addr2;addr3;addr4;addr5;country;nationality;fax1;fax2;tel1;tel2;tel3;listed_in_dir;OHIM;membertype;incommitee;commem;executivebodies;memberofhonour;sponsoredby1; sponsoredby2;datemembership;pastcouncil;company;practicein;inactivitysince;categoriesofprofessional;membership_fee;membership_year;payment_error;method_of_payment;date_of_payment;reference;councilmem;pastpresident;active;VAT number;Country Type;Council Member;Conferences";

$sql = "select * from ecta_commitees";
$q = spip_query($sql,'ectamembersdev');
while($r = spip_fetch_array($q)) {
	echo ';'.$r['title'];
	$commitees[] = $r['id_commitee'];
}

	echo  "\n";


	$sql = "select * from ecta_members_type";
	$q = spip_query($sql,'ectamembersdev');
	$type['-'] = '';
	while ($r = spip_fetch_array($q)) {
		$type[$r['id_member_type'].'-'] = supprimer_numero($r['title']);
	}

	$sql = "select * from spip_geo_pays";
	$q = spip_query($sql);
	$pays['-'] = '';
	while($r = spip_fetch_array($q)) {
		$pays[$r['code_iso'].'-'] = $r['pays'];
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
			
	$sql=translitteration("SELECT seq, ecta_members.id_auteur, ecta_members.membernumber, ecta_members.gender, ecta_members.title, ecta_members.name, ecta_members.surname, ecta_members.birthdate, ecta_members.email, ecta_members.password, ecta_members.addr1, ecta_members.addr2, ecta_members.addr3, ecta_members.addr4, ecta_members.addr5, ecta_members.country, ecta_members.nationality, ecta_members.fax1, ecta_members.fax2, ecta_members.tel1, ecta_members.tel2, ecta_members.tel3, ecta_members.listed_in_dir, ecta_members.OHIM, ecta_members.membertype, ecta_members.incommitee, ecta_members.commem, ecta_members.executivebodies, ecta_members.memberofhonour, ecta_members.sponsoredby1, ecta_members.sponsoredby2, ecta_members.datemembership, ecta_members.pastcouncil, ecta_members.company, ecta_members.practicein, ecta_members.inactivitysince, ecta_members.categoriesofprofessional, ecta_members.membership_fee,  ecta_members.membership_year, ecta_members.payment_error, ecta_members.method_of_payment, ecta_members.date_of_payment, ecta_members.reference, ecta_members.councilmem, 
	ecta_members.pastpresident, ecta_members.active, vat_number, countrytype, councilmem  FROM ecta_members $where");
	$reponse = spip_query($sql,'ectamembersdev');
	spip_log($sql);
	
	while($row = spip_fetch_array($reponse)){
		$user = sql_fetsel('login','spip_auteurs','id_auteur='.$row['id_auteur']);
		foreach($row as $k=>$v) $row[$k] = str_replace('"','""',$v);
		echo utf8_decode('"'.$row['seq'].'";"'.$row['id_auteur'].'";"'.$row['membernumber'].'";"'.$row['gender'].'";"'.$row['title'].'";"'.$row['name'].'";"'.$row['surname'].'";"'.$row['birthdate'].'";"'.$row['email'].'";"'.$user['login'].'";"'.$row['password'].'";"'.$row['addr1'].'";"'.$row['addr2'].'";"'.$row['addr3'].'";"'.$row['addr4'].'";"'.$row['addr5'].'";"'.$pays[$row['country'].'-'].'";"'.$pays[$row['nationality'].'-'].'";"'.$row['fax1'].'";"'.$row['fax2'].'";"'.$row['tel1$'].'";"'.$row['tel2'].'";"'.$row['tel3'].'";"'.$row['listed_in_dir'].'";"'.$row['OHIM'].'";"'.$type[$row['membertype'].'-'].'";"'.$row['incommitee'].'";"'.$row['commem'].'";"'.$row['executivebodies'].'";"'.$row['memberofhonour'].'";"'.$row['sponsoredby1'].'";"'.$row['sponsoredby2'].'";"'.$row['datemembership'].'";"'.$row['pastcouncil'].'";"'.$row['company'].'";"'.$pays[$row['practicein'].'-'].'";"'.$row['inactivitysince'].'";"'.$row['categoriesofprofessional'].'";"'.$row['membership_fee'].'";"'.$row['membership_year'].'";"'.$row['payment_error'].'";"'.$row['method_of_payment'].'";"'.$row['date_of_payment'].'";"'.$row['reference'].'";"'.$row['councilmem'].'";"'.
		$row['pastpresident'].'";"'.$row['active'].'";"'.$row['vat_number'].'";"'.$row['countrytype'].'";"'.$row['councilmem']);

		$mcommitees = array();
		$sqlc = "select * from ecta_members_commitees where id_member = ".$row['seq'];
		$qc = spip_query($sql,'ectamembersdev');
		while($rc = spip_fetch_array($qc)) {
			$mcommitees[] = $r['id_commitee'];
		}

		foreach($commitees as $id_c) {
			if (in_array($id_c,$mcommitees)) echo  '";"yes';
			else  echo  '";"no';
		}
		
		echo  "\"\n";
	}

}
