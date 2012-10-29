<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');

function exec_export_xls() {

  /** PHPExcel */
   require_once dirname(__FILE__).'/../lib/PHPExcel.php';

   /** PHPExcel_IOFactory */
   require_once dirname(__FILE__).'/../lib/PHPExcel/IOFactory.php';


   // Create new PHPExcel object
   $objPHPExcel = new PHPExcel();

   // Set properties
   $objPHPExcel->getProperties()->setCreator("Gilles VINCENT (Tech-Nova)")
               ->setLastModifiedBy("Gilles VINCENT (Tech-Nova)")
							 ->setTitle("ECTA members");

   // Add some data
   $objPHPExcel->setActiveSheetIndex(0)
               ->setCellValue('A1', 'Seq')
               ->setCellValue('B1', 'id_auteur')
               ->setCellValue('C1', 'membernumber')
               ->setCellValue('D1', 'gender')
               ->setCellValue('E1', 'title')
               ->setCellValue('F1', 'name')
               ->setCellValue('G1', 'surname')
               ->setCellValue('H1', 'birthdate')
               ->setCellValue('I1', 'email')
               ->setCellValue('J1', 'login')
               ->setCellValue('K1', 'password')
               ->setCellValue('L1', 'addr1')
               ->setCellValue('M1', 'addr2')
               ->setCellValue('N1', 'addr3')
               ->setCellValue('O1', 'addr4')
               ->setCellValue('P1', 'addr5')
               ->setCellValue('Q1', 'country')
               ->setCellValue('R1', 'nationality')
               ->setCellValue('S1', 'fax1')
               ->setCellValue('T1', 'fax2')
               ->setCellValue('U1', 'tel1')
               ->setCellValue('V1', 'tel2')
               ->setCellValue('W1', 'tel3')
               ->setCellValue('X1', 'listed_in_dir')
               ->setCellValue('Y1', 'OHIM')
               ->setCellValue('Z1', 'membertype')
               ->setCellValue('AA1', 'incommitee')
               ->setCellValue('AB1', 'commem')
               ->setCellValue('AC1', 'executivebodies')
               ->setCellValue('AD1', 'memberofhonour')
               ->setCellValue('AE1', 'sponsoredby1')
               ->setCellValue('AF1', 'sponsoredby2')
               ->setCellValue('AG1', 'datemembership')
               ->setCellValue('AH1', 'pastcouncil')
               ->setCellValue('AI1', 'company')
               ->setCellValue('AJ1', 'practicein')
               ->setCellValue('AK1', 'inactivitysince')
               ->setCellValue('AL1', 'categoriesofprofessional')
               ->setCellValue('AM1', 'membership_fee')
               ->setCellValue('AN1', 'membership_year')
               ->setCellValue('AO1', 'payment_error')
               ->setCellValue('AP1', 'method_of_payment')
               ->setCellValue('AQ1', 'date_of_payment')
               ->setCellValue('AR1', 'reference')
               ->setCellValue('AS1', 'councilmem')
               ->setCellValue('AT1', 'pastpresident')
               ->setCellValue('AU1', 'active')
               ->setCellValue('AV1', 'VAT number')
               ->setCellValue('AW1', 'Country Type')
               ->setCellValue('AX1', 'Council Member')
               ->setCellValue('AY1', 'Conferences');

	// create a simple 2-dimensional array

	$cols = array(
		'A' => 'seq', 
		'B' => 'id_auteur', 
		'C' => 'membernumber', 
		'D' => 'gender', 
		'E' => 'title', 
		'F' => 'name', 
		'G' => 'surname', 
		'H' => 'birthdate', 
		'I' => 'email', 
		'J' => 'login', 
		'K' => 'password', 
		'L' => 'addr1', 
		'M' => 'addr2', 
		'N' => 'addr3', 
		'O' => 'addr4', 
		'P' => 'addr5', 
		'Q' => 'country', 
		'R' => 'nationality', 
		'S' => 'fax1', 
		'T' => 'fax2', 
		'U' => 'tel1', 
		'V' => 'tel2', 
		'W' => 'tel3', 
		'X' => 'listed_in_dir', 
		'Y' => 'OHIM', 
		'Z' => 'membertype', 
		'AA' => 'incommitee', 
		'AB' => 'commem', 
		'AC' => 'executivebodies', 
		'AD' => 'memberofhonour', 
		'AE' => 'sponsoredby1',
		'AF' => 'sponsoredby2',
		'AG' => 'datemembership',
		'AH' => 'pastcouncil', 
		'AI' => 'company', 
		'AJ' => 'practicein', 
		'AK' => 'inactivitysince', 
		'AL' => 'categoriesofprofessional', 
		'AM' => 'membership_fee', 
		'AN' => 'membership_year', 
		'AO' => 'payment_error', 
		'AP' => 'method_of_payment', 
		'AQ' => 'date_of_payment', 
		'AR' => 'reference', 
		'AS' => 'councilmem', 
		'AT' => 'pastpresident', 
		'AU' => 'active',
		'AV' => 'vat',
		'AW' => 'countrytype',
		'AX' => 'councilmember',
		'AY' => 'conferences'
	);


	
		spip_query("SET NAMES 'utf8'",'ectamembersdev');
		spip_query("SET NAMES 'utf8'");


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

		$sql=translitteration("SELECT seq, ecta_members.id_auteur, ecta_members.membernumber, ecta_members.gender, ecta_members.title, ecta_members.name, ecta_members.surname, ecta_members.birthdate, ecta_members.email, ecta_members.password, ecta_members.addr1, ecta_members.addr2, ecta_members.addr3, ecta_members.addr4, ecta_members.addr5, ecta_members.country, ecta_members.nationality, ecta_members.fax1, ecta_members.fax2, ecta_members.tel1, ecta_members.tel2, ecta_members.tel3, ecta_members.listed_in_dir, ecta_members.OHIM, ecta_members.membertype, ecta_members.incommitee, ecta_members.commem, ecta_members.executivebodies, ecta_members.memberofhonour, ecta_members.sponsoredby1, ecta_members. sponsoredby2, ecta_members.datemembership, ecta_members.pastcouncil, ecta_members.company, ecta_members.practicein, ecta_members.inactivitysince, ecta_members.categoriesofprofessional, ecta_members.membership_fee,  ecta_members.membership_year, ecta_members.payment_error, ecta_members.method_of_payment, ecta_members.date_of_payment, ecta_members.reference, ecta_members.councilmem, 
		ecta_members.pastpresident, ecta_members.active FROM ecta_members $where ");
	$reponse = spip_query($sql,'ectamembersdev');
	
	$i = 1;
	
	while($row = spip_fetch_array($reponse)){
		$i++;
		
		$user = sql_fetsel('login','spip_auteurs','id_auteur='.$row['id_auteur']);
		$row['login'] = $user['login'];
		
		$user=array();
		foreach ($cols as $c => $k) {
			if (isset($row[$k])) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("$c$i", $row[$k]);
			}
		}
		
	}

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Members_'.date('Ymd_Hi'));

	// Redirect output to a clientâs web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="members_'.date('Ymd_Hi').'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

}
