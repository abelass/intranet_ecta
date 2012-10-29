<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function exec_membre_import_csv(){
	global $connect_statut, $connect_id_auteur;
	
	if (_request('maintenance')) {
		$q = spip_query("select user_name, forgotten_password_code from fa_user ");
		while ($r = spip_fetch_array($q)) {
			echo '1';
			spip_query("update `intranet_membres` set code_init = '".$r['forgotten_password_code']."' where matricule = '".$r['user_name']."'");
		}
		die('ok');
	}
	
	$sql = spip_query("select * from intranet_codes_equipes where nom = '"._request('role')."'");
	$r = spip_fetch_array($sql);
	$id_equipe = $r['id_code_equipe'];
	if (!$r) die('section inconnue');
	
	$sql = "SELECT * from fa_user JOIN fa_user_profile ON fa_user.id = fa_user_profile.id WHERE role = '"._request('role')."'";
	$q = spip_query($sql);
	
	while ($row = spip_fetch_array($q)) {

		$sql = "INSERT INTO intranet_membres (
              id_membre, matricule,libelle,nom1,position,institution, prenom,mail,code_init) 
            VALUES ('','".addslashes(utf8_encode($row['user_name']))."', '".addslashes(str_replace('_',' ',utf8_encode($row['name'])))."', '".addslashes(str_replace('_',' ',utf8_encode($row['lastname'])))."','','".addslashes(utf8_encode($row['formations']))."','".addslashes(str_replace('_',' ',utf8_encode($row['firstname'])))."','".addslashes(utf8_encode($row['email']))."','".$row['pwdenclair']."')";

		$res = mysql_query($sql);
		$id_membre = mysql_insert_id();
		echo "membre : $id_membre =>";

		$sql = spip_query("insert into intranet_codes_equipes_membres(id_code_equipe,id_membres) VALUES($id_equipe,$id_membre)");
		$sql = spip_query("insert into intranet_codes_origines_membres(id_code_origine,id_membres) VALUES(1,$id_membre)");
		
	
	 $sql = "INSERT INTO `spip_auteurs` ( `id_auteur` , `nom` , `bio` , `email` , `nom_site` , `url_site` , `login` , `pass` , `low_sec` , `statut` , `maj` , `pgp` , `htpass` , `en_ligne` , `imessage` , `messagerie` , `alea_actuel` , `alea_futur` , `prefs` , `cookie_oubli` , `source` , `lang` , `extra` )VALUES ('', '".addslashes(utf8_encode($row['name']))."', '', '".$row['email']."', '', '', '".$row['user_name']."', MD5( '1545607746460151d1d63984.51604272".$row['pwdenclair']."' ) , '', '6forum', NOW( ) , '', '', NOW( ), '', '', '1545607746460151d1d63984.51604272', '157799821346015be7c75233.74847129', 'a:1:{s:3:\"cnx\";s:0:\"\";}', '', 'spip', 'fr', '');";
		$res=spip_query($sql);
		$id_auteur=spip_insert_id();
		
		spip_query("update intranet_membres set id_auteur ='".$id_auteur."' where id_membre = ".$id_membre." ");
		$res=spip_query($sql);
    spip_query("insert into spip_zones_auteurs values ('1','$id_auteur');");
		spip_query("insert into spip_zones_auteurs values ('92','$id_auteur');");
		
		echo "auteur : $id_auteur";

	}
	die($sql);
	
/*		$query = ("SELECT m.nom1,m.prenom,m.libelle,m .mail, m.code_init, s.login FROM intranet_membres m,spip_auteurs s  WHERE m.mail NOT LIKE '' AND m.id_auteur NOT LIKE '' AND m.code_init NOT LIKE '' AND m.id_auteur NOT LIKE '0' AND s.id_auteur=m.id_auteur" );
		$result = mysql_query($query) or die("Could not run query.");
		while ($row = mysql_fetch_assoc($result)) {
					
					$html="<p>Cher membre de l'ADIC,</p><p>Le nouvel Intranet est &agrave; pr&eacute;sent finalis&eacute; et vous pouvez d&egrave;s aujourd'hui y avoir acc&egrave;s en reprenant le login et le mot de passe suivants:</p>";
					$html.="<b>$row[libelle] $row[nom1] $row[prenom]</b><br />";
					$html.="<b>Login :</b> $row[login]<br />";
					$html.="<b>Mot de passe :</b> $row[code_init]";
					$html.="<p>D&egrave;s que vous serez entr&eacute;s dans l'intranet, vous pourrez vous choisir un autre mot de passe si vous le souhaitez.<br />N'h&eacute;sitez pas &agrave; prendre contact avec l'ADIC si vous rencontrez des probl&egrave;mes.</p><p>Cordialement,<br />l'ADIC.<br /><a href=\"http://www.adic.be\">www.adic.be</a></p>";
	
					$headers ='From: "ADIC"<A.D.I.C@skynet.be>'."\n";
     			$headers .='Reply-To: A.D.I.C@skynet.be'."\n";
     			$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n";
     			$headers .='Content-Transfer-Encoding: 8bit';
					
					mail($row[mail], 'ADIC - Intranet', $html, $headers); 
					echo "<b>Login :</b> $row[login]<br />";
	
  	}
		
*/	
	
}

?>
