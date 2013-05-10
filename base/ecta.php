<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Intranete Ecta
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Ecta\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function ecta_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['members'] = 'members';
	$interfaces['table_des_tables']['associations'] = 'associations';
	$interfaces['table_des_tables']['categories_of_professional'] = 'categories_of_professional';
	$interfaces['table_des_tables']['commitees'] = 'commitees';
	$interfaces['table_des_tables']['conferencies'] = 'conferencies';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function ecta_declarer_tables_objets_sql($tables) {

	$tables['spip_members'] = array(
		'type' => 'member',
		'principale' => "oui",
		'field'=> array(
			"seq"                => "bigint(21) NOT NULL",
			"id_auteur"          => "bigint(20) NOT NULL DEFAULT '0'",
			"membernumber"       => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"gender"             => "enum('M','F') CHARACTER SET utf8 NOT NULL DEFAULT 'M'",
			"title"              => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"name"               => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"surname"            => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"birthdate"          => "varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"email"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"login"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"password"           => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"addr1"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"addr2"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"addr3"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"addr4"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"addr5"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"country"            => "varchar(3) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"nationality"        => "varchar(3) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"fax1_pn"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"fax1_pl"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"fax1"               => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"fax2_pn"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"fax2_pl"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"fax2"               => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"tel1_pn"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"tel1_pl"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"tel1"               => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"tel2_pn"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"tel2_pl"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"tel2"               => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"tel3_pn"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"tel3_pl"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL",
			"tel3"               => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"listed_in_dir"      => "enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'Yes'",
			"ohim"               => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"membertype"         => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"incommitee"         => "enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No'",
			"commem"             => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"executivebodies"    => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"memberofhonour"     => "enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No'",
			"sponsoredby1"       => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"sponsoredby2"       => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"datemembership"     => "varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"pastcouncil"        => "enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No'",
			"company"            => "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''",
			"practicein"         => "varchar(3) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"inactivitysince"    => "varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"categoriesofprofessional" => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"membership_fee"     => "varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Not a member'",
			"membership_year"    => "year(4) DEFAULT NULL",
			"payment_error"      => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"method_of_payment"  => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"date_of_payment"    => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"reference"          => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"last_modified"      => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
			"datestamp"          => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"countrytype"        => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"_membership"        => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"printed"            => "int(1) NOT NULL DEFAULT '0'",
			"otherassociations"  => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"pastpresident"      => "enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No'",
			"active"             => "enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'Yes'",
			"vat_number"         => "varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''",
			"special_requests"   => "text COLLATE utf8_unicode_ci NOT NULL",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "seq",
		),
		'titre' => "title AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array(),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	$tables['spip_associations'] = array(
		'type' => 'association',
		'principale' => "oui",
		'field'=> array(
			"id_association"     => "bigint(21) NOT NULL",
			"title"              => "varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_association",
		),
		'titre' => "title AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('title'),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	$tables['spip_categories_of_professional'] = array(
		'type' => 'category',
		'principale' => "oui", 
		'table_objet_surnoms' => array('categoriesofprofessional', 'category'), // table_objet('category') => 'categories_of_professional' 
		'field'=> array(
			"id_category"        => "bigint(21) NOT NULL",
			"title"              => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_category",
		),
		'titre' => "title AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('title'),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	$tables['spip_commitees'] = array(
		'type' => 'commitee',
		'principale' => "oui",
		'field'=> array(
			"id_commitee"        => "bigint(21) NOT NULL",
			"title"              => "varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_commitee",
		),
		'titre' => "title AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('title'),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	$tables['spip_conferencies'] = array(
		'type' => 'conference',
		'principale' => "oui",
		'field'=> array(
			"id_conference"      => "bigint(21) NOT NULL",
			"title"              => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"type"               => "varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT ''",
			"year"               => "year(4) NOT NULL DEFAULT '0000'",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_conference",
		),
		'titre' => "title AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('title', 'type', 'year'),
		'champs_versionnes' => array('title'),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	return $tables;
}



?>