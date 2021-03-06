<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Intranete Ecta
 *
 * @plugin     Intranete Ecta
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Ecta\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Fonction d'installation et de mise à jour du plugin Intranete Ecta.
 *
 * Vous pouvez :
 *
 * - créer la structure SQL,
 * - insérer du pre-contenu,
 * - installer des valeurs de configuration,
 * - mettre à jour la structure SQL 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function ecta_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();


	$maj['create'] = array(
	   array('sql_alter','TABLE ecta_associations RENAME TO spip_associations'),
	   array('sql_alter','TABLE ecta_categories_of_professional RENAME TO spip_categories_of_professional'),
	   array('sql_alter','TABLE ecta_commitees RENAME TO spip_commitees'),
	   array('sql_alter','TABLE ecta_commitee_role RENAME TO spip_commitee_role'),  
       array('sql_alter','TABLE ecta_conferencies RENAME TO spip_conferencies'),  
       array('sql_alter','TABLE ecta_executive_bodies RENAME TO spip_executive_bodies'), 
       array('sql_alter','TABLE ecta_members RENAME TO spip_members'), 
       array('sql_alter','TABLE ecta_membership_type RENAME TO spip_membership_type'), 
       array('sql_alter','TABLE ecta_members_associations RENAME TO spip_members_associations'),
       array('sql_alter','TABLE ecta_members_categories_of_professional RENAME TO spip_members_categories_of_professional'),
       array('sql_alter','TABLE ecta_members_commitees RENAME TO spip_members_commitees'),  
       array('sql_alter','TABLE ecta_members_conferencies RENAME TO spip_members_conferencies'),  
       array('sql_alter','TABLE ecta_members_council RENAME TO spip_members_council'), 
       array('sql_alter','TABLE ecta_members_no_members RENAME TO spip_members_no_members'),     
       array('sql_alter','TABLE ecta_members_type RENAME TO spip_members_type'),
	   array('maj_tables', array('spip_members', 'spip_associations', 'spip_categories_of_professional', 'spip_commitees', 'spip_conferencies','spip_executive_bodies','spip_membership','spip_members_associations', 'spip_members_categories_of_professional','spip_members_commitees','spip_members_council','spip_members_type', 'spip_members_conferencies'))
       );
       
  $maj['1.0.2']  = array(
    array('sql_alter','TABLE spip_members CHANGE incommitee incommitee enum(\'Yes\',\'No\',\'Com\')  NOT NULL DEFAULT \'No\'')
    );
  $maj['1.0.5']  = array(
    array('sql_alter','TABLE spip_members CHANGE ohim ohim varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT \'\'')
    );
  $maj['1.0.6']  = array(
    array('sql_alter','TABLE spip_members CHANGE Last_modified last_modified timestamp NOT NULL')
    ); 
  $maj['1.1.1']  = array(
    array('maj_tables', array('spip_members')),
    array('sql_updateq','spip_members',array('statut'=>'accepte')),    
    );        
	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Intranete Ecta.
 * 
 * Vous devez :
 *
 * - nettoyer toutes les données ajoutées par le plugin et son utilisation
 * - supprimer les tables et les champs créés par le plugin. 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function ecta_vider_tables($nom_meta_base_version) {
	# quelques exemples
	# (que vous pouvez supprimer !)
	# sql_drop_table("spip_xx");
	# sql_drop_table("spip_xx_liens");

	sql_drop_table("spip_members");
	sql_drop_table("spip_associations");
	sql_drop_table("spip_categories_of_professional");
	sql_drop_table("spip_commitees");
	sql_drop_table("spip_conferencies");

	# Nettoyer les versionnages et forums
	sql_delete("spip_versions",              sql_in("objet", array('member', 'association', 'category', 'commitee', 'conference')));
	sql_delete("spip_versions_fragments",    sql_in("objet", array('member', 'association', 'category', 'commitee', 'conference')));
	sql_delete("spip_forum",                 sql_in("objet", array('member', 'association', 'category', 'commitee', 'conference')));

	effacer_meta($nom_meta_base_version);
}

?>