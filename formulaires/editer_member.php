<?php
/**
 * Gestion du formulaire de d'édition de member
 *
 * @plugin     Intranet Ecta
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Ecta\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $seq
 *     Identifiant du member. 'new' pour un nouveau member.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un member source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du member, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
function formulaires_editer_member_identifier_dist($seq='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
  return serialize(array(intval($seq)));
}

/**
 * Chargement du formulaire d'édition de member
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $seq
 *     Identifiant du member. 'new' pour un nouveau member.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un member source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du member, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_member_charger_dist($seq='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
  $valeurs = formulaires_editer_objet_charger('member',$seq,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
  //Formulaire public
  if(_request('exec')){
	$valeurs['espace']='prive';
	$valeurs['tab']=_request('tab');
	$statut='accepte';        
  }
  //Formulaire privé   
  else {
	$valeurs['espace']='public';  
	$valeurs['taxable']=''; 
	$valeurs['art_92_93']=''; 
	$valeurs['retired_diclaimer']='';
	$valeurs['categories_of_professional_other']='';  
	$statut='application';                                                                        
  }
  $valeurs['categories_of_professional']='';  
  $valeurs['associations']=_request('associations')?_request('associations'):(isset($valeurs['associations'])?$valeurs['associations']:array());    
  //$valeurs['id_commitee_role']=_request('id_commitee_role');    
  $valeurs['_hidden'].='<input type="hidden" value="'._request('tab').'" name="tab">';
  $valeurs['_hidden'].='<input type="hidden" value="'. $valeurs['espace'].'" name="espace">'; 
  $valeurs['_hidden'].='<input type="hidden" value="'. $statut.'" name="statut">';      
  
  if(intval($valeurs['id_auteur']))$valeurs['_hidden'].='<input type="hidden" value="'. $valeurs['id_auteur'].'" name="id_auteur">';  
  if(isset($valeurs['membernumber']))$valeurs['_hidden'].='<input type="hidden" value="'. $valeurs['membernumber'].'" name="membernumber">';  
  if($valeurs['name'])$valeurs['_hidden'].='<input type="hidden" value="'. $valeurs['name'].'" name="name">';
  if($valeurs['surname'])$valeurs['_hidden'].='<input type="hidden" value="'. $valeurs['surname'].'" name="surname">';    
  return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de member
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $seq
 *     Identifiant du member. 'new' pour un nouveau member.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un member source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du member, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_member_verifier_dist($seq='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
  $login=_request('login');
  $id_auteur=_request('id_auteur');
  $espace=_request('espace');
  $erreurs=array();
  
  if($login){
	$where=array('login='.sql_quote( $login));
	if($id_auteur)$where[]='id_auteur!='.$id_auteur;
	if ($login=sql_getfetsel('login','spip_auteurs',$where)) $erreurs['login']="This login already exists";
  }
  //Formulaire public
  if($espace=='prive')$obligatoire=array('membernumber','name','surname');
  //Formulaire prive
  else {
	$obligatoire=array('name','surname','email','practicein','company','addr1','addr4','addr5','country','vat_number','membertype','nationality');
	if(!_request('categories_of_professional_other'))array_push($obligatoire,'categories_of_professional');
  }
  
  
  $erreurs=array_merge($erreurs,formulaires_editer_objet_verifier('member',$seq,$obligatoire));
  
  return $erreurs;
}

/**
 * Traitement du formulaire d'édition de member
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $seq
 *     Identifiant du member. 'new' pour un nouveau member.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un member source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du member, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_member_traiter_dist($seq='new', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
  //l'acien script utilisait $sequp
  $sequp=$seq;
  $id_auteur=_request('id_auteur');
  $email=_request('email');
  $espace=_request('espace');
  $cat=_request('categories_of_professional');
  $association=_request('associations');
  
  //Formulaire Privée
  if($espace=='prive'){
	//Traitement spip_auteurs
	if(intval($seq)){
	  $valeurs=array();
	  if($login=_request('login'))$valeurs['login']=$login;
	  if(_request('name') OR _request('surname')){
		$valeurs['nom'] = (_request('title')?_request('title').' ':'')._request('name').' '._request('surname');
		if (!trim($valeurs['nom'])) $valeurs['nom'] = '-';
	  }
	  
	  if($email=_request('email'))$valeurs['email']=$email;
	  if (_request('password')) {
		$valeurs['pass'] = md5('1545607746460151d1d63984.51604272'._request('password'));
		$valeurs['alea_actuel'] = '1545607746460151d1d63984.51604272';
	  }
	  sql_updateq('spip_auteurs',$valeurs,'id_auteur='.$id_auteur);
	  
	}
	
	
	
	/* Confs */
	if($_POST['spring_conferences']){
	  spip_query("delete from spip_members_conferencies where id_member='$sequp'");
	  if (isset($_POST['spring_conferences']))
		foreach ($_POST['spring_conferences'] as $key => $value) {
		spip_query("insert into spip_members_conferencies(id_member,id_conference,participation) VALUES('$sequp','$key','$value')");
	  }
	  if (isset($_POST['autumn_council']))
		foreach ($_POST['autumn_council'] as $key => $value) {
		spip_query("insert into spip_members_conferencies(id_member,id_conference,participation) VALUES('$sequp','$key','$value')");
	  }            
	}
	
	
	/*Comitees*/        
	$val_start_date=_request('start_date');
	$val_end_date=_request('end_date');     
	$id_commitee_role=_request('member_role'); 
	if(is_array($val_start_date)){
	  foreach ($val_start_date as $id_commitee =>$start) {
		$end=$val_end_date[$id_commitee];   
		if($val_end_date[$id_commitee]>0)$end=$val_end_date[$id_commitee];   
		else $end=0000;             
		if(isset($start['new']) AND $start['new']>0){
		  sql_insertq('spip_members_commitees',array('id_member'=>$sequp,'id_commitee'=>$id_commitee,'start_date'=>$start['new'].'-01-01','end_date'=>$end['new'].'-01-01','id_commitee_role'=>$id_commitee_role[$id_commitee]['new']));
		}
		else{
		  foreach($start AS $id_membership=>$start_date){
			if($start_date>0){
			  $end_date=$end[$id_membership].'-01-01';
			  sql_updateq('spip_members_commitees',array('start_date'=>$start_date.'-01-01','end_date'=>$end_date,'id_commitee_role'=>$id_commitee_role[$id_commitee][$id_membership]),'id_membership='.$id_membership);
			}
		  }
		}
	  }  
	}   
	
	
	/*Councils*/
	
	$council_statut=_request('council_statut');
	$council_start_date=_request('council_start_date');        
	$council_end_date=_request('council_end_date'); 
	if(is_array($council_start_date)){
	  foreach ($council_start_date AS $id_membership_council=>$start_date){
		if( $id_membership_council =='new' AND $start_date>0){
		  $valeurs=array(
			'seq'=>$sequp,
			'end_date'=>$council_end_date['new'].'-01-01',
			'start_date'=>$start_date.'-01-01',
			'statut'=>$council_statut['new']?$council_statut['new']:'Yes');
		  sql_insertq('spip_members_council',$valeurs);
		}
		elseif($start_date>0){
		  $valeurs=array(
			'seq'=>$sequp,
			'statut'=>$council_statut[$id_membership_council],
			'end_date'=>$council_end_date[$id_membership_council].'-01-01',
			'start_date'=>$start_date.'-01-01',
			'statut'=>$council_statut[$id_membership_council]?$council_statut[$id_membership_council]:'Yes'
		  );
		  sql_updateq('spip_members_council',$valeurs,'id_membership_council='.$id_membership_council);
		}
	  }
	}
	
	/* association */
	if($association) sql_delete('spip_members_associations','id_member='.$sequp);
	
	/* categories_of_professional */
	if ($cat) sql_delete('spip_members_categories_of_professional','id_member='.$sequp);
	
	$ch = array();  
	
	if($seq){
	  $aut=sql_fetsel('*','spip_members','seq='.$seq);
	  foreach($aut AS $key=>$val){ 
		if(($req=_request($key) AND _request($key)!=$val) OR ($req=_request('in'.$key) AND _request('in'.$key)!=$val))$ch[$key]=$req;               
	  }
	}
  }
  //Formulaire Public
  else{
	$notifications = charger_fonction('notifications', 'inc');
	$categories_of_professional=_request('categories_of_professional');
	if($categories_of_professional_other=_request('categories_of_professional_other'))$cat=array(14);
	elseif($categories_of_professional) $cat=array($categories_of_professional);	
  }
  
  //Traiter le formulaire
  $res=formulaires_editer_objet_traiter('member',$seq,'',$lier_trad,$retour,$config_fonc,$row,$hidden);
  
  if(count($ch)>0 AND $espace=='prive'){
	  //actualisation mailchimp
	  
	  $flux=array(
		'data'=>array('id_auteur'=>$id_auteur),
	  );
	  
	  $email=$aut['email']?$aut['email']:$email;
	 // if(!$email)$email=$aut['email'];
	  $flux['args']['args'][4]['email']=$email;  
	  spip_log('actualisation profil intranet','ecta');	  
	  
	  
	  include_spip('sclp_fonctions'); 
	  inc_editer_auteur_traiter_listes($flux);
	  
	 if(intval($seq)){
		$definitions = charger_fonction('definitions', 'inc');
		include_spip('inc/session');
		$id_auteur_session=session_get('id_auteur');    
		$nom_session=sql_getfetsel('nom','spip_auteurs','id_auteur='.$id_auteur_session);
		if(!$nom_session)$nom_session='name not detected ,id_auteur'.$id_auteur_session;
		$message_mail .="Last modification done from the backend on : ".date('d-m-Y H:i:s')."  by ".$nom_session."\n\n";
		$message_mail .="Modification - Member number:".$aut['membernumber']."\n\n";
		$message_mail .="The following modifications have been made:\n\n";  
		
		foreach($ch as $key=>$val){
		  if(is_array($val))$val=implode(',',$val);
		  $message_mail .= $key.': '.$val."\n\n";
		}
		
		$envoyer_mail = charger_fonction('envoyer_mail','inc');
		$envoyer_mail($definitions('dest_admin'), "Modification of a public profile (Nr ".$_POST['membernumber'].")", $message_mail);
		
	  }                      
	}
  
  if(!intval($sequp))$sequp= $res['seq'];
  
  
  /* categories_of_professional */
  if (is_array($cat)){
	foreach ($cat as $value) {
	  sql_insertq('spip_members_categories_of_professional',array('id_member'=>$sequp,'id_category'=>$value));
	}
  }
  
  /* association */
  if(is_array($association)){
	foreach ($association as $value) {
	  sql_insertq('spip_members_associations',array('id_member'=>$sequp,'id_association'=>$value));
	}
	
  }
  
  if($espace=='public'){
	// On récupère les entrées du formulaire pour l'envoi des mail
	$champs=formulaires_editer_member_charger_dist();
	$contexte=array();
	$exclus=array('espace','statut');
	foreach($champs as $champ=>$valeur){
	  if(!in_array(trim($champ),$exclus)){
		if($champ=='categories_of_professional' AND $categories_of_professional=_request($champ))
		  $contexte[$champ]=supprimer_numero(sql_getfetsel('title','spip_categories_of_professional','id_category='.$categories_of_professional));
		elseif($champ=='associations' AND $associations=_request($champ)){
		  $sql=sql_select('title','spip_associations','id_association IN ('.implode(', ',$associations).')');
		  $ass=array();
		  while($data=sql_fetch($sql)){
			spip_log($data,'teste');
			$ass[]=supprimer_numero($data['title']);
		  }
		  $contexte[$champ]=implode(',',$ass);
		}
		else$contexte[$champ]=_request($champ);
	  }
	  else unset($champ);
	}
	// envoi des mails
	$notifications('member_application',$sequp,$contexte);
  }
  
  return $res;
}
?>
