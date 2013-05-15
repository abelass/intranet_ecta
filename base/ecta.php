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
	$interfaces['table_des_tables']['executive_bodies'] = 'executive_bodies'; 
	$interfaces['table_des_tables']['membership'] = 'membership'; 
	$interfaces['table_des_tables']['members_associations'] = 'members_associations';
	$interfaces['table_des_tables']['members_categories_of_professional'] = 'members_categories_of_professional';
    $interfaces['table_des_tables']['members_commitees'] = 'members_commitees';
    $interfaces['table_des_tables']['members_council'] = 'members_council';
    $interfaces['table_des_tables']['members_conferencies'] = 'members_conferencies';    
    $interfaces['table_des_tables']['members_type'] = 'members_type';
    $interfaces['table_des_tables']['membership_type'] = 'membership_type';    
    
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
			"incommitee"         => "enum('Yes','No','Com') CHARACTER SET utf8 NOT NULL DEFAULT 'No'",
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
		'titre' => "CONCAT(name,' ', surname) AS titre, '' AS lang",
		 #'date' => "",
        'champs_editables'  => array('membernumber', 'gender', 'title', 'name', 'surname', 'birthdate', 'email', 'login', 'password', 'addr1', 'addr2', 'addr3', 'addr4', 'addr5', 'country', 'nationality', 'fax1_pn', 'fax1_pl', 'fax1', 'fax2_pn', 'fax2_pl', 'fax2', 'tel1_pn', 'tel1_pl', 'tel1', 'tel2_pn', 'tel2_pl', 'tel2', 'tel3_pn', 'tel3_pl', 'tel3', 'listed_in_dir', 'ohim', 'membertype', 'incommitee', 'commem', 'executivebodies', 'memberofhonour', 'sponsoredby1', 'sponsoredby2', 'datemembership', 'pastcouncil', 'company', 'practicein', 'inactivitysince', 'categoriesofprofessional', 'membership_fee', 'membership_year', 'payment_error', 'method_of_payment', 'date_of_payment', 'reference', 'countrytype', '_membership', 'printed', 'otherassociations', 'pastpresident', 'active', 'vat_number', 'special_requests','last_modified'),
         'champs_versionnes' => array('membernumber', 'gender', 'title', 'name', 'surname', 'birthdate', 'email', 'login', 'password', 'addr1', 'addr2', 'addr3', 'addr4', 'addr5', 'country', 'nationality', 'fax1_pn', 'fax1_pl', 'fax1', 'fax2_pn', 'fax2_pl', 'fax2', 'tel1_pn', 'tel1_pl', 'tel1', 'tel2_pl', 'tel2', 'tel3_pn', 'tel3_pl', 'tel3', 'listed_in_dir', 'ohim', 'membertype', 'incommitee', 'executivebodies', 'memberofhonour', 'sponsoredby1', 'sponsoredby2', 'datemembership', 'pastcouncil', 'company', 'practicein', 'inactivitysince', 'membership_fee', 'membership_year', 'payment_error', 'method_of_payment', 'date_of_payment', 'reference', 'countrytype', '_membership', 'printed', 'otherassociations', 'pastpresident', 'active', 'vat_number', 'special_requests'),
         'rechercher_champs' => array("membernumber" => 8, "name" => 8, "surname" => 8, "email" => 8, "addr1" => 4, "addr2" => 4, "addr3" => 3, "addr4" => 3, "addr5" => 3, "country" => 3, "nationality" => 2, "company" => 4),
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

function ecta_declarer_tables_principales($tables_principales){
    //-- Table spip_executive_bodies------------------
    $spip_executive_bodies = array(
        "id_spip_executive_body"  => "int(11) NOT NULL",
        "title" => "varchar(50) DEFAULT 'oui' NOT NULL",
        );
 
    $spip_executive_bodies_key = array(
        "PRIMARY KEY"   => "id_spip_executive_body",
        );
 
    $tables_principales['spip_executive_bodies'] = array(
        'field' => &$spip_executive_bodies, 
        'key' => &$spip_executive_bodies_key, 
        'join'=>array(
            'id_spip_executive_body'=>'id_spip_executive_body'
        ));
        
    //-- Table spip_membership_type------------------
    $spip_membership_type = array(
        "id_membership_type"  => "int(4) NOT NULL",
        "membership_fee" => "varchar(20) DEFAULT 'oui' NOT NULL",
        "amount"  => "int(11) NOT NULL",        
        );
 
    $spip_membership_types_key = array(
        "PRIMARY KEY"   => "id_membership_type,membership_fee",
        );
 
    $tables_principales['spip_membership_type'] = array(
        'field' => &$spip_membership_type, 
        'key' => &$spip_membership_type_key, 
        'join'=>array(
            'id_membership_type'=>'id_membership_type'
        )); 
        
    //-- Table spip_membership_type------------------
    $spip_membership_type = array(
        "id_membership_type"  => "int(4) NOT NULL",
        "membership_fee" => "varchar(20) DEFAULT 'oui' NOT NULL",
        "amount"  => "int(11) NOT NULL",        
        );
 
    $spip_membership_types_key = array(
        "PRIMARY KEY"   => "id_membership_type,membership_fee",
        );
 
    $tables_principales['spip_membership_type'] = array(
        'field' => &$spip_membership_type, 
        'key' => &$spip_membership_type_key, 
        'join'=>array(
            'id_membership_type'=>'id_membership_type'
        )); 
        
    //-- Table spip_members_commitees------------------
    $spip_members_commitees = array(
        "id_membership"  => "int(21) NOT NULL",
        "id_member"  => "int(11) NOT NULL",
        "id_commitee"  => "int(11) NOT NULL",
        "id_commitee_role"  => "int(11) NOT NULL",               
        "start_date" => "date DEFAULT '0000-00-00' NOT NULL",
        "end_date" => "date DEFAULT '0000-00-00' NOT NULL",

        );
 
    $spip_members_commitees_key = array(
        "PRIMARY KEY"   => "id_membership,id_member,id_commitee,id_commitee_role",
        );
 
    $tables_principales['spip_members_commitees'] = array(
        'field' => &$spip_members_commitees, 
        'key' => &$spip_members_commitees_key, 
        'join'=>array(
            'id_membership'=>'id_membership',
            'id_member'=>'id_member',   
            'id_commitee'=>'id_commitee', 
            'id_commitee_role'=>'id_commitee_role'                                    
        )); 
        
    //-- Table spip_members_council------------------
    $spip_members_council = array(
        "id_membership_council"  => "int(21) NOT NULL",
        "seq"  => "int(11) NOT NULL",
        "statut"  => "enum('Yes','Com') NOT NULL DEFAULT ''",
        "id_commitee_role"  => "int(11) NOT NULL",               
        "start_date" => "date DEFAULT '0000-00-00' NOT NULL",
        "end_date" => "date DEFAULT '0000-00-00' NOT NULL",

        );
 
    $spip_members_council_key = array(
        "PRIMARY KEY"   => "id_membership_council,seq,statut",
        );
 
    $tables_principales['spip_members_council'] = array(
        'field' => &$spip_members_council, 
        'key' => &$spip_members_council_key, 
        'join'=>array(
            'id_membership_council'=>'id_membership_council',
            'seq'=>'seq',                                     
        ));  

    //-- Table spip_members_type-----------------
    $spip_members_type = array(
        "id_member_type"  => "int(6) NOT NULL",
        "title" => "varchar(50) DEFAULT 'oui' NOT NULL",
        );
 
    $spip_members_type_key = array(
        "PRIMARY KEY"   => "    id_member_type",
        );
 
    $tables_principales['spip_members_type'] = array(
        'field' => &$spip_members_type, 
        'key' => &$spip_members_type_key, 
        'join'=>array(
            '   id_member_type'=>'  id_member_type',                                
        ));  

    return $tables_principales;
}

function spip_declarer_tables_auxiliaires($tables_auxiliaires){
     
    //Table spip_members_associations
    $spip_members_associations = array(
        'id_member' => 'bigint(11) DEFAULT "0" NOT NULL',
        'id_association' => 'bigint(11) DEFAULT "0" NOT NULL',
    );
 
    $spip_members_associations_keys = array(
        'PRIMARY KEY' => 'id_member, id_association'
    );
 
    $tables_auxiliaires['spip_members_associations'] = array(
        'field' => &$spip_members_associations,
        'key' => &$spip_members_associations_keys
    );

    //Table spip_members_categories_of_professional
    $spip_members_categories_of_professional = array(
        'id_member' => 'bigint(11) DEFAULT "0" NOT NULL',
        'id_category' => 'bigint(11) DEFAULT "0" NOT NULL',
    );
 
    $spip_members_categories_of_professional_keys = array(
        'PRIMARY KEY' => 'id_member, id_category'
    );
 
    $tables_auxiliaires['spip_members_categories_of_professional'] = array(
        'field' => &$spip_members_categories_of_professional,
        'key' => &$spip_members_categories_of_professional_keys
    );

    //Table spip_members_commitees
    $spip_members_commitees = array(
        'id_member' => 'bigint(11) DEFAULT "0" NOT NULL',
        'id_category' => 'bigint(11) DEFAULT "0" NOT NULL',
    );
 
    $spip_members_commitees_keys = array(
        'PRIMARY KEY' => 'id_member, id_category'
    );
 
    $tables_auxiliaires['spip_members_commitees'] = array(
        'field' => &$spip_members_commitees,
        'key' => &$spip_members_commitees_keys
    );
    
    //Table spip_members_conferencies
    $spip_members_conferencies = array(
        'id_conference' => 'bigint(11) DEFAULT "0" NOT NULL',    
        'id_member' => 'bigint(11) DEFAULT "0" NOT NULL',
        'particiaption' => "enum('No','Speaker','Chair','Delegatge') DEFAULT 'No' NOT NULL",
    );
 
    $spip_members_conferencies_keys = array(
        'PRIMARY KEY' => 'id_conference, id_member'
    );
 
    $tables_auxiliaires['spip_members_conferencies'] = array(
        'field' => &$spip_members_conferencies,
        'key' => &$spip_members_conferencies_keys
    );        
    return $tables_auxiliaires;
}

?>