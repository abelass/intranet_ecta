<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');


function i2_import_csv_champ($champ) {
	$champ = preg_replace(',[\s]+,', ' ', $champ);
	$champ = str_replace(',",', '""', $champ);
	return '"'.$champ.'"';
}

function i2_import_csv_ligne($ligne, $delim = ',') {
	return join($delim, array_map('i2_import_csv_champ', $ligne))."\r\n";
}

function exec_export_csv() {
	include_spip('ecrire/inc/filtres');
	spip_query("SET NAMES 'utf8'");

$sql = "select * from spip_commitees";
$q = spip_query($sql);
$commitees= array();
$commitee_titre= array();
while($r = spip_fetch_array($q)) {
	$commitees[] = $r['id_commitee'];
	$commitee_titre[$r['id_commitee']] = $r['title'];
}


	$sql = "select * from spip_members_type";
	$q = spip_query($sql);
	while ($r = spip_fetch_array($q)) {
		$type_name[$r['id_member_type']] = supprimer_numero($r['title']);
	}

	$sql = "select * from spip_geo_pays";
	$q = spip_query($sql);
	$pays['-'] = '';
	while($r = spip_fetch_array($q)) {
		$country_full[$r['code_iso']] = $r['pays'];
	}
	
	$where = array();

		if ($s_name = mysql_real_escape_string(strtolower(_request('s_name')))) 
			$where[] = "(LOWER(name) like '%$s_name%' OR LOWER(surname) like '%$s_name%' OR LOWER(membernumber)=$s_name)";
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
		
	$delim = $arg ? $arg : (_request('delim') ? _request('delim') : ',');
		
		/**
		 * R&eacute;cup&eacute;ration des champs &agrave; exporter
		 */
		$tables = array('spip_members');

		$tablefield=array('seq','id_auteur','membernumber','gender','title','name','surname','birthdate','email','login','password','addr1','addr2','addr3','addr4','addr5','country','nationality','fax1_pn','fax1_pl','fax1','fax2_pn','fax2_pl','fax2','tel1_pn','tel1_pl','tel1','tel2_pn','tel2_pl','tel2','tel3_pn','tel3_pl','tel3','listed_in_dir','OHIM','membertype','incommitee','commem','executivebodies','memberofhonour','sponsoredby1','sponsoredby2','datemembership','pastcouncil','company','practicein','inactivitysince','categoriesofprofessional','membership_fee','membership_year','payment_error','method_of_payment','date_of_payment','reference','pastpresident','active','vat_number','Country Type','Council Member','Conferences');
		
		$coms= array();
		$sqlcoms = "select * from spip_commitees";
			$qc = spip_query($sqlcoms);
				while($rcoms = spip_fetch_array($qc)) {
				$coms[$rcoms['id_commitee']] = $rcoms['title'];
				$tablefield[]=$rcoms['title'];
				}
		
		/**
		 * Export des tables mergés
		 */
		$output = i2_import_csv_ligne($tablefield,$delim);	
		
			
	$sql=translitteration("SELECT seq, spip_members.id_auteur, spip_pip_members.membernumber, spip_members.gender, spip_members.title, spip_members.name, spip_members.surname, spip_members.birthdate, spip_members.email, spip_members.password, spip_members.addr1, spip_members.addr2, spip_members.addr3, spip_members.addr4, spip_members.addr5, spip_members.country, spip_members.nationality, spip_members.fax1_pn,spip_members.fax1_pl,spip_members.fax1,spip_members.fax2_pn,spip_members.fax2_pl,spip_members.fax1,spip_members.tel1_pn, spip_members.tel1_pl, spip_members.tel1,spip_members.tel2_pn, spip_members.tel2_pl, spip_members.tel2,spip_members.tel3_pn, spip_members.tel3_pl,spip_members.tel3,spip_members.listed_in_dir,spip_members.OHIM,spip_members.membertype, spip_members.incommitee, spip_members.commem, spip_members.executivebodies, spip_members.memberofhonour, spip_members.sponsoredby1, spip_members.sponsoredby2, spip_members.datemembership, spip_members.pastcouncil, spip_members.company, spip_members.practicein, spip_members.inactivitysince, spip_members.categoriesofprofessional, spip_members.membership_fee,  spip_members.membership_year, spip_members.payment_error, spip_members.method_of_payment, spip_members.date_of_payment, spip_members.reference,
	spip_members.pastpresident, spip_members.active, spip_members.vat_number, spip_members.countrytype  FROM spip_members $where");
	$reponse = spip_query($sql);
	spip_log($sql);
	
	//member role types
	$r=sql_select('*','spip_commitee_role');
    $roles=array();
        while($rs=sql_fetch($r)){
            $roles[$rs['id_commitee_role']]=$rs['title'];  
          }
	
	while($row = spip_fetch_array($reponse)){

	$row['mem_coms']=array();		
	$com=sql_select('*','spip_members_commitees','id_member='.$row['seq']);
	
	while($data=sql_fetch($com)){
	    if($data['end_date']>0)$end_date=affdate($data['end_date'],'Y');
        else $end_date='today';
		if($data['start_date']>0 or $data['id_commitee_role']!=0) $row['mem_coms'][$row['membernumber']][$data['id_commitee']][]=$roles[$data['id_commitee_role']].': '.affdate($data['start_date'],'Y').'-'.$end_date ;
		}
		
    $row['Council Member']=array();     
    $counc=sql_select('*','spip_members_council','seq='.$row['seq']);   
    
    while($data=sql_fetch($counc)){
        if($data['end_date']>0)$end_date=affdate($data['end_date'],'Y');
        else $end_date='today';
        if($data['start_date']>0) $row['Council Member'][]=$data['statut'].': '.affdate($data['start_date'],'Y').'-'.$end_date ;
        }
	$ligne=array();
	foreach($tablefield as $key)
	  if (isset($row[$key])){
	  foreach($commitees as $id_c) {
	     
	  	if(is_array($row['mem_coms'][$row['membernumber']])){  			  
			if ($row['mem_coms'][$row['membernumber']][$id_c]){
				$row[$commitee_titre[$id_c]]=implode(', ',$row['mem_coms'][$row['membernumber']][$id_c]);				
				}
			else   $row[$commitee_titre[$id_c]]="no";
		}
		else  {
			$row[$commitee_titre[$id_c]]="no";
			}
		}
	   if($key=='country')  {  $ligne[]=$country_full[$row['country']];}
		elseif($key=='membertype') {$ligne[]=$type_name[$row['membertype']];}
        elseif($key=='Council Member')  $ligne[]=implode(', ',$row['Council Member']);
		else {$ligne[]=$row[$key];}

	}		
	else{
		  $ligne[]="";
		  }
	$output .= i2_import_csv_ligne($ligne,$delim);
}
	
		$charset = 'charset=UTF-16LE';
	
		include_spip('inc/texte');
		$filename = preg_replace(',[^-_\w]+,', '_', translitteration(textebrut(typo(_T('i2_import:export_users_sites',array('date' => date('Y-m-d'),'site'=>$GLOBALS['meta']['nom_site']))))));
	
		// Excel ?
		if ($delim == ',')
			$extension = 'csv';
		else {
			$extension = 'xls';
			# Excel n'accepte pas l'utf-8 ni les entites html... on fait quoi?
			include_spip('inc/charsets');
			$output = unicode2charset(charset2unicode($output), 'iso-8859-1');
			$charset = 'iso-8859-1';
		}
		header("Content-type: application/vnd.ms-excel;$charset");
		header("Content-disposition: attachment; filename=membres-".date('Ymd').".csv"); 
		Header("Content-Length: ".strlen($output));
		echo $output;
		exit;
	
	return;

}
