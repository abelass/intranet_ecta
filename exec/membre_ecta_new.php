<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');

function cp($long_pass)
{
	$consonnes = "bcdfgjklmnprstv";
	$voyelles = "aeiou";

	$mdp='';
	for ($i=0; $i < $long_pass; $i++)
	{
		 /* L'op�rateur % permet le changement entre voyelle et consonne */
		 if (($i % 2) == 0)
		 {
			$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
		 }
		 else
		 {
			$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
		 }
	 }

	 return $mdp;
 } 

function exec_membre_ecta_new(){
	global $connect_statut, $connect_id_auteur;
	spip_query("SET NAMES 'utf8'",'ectamembersdev');
	spip_query("SET NAMES 'utf8'");

	if(_request('inmembernumber')){
		$pass=_request('inpassword') ? _request('inpassword') : cp(8);
		$login=addslashes(_request('inmembernumber'));
		
		if (!$login) die('You must give a login. Please go back and correct your form.');
		// TODO : commencer par v�rifier si un membre existe
		// TODO : charger_fond pour un formulaire (ou au min. copier/coller celui de l'�dition)
		
		$reponse = spip_query('desc ecta_members','ectamembersdev');
		while($result = spip_fetch_array($reponse))
			$r[] = $result['Field'];
		
		foreach($_POST as $k=>$v) {
			if (in_array($k,$r)) 
				$maj[$k] = "$k = '".addslashes($v)."'";
			if (in_array(substr($k,2),$r)) 
				$maj[substr($k,2)] = substr($k,2)." = '".addslashes($v)."'";
		}
		
		$sql = array('id_auteur' => '', 
				'nom' => addslashes(_request('insuffixe')." "._request('inlastname')." "._request('infirstname')), 
				'bio' => '', 
				'email' => addslashes(_request('inemail')), 
				'nom_site' => '', 
				'url_site' => '', 
				'login' => $login, 
				'pass' => md5( '1545607746460151d1d63984.51604272".$pass."' ) , 
				'low_sec' => '', 
				'statut' => '6forum', 
				'maj' =>  date('Y-m-d H:i:s'), 
				'pgp' => '', 
				'htpass' => '', 
				'en_ligne' => '', 
				'alea_actuel' => '1545607746460151d1d63984.51604272', 
				'alea_futur' => '157799821346015be7c75233.74847129', 
				'prefs' => 'a:1:{s:3:\"cnx\";s:0:\"\";}', 
				'cookie_oubli' => '', 
				'source' => 'spip', 
				'lang' => 'en');
				
		$id_auteur=sql_insertq('spip_auteurs', $sql);
		
		if (!$id_auteur) die('Probleme lors de l\'ajout du membre spip. Consultez les logs');
		
		if (isset($maj['seq'])) unset($maj['seq']);
		
		spip_query("insert into ecta_members(id_auteur) values ($id_auteur)",'ectamembersdev');
		$q = spip_query("select seq FROM ecta_members where id_auteur=$id_auteur",'ectamembersdev');
		$id_member = spip_fetch_array($q);
		spip_query("UPDATE ecta_members SET ".implode(',',$maj)." WHERE seq=".($id_member['seq']),'ectamembersdev');

		// SPIP-Liste : Abonnement � la liste "membres" (id = 4)
		$sql = array('id_auteur' => $id_auteur,'id_liste'=>4,'statut'=>'valide','format'=>'html');
		sql_insertq('spip_auteurs_listes', $sql);
		
		header("Location: ?exec=membre_ecta_edit&seq=".($id_member['seq']));
		exit();
	}
		$commencer_page = charger_fonction('commencer_page', 'inc');
		echo $commencer_page('MEMBERS DIRECTORY - ADMINISTRATION', "naviguer", "articles", $id_rubrique);

		echo debut_gauche();
			echo pipeline('affiche_gauche',array('args'=>array('exec'=>'membres_page'),'data'=>''));
			echo debut_boite_info();
				echo '<p class="style1">MEMBERS DIRECTORY<br>ADMINISTRATION</p><p class="style1">INSERT A NEW MEMBER</li>';
			echo fin_boite_info();
			echo debut_raccourcis();
       echo '<a href="?exec=membre_ecta_list"><b><img src="'._DIR_PLUGIN_ECTA.'img_pack/back.png" alt="retour" align="absmiddle"> Retour</b></a>';
			echo fin_raccourcis();
		echo debut_droite();
		
		
		
		?>
				
				<link type="text/css" href="<?php echo find_in_path('css/custom-theme/ui.all.css'); ?>" rel="stylesheet" />
				<script type="text/javascript" src="<?php echo find_in_path('js/jquery-ui-1.6.custom.min.js'); ?>"></script>


		<style>
			#contenu {
				font-size:10px;
			}
			.formulaire_spip li fieldset {
				border:0;
			}
			.formulaire_spip ul ul input.text, .formulaire_spip ul ul input.password, .formulaire_spip ul ul textarea, .formulaire_spip ul ul select  {
				width:190px!important;
			}
			.ui-tabs .ui-tabs-hide {
			     display: none;
			}
			.ui-tabs-panel {
				padding:0;
				border-top:0;
			}
	.ui-tabs-nav ul.nav li {
		text-align:center;
	}
	.ui-tabs-nav ul.nav li a {
		padding:0.5em 0;
		width:98px;
	}
		</style>

		<form action="" method="post" name="form1" class="style3" id="form1">

			<div id="tabs">
				<ul class="nav">
					<li><a href="#general"><span>General</span></a></li>
					<li><a href="#membership"><span>Membership</span></a></li>
					<li><a href="#professional"><span>Professional</span></a></li>
					<li><a href="#conferencies"><span>Conferencies</span></a></li>
					<li><a href="#cotisation"><span>Cotisation</span></a></li>
				</ul>

				<div id="general" class="formulaire_spip">
					<ul>
						<li>
							<label>Member number</label> <input name="inmembernumber" type="text" class="text" id="inmembernumber" value="">
						</li>

						<li>
							<label>Details</label>
							<fieldset>

								<ul>
									<li>
										<label>Gender</label> 
										<input name="gender" type="radio" value="M"> M
										&nbsp;&nbsp;&nbsp;
										<input name="gender" type="radio" value="F"> F					
									</li>
									<li>
										<label>Title</label> <input name="title" type="text" class="text"id="insuffixe" value="">
									</li>
									<li>
										<label>First name</label> <input name="name" type="text" class="text"id="inlastname" value="">
									</li>
									<li>
										<label>Family name</label> <input name="surname" type="text" class="text"id="infirstname" value="">
									</li>
									<li>
										<label>Birth date</label> <input name="birthdate" type="text" class="text"id="birthdate" value="">
									</li>
									<li>
										<label>Email</label> <input name="inemail" type="text" class="text"id="inemail" value="" size="50">
									</li>
								</ul>
							</fieldset>
						</li>

						<li>
							<label>Login</label>
							<fieldset>
								<ul>
									<li>
										<label>Login</label> <input name="inlogin" type="text" class="text"id="inlogin" value="">
									</li>
									<li>
										<label>Password</label> <input name="inpassword" type="text" class="text"id="inpassword" value="">
									</li>
								</ul>
							</fieldset>
						</li>

						<li>
							<strong>Member shown in member directory ?</strong> <input name="shown" type="checkbox" checked id="shown" value="1" >
						</li>
					

					</ul>

					<p class='boutons_formulaire'>
						<input type="submit" name="Submit" value="Submit">
					</p>

				</div><!-- general -->



			</div>

		</form>


		<p align="center">
			<a href="?exec=membre_ecta_list">Back to the main menu</a>
		</p>
		
		<?php
		
		
		echo fin_gauche(); 
				?>

		<script type="text/javascript">
		   $("#tabs").tabs()
		</script>

		<?php

		echo fin_page();
}

?>
