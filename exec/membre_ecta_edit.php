<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');

function exec_membre_ecta_edit(){
    

    
    /*Scripts de migration à priorio plus utile
         $sql=sql_select('id_member,id_commitee','ecta_members_commitees');
      $count=0;
    while($data=sql_fetch($sql)){
        $count++;
        sql_update('ecta_members_commitees',array('id_membership'=>$count),'id_member='.$data['id_member'].' AND id_commitee='.$data['id_commitee']);
    }
    $sql=sql_select('*','ecta_members');
      $count=0;
      $champs_coms=array(1=>'chaircommitee',2=>'vicechaircommitee',3=>'secretarycommitee');
    while($data=sql_fetch($sql)){
        foreach($champs_coms as $id_com_mem => $com) {
             if($id=$data['id_'.$com]>0){
                 $valeurs=array(
                 'id_commitee_role'=>$id_com_mem,
                 'id_commitee'=>$id, 
                 'id_member'=>$data['seq'], 
                 'start_date'=>$data['from_'.$com],                                 
                 );
                 echo serialize($valeurs);
                 sql_insertq('ecta_members_commitees',$valeurs);
           
       }
        }  
    }
        $sql=sql_select('*','ecta_members');
      $count=0;
      $champs_coms=array(1=>'chaircommitee',2=>'vicechaircommitee',3=>'secretarycommitee');
    while($data=sql_fetch($sql)){
        foreach($champs_coms as $id_com_mem => $com) {
             if($data['id_'.$com]>0){
                 $valeurs=array(
                 'id_commitee_role'=>$id_com_mem,
                 'id_commitee'=>$data['id_'.$com], 
                 'id_member'=>$data['seq'], 
                 'start_date'=>$data['from_'.$com],                                 
                 );
                 sql_insertq('ecta_members_commitees',$valeurs);
           
       }
        }  
    }
    $sql=sql_select('*','ecta_members');

    while($data=sql_fetch($sql)){
        if($data['councilmem']=='Yes' OR $data['councilmem']=='Com'){
                 $valeurs=array(
                 'seq'=>$data['seq'], 
                 'statut'=>$data['councilmem'],                                 
                 );
                 echo serialize($valeurs);
                 sql_insertq('ecta_members_council',$valeurs);
             }
           }
*/



    
	global $connect_statut, $connect_id_auteur;
	spip_query("SET NAMES 'utf8'",'ectamembersdev');
	spip_query("SET NAMES 'utf8'");
	$message_maj = $message_err = '';
    
	if(_request('delete'))sql_delete('ecta_members_commitees','id_membership='._request('delete'));
	if(_request('delete_council'))sql_delete('ecta_members_council','  id_membership_council='._request('delete_council'));    
	if (_request('inlogin'))
	{
		$sequp = (int)_request('sequp');
		
		/* SPIP */
		$sql="SELECT * FROM ecta_members where seq='$sequp' ";
		$reponse = spip_query($sql);
		$aut = spip_fetch_array($reponse);		
		
		$row['nom'] = (_request('title')?_request('title').' ':'')._request('name').' '._request('surname');
		if (!trim($row['nom'])) $row['nom'] = '-';
		$row['email'] = _request('inemail');
		
		$sql="SELECT id_auteur FROM spip_auteurs where login = ".sql_quote(trim(_request('inlogin')))." AND id_auteur != ".$aut['id_auteur'];
		//SELECT id_auteur FROM spip_auteurs where login = 'Smideberga' AND id_auteur != 8703
		
		$check_login = spip_query($sql);
		if (!spip_num_rows($check_login)) {
			sql_updateq('spip_auteurs',array('login'=>trim(_request('inlogin'))),'id_auteur = '.$aut['id_auteur']);
		} else {
			$message_err = "Error : this login already exists";
		}
		
		if (_request('inpassword')) {
			$row['pass'] = md5('1545607746460151d1d63984.51604272'._request('inpassword'));
			$row['alea_actuel'] = '1545607746460151d1d63984.51604272';
		}
		
		sql_updateq('spip_auteurs',$row,'id_auteur = '.$aut['id_auteur']);
					
		/* ECTA_members */
		$reponse = spip_query('desc ecta_members');
		while($result = spip_fetch_array($reponse))
			$r[] = $result['Field'];

		foreach($_POST as $k=>$v) {
			if (in_array($k,$r)) 
				$maj[$k] = "$k = '".addslashes(trim($v))."'";
			if (in_array(substr($k,2),$r)) 
				$maj[substr($k,2)] = substr($k,2)." = '".addslashes(trim($v))."'";
		}
		
		$maj['datestamp']  = "datestamp = '".date("d-m-Y H:i:s")."'";
		if (!_request('listed_in_dir')) $maj['listed_in_dir'] = "listed_in_dir = 'No'";
		
		spip_query("update ecta_members set ". implode(',',$maj) ." where seq='$sequp'",'ectamembersdev');
        
		
		/* Confs */
		spip_query("delete from ecta_members_conferencies where id_member='$sequp'",'ectamembersdev');
		if (isset($_POST['spring_conferences']))
			foreach ($_POST['spring_conferences'] as $key => $value) {
				spip_query("insert into ecta_members_conferencies(id_member,id_conference,participation) VALUES('$sequp','$key','$value')",'ectamembersdev');
			}
		if (isset($_POST['autumn_council']))
			foreach ($_POST['autumn_council'] as $key => $value) {
				spip_query("insert into ecta_members_conferencies(id_member,id_conference,participation) VALUES('$sequp','$key','$value')",'ectamembersdev');
			}
			
		/* committees */
		/*spip_query("delete from ecta_members_commitees where id_member='$sequp'",'ectamembersdev');*/
		$val_start_date=_request('start_date');
		$val_end_date=_request('end_date');		
        $id_commitee_role=_request('id_commitee_role');
			foreach ($val_start_date as $id_commitee =>$start) {
				$end=$val_end_date[$id_commitee];				
				if(isset($start['new']) AND $start['new']>0){
				    sql_insertq('ecta_members_commitees',array('id_member'=>$sequp,'id_commitee'=>$id_commitee,'start_date'=>$start['new'].'-01-01','end_date'=>$end['new'].'-01-01','id_commitee_role'=>$id_commitee_role[$id_commitee]['new']));
				}
                else{
                    foreach($start AS $id_membership=>$start_date){
                        if($start_date>0){
                            $end_date=$end[$id_membership].'-01-01';
                            sql_updateq('ecta_members_commitees',array('start_date'=>$start_date.'-01-01','end_date'=>$end_date,'id_commitee_role'=>$id_commitee_role[$id_commitee][$id_membership]),'id_membership='.$id_membership);
                            }
                        }
                }
			}				
		
        /*Councils*/
        $council_statut=_request('council_statut');
        $council_start_date=_request('council_start_date');        
        $council_end_date=_request('council_end_date'); 
      
        foreach ($council_start_date AS $id_membership_council=>$start_date){
            if( $id_membership_council =='new' AND $start_date>0){
                $valeurs=array(
                'seq'=>$sequp,
                'end_date'=>$council_end_date['new'].'-01-01',
                'start_date'=>$start_date.'-01-01',
                'statut'=>$council_statut['new']?$council_statut['new']:'Yes');
                sql_insertq('ecta_members_council',$valeurs);
                }
            elseif($start_date>0){
                $valeurs=array(
                    'seq'=>$sequp,
                    'statut'=>$council_statut[$id_membership_council],
                    'end_date'=>$council_end_date[$id_membership_council].'-01-01',
                    'start_date'=>$start_date.'-01-01',
                     'statut'=>$council_statut[$id_membership_council]?$council_statut[$id_membership_council]:'Yes'
                    );
                sql_updateq('ecta_members_council',$valeurs,'id_membership_council='.$id_membership_council);
            }
        }
        
              
			/* association */
			spip_query("delete from ecta_members_associations where id_member='$sequp'",'ectamembersdev');
			if (isset($_POST['associations']))
				foreach ($_POST['associations'] as $value) {
					spip_query("insert into ecta_members_associations(id_member,id_association) VALUES('$sequp','$value')",'ectamembersdev');
				}

			/* categories_of_professional */
			spip_query("delete from ecta_members_categories_of_professional where id_member='$sequp'",'ectamembersdev');
			if (isset($_POST['categories_of_professional']))
				{foreach ($_POST['categories_of_professional'] as $value) {
					spip_query("insert into ecta_members_categories_of_professional(id_member,id_category) VALUES('$sequp','$value')",'ectamembersdev');
				}
				}
				
				
		if (!$message_err){
			$ch = array();	

			foreach($aut AS $key=>$val){ 
				if(($req=_request($key) AND _request($key)!=$val) OR ($req=_request('in'.$key) AND _request('in'.$key)!=$val))$ch[$key]=$req;				
			}
			
			if(count($ch)>0){
			//actualisation mailchimp
			
				spip_log('actualisation profil intranet','sclp');
				$flux=array(
					'data'=>array('id_auteur'=>$aut['id_auteur'])
					);
                    

				$flux['args']['args'][4]['email']=$aut['email'];	
				
				$traitement=charger_fonction('editer_auteur_traiter_listes','inc');
				$flux=$traitement($flux);
				
				
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
   			//$envoyer_mail($GLOBALS['meta']['email_webmaster'], "Modification of a public profile (Nr ".$_POST['membernumber'].")", $message_mail, true);
   			//$envoyer_mail('websolutions@mychacra.net', "Modification of a public profile (Nr ".$_POST['membernumber'].")", $message_mail, true);
   			 $envoyer_mail('ecta@ecta.org', "Modification of a public profile (Nr ".$aut['membernumber'].")", $message_mail, true);
			}				
			spip_log($message_mail,'teste');
			
			 $message_maj = "The member has been successfully updated";
		 }
	}

	$commencer_page = charger_fonction('commencer_page', 'inc');
	echo $commencer_page('MEMBERS DIRECTORY - ADMINISTRATION', "naviguer", "articles", $id_rubrique);

		$sql="SELECT * FROM ecta_members where seq = '".addslashes(_request('seq'))."'";
		$reponse = spip_query($sql, 'ectamembersdev');
		$results = spip_fetch_array($reponse);
		
		if (!spip_num_rows($reponse)) die('Pb avec le membre '._request('seq'));
		
		$seq = (int)_request('seq');
		
		foreach($results as $k=>$v){
			// On prévoit le cas où il y a eu unne maj via le formulaire (on est passé en UTF8)
			$$k = $v; //htmlentities($v,ENT_QUOTES,'UTF-8');
			${"in$k"} = $v; //htmlentities($v,ENT_QUOTES,'UTF-8'); // Compatibilité du code
		}

		$user = sql_fetsel('login','spip_auteurs','id_auteur='.$inid_auteur);
		$inlogin = $user['login'];
		
		echo debut_gauche();
			echo pipeline('affiche_gauche',array('args'=>array('exec'=>'membres_page'),'data'=>''));
			echo debut_boite_info();
				echo '<p class="style1" style="text-align:center"><img src="'._DIR_PLUGIN_ECTA.'img_pack/admin-logo.png" alt="ECTA"></p>';
				echo '<p class="style1" style="text-align:center"><strong>MEMBERS DATABASE</strong></p>';
			echo fin_boite_info();

			if ($spip_display != 4) {
			$iconifier = charger_fonction('iconifier', 'inc');
			if ($id_auteur > 0)
				echo $iconifier('id_auteur', $id_auteur, 'auteur_infos');
		}

			if ($message_maj || $message_err) {

				echo "
					<style>
					.message_ok {
						padding:10px;
						background:#99FF99;
						color:green;
						font-size:14px;
						font-weight:bold;
						margin-bottom:10px;
					}
					.message_err {
						padding:10px;
						background:#FF9999;
						color:red;
						font-size:14px;
						font-weight:bold;
						margin-bottom:10px;
					}
					</style>".
					
					($message_maj ?"	<div class='message_ok'>
						 $message_maj
						</div>":"").

						($message_err ?"	<div class='message_err'>
							 $message_err
							</div>":'').
				"<script>
					/*
					 * jQuery Timer Plugin
					 * http://www.evanbot.com/article/jquery-timer-plugin/23
					 *
					 * @version      1.0
					 * @copyright    2009 Evan Byrne (http://www.evanbot.com)
					 */ 

					jQuery.timer = function(time,func,callback){
						var a = {timer:setTimeout(func,time),callback:null}
						if(typeof(callback) == 'function'){a.callback = callback;}
						return a;
					};

					jQuery.clearTimer = function(a){
						clearTimeout(a.timer);
						if(typeof(a.callback) == 'function'){a.callback();};
						return this;
					};
					
					jQuery.timer(2000,function(){jQuery('.message_ok').slideUp()})
				</script>
			";
			
		}
			echo debut_raccourcis();
       if(0 && _request('seq')){
				echo '<a href="?exec=membre_ecta_view&amp;seq='._request('seq').'"><b><img src="'._DIR_PLUGIN_ECTA.'img_pack/back.png" alt="retour" align="absmiddle"> Back to the member profile</b></a>';
				}
				else{
					echo '<a href="?exec=membre_ecta_list"><b><img src="'._DIR_PLUGIN_ECTA.'img_pack/back.png" alt="retour" align="absmiddle"> Back</b></a>';
				}
			echo fin_raccourcis();
		echo debut_droite();
		
		
		/* Confs */
		/*$q = spip_query("select id_conference from ecta_conferencies",'ectamembersdev');
				
		$spring_conferences = $autumn_council = array();
		while ($conf = spip_fetch_array($q)) {
			$q2 = spip_query("select ecta_members_conferencies.id_conference, id_member 
					from ecta_members_conferencies
					WHERE id_conference=".$conf['id_conference']." AND id_member=".$seq,'ectamembersdev');
			$value=spip_num_rows($q2);
			if ($conf['type']=='spring') $spring_conferences[$conf['id_conference']]=$conf['id_membre'];
			else $autumn_council[$conf['id_conference']]=$conf['id_membre'];
		}*/
			
		/* commities */
		/*$q = spip_query("select id_conference from ecta_commitees",'ectamembersdev');
				
		$commitee = array();
		while ($c = spip_fetch_array($q)) {
			$q2 = spip_query("select ecta_members_commitees.* 
					from ecta_members_commitees
					WHERE id_commitee=".$conf['id_commitee']." AND id_member=".$seq,'ectamembersdev');
			$value=spip_num_rows($q2);
			$commitee[$c['id_commitee']]=$value;
		}	*/	
		
		if ($datestamp == '')
		{
			$datestamp = "no datestamp available";
		}		
		
		?>
		
		<link type="text/css" href="<?php echo find_in_path('css/custom-theme/ui.all.css'); ?>" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo find_in_path('js/jquery-ui-1.6.custom.min.js'); ?>"></script>

		    
<script type="text/javascript">
    jQuery(document).ready(function(){
      
$("a .ajax >.hidden").unbind('click');
        $('.hidden').hide('fast');
        $('.switch').click(function(){
            $(this).toggleClass('open').next().toggle('fast');       
            }
            
           );
});   
</script>
				
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
	input.start_date, input.end_date, input{width:40px;}
	.formulaire_spip li.membership label{    float: none;
    margin-left: 0;
    width: auto;}
	.formulaire_spip li.membership,.formulaire_spip li.explication {padding-left:0;width:350px;
	}
	.formulaire_spip span{font-weight:bold;cursor: pointer}
	.formulaire_spip span .open{color:green}
	.formulaire_spip span .close{display:none; color:red}
	.formulaire_spip span.open .open{display:none}	
	.formulaire_spip span.open .close{display:inline} 
	small{font-size: 10px;color:#7f7f7f;display:block}
	.formulaire_spip ul ul .member_role, select.statut{width:auto !important}
</style>

<form action="" method="post" name="form1" class="style3" id="form1">
	<input name="sequp" type="hidden" value="<?php echo $seq;?>">
	<input name="act" type="hidden" value="edit">
	<input name="exec" type="hidden" value="membre_ecta_list">
		
	<div id="tabs">
		<ul class="nav">
			<li><a href="#general"><span>General</span></a></li>
			<li><a href="#membership"><span>Membership</span></a></li>
			<li><a href="#professional"><span>Professional</span></a></li>
			<li><a href="#conferencies"><span>Conferences</span></a></li>
			<li><a href="#cotisation"><span>Cotisation</span></a></li>
		</ul>
		
		<div id="general" class="formulaire_spip">
			<ul>
				<li>
					<label>Member number</label> <input name="inmembernumber" type="text" class="text"id="incompany" value="<?php echo $inmembernumber;?>">
				</li>

				<li>
					<label>Details</label>
					<fieldset>
					
						<ul>
							<li>
								<label>Gender</label> 
								<input name="gender" type="radio" value="M" <?php if ($gender == "M") { echo 'checked'; }?>> M
								&nbsp;&nbsp;&nbsp;
								<input name="gender" type="radio" value="F" <?php if ($gender == "F") { echo 'checked'; }?>> F					
							</li>
							<li>
								<label>Title</label> <input name="title" type="text" class="text" id="title" value="<?php echo $title;?>"> 
							</li>
							<li>
								<label>First name</label> <input name="name" type="text" class="text" id="inlastname" value="<?php echo $name;?>">
							</li>
							<li>
								<label>Family name</label> <input name="surname" type="text" class="text" id="infirstname" value="<?php echo $surname;?>">
							</li>
							<li>
								<label>Birth date</label> <input name="birthdate" type="text" class="text" id="birthdate" value="<?php echo $birthdate;?>">
							</li>
							<li>
								<label>Email</label> <input name="inemail" type="text" class="text"id="inemail" value="<?php echo $inemail;?>" size="50">
							</li>
						</ul>
					</fieldset>
				</li>

				<li>
					<label>Login</label>
					<fieldset>
						<ul>
							<li>
								<label>Login</label> <input name="inlogin" type="text" class="text"id="inlogin" value="<?php echo $inlogin;?>">
							</li>
							<li>
								<label>Password</label> <input name="inpassword" type="text" class="text"id="inpassword" value="<?php echo $inpassword;?>">
							</li>
						</ul>
					</fieldset>
				</li>

				<li>
					<label>Company</label> 
					<input name="incompany" type="text" class="text" id="incompany" value="<?php echo $incompany;?>">
				</li>

				<li>
					<label>Address</label>
					<fieldset>
						
						<ul>
							<li>
								<label>Address 1</label> <input name="inaddr1" type="text" class="text"id="inaddr1" value="<?php echo $inaddr1;?>" size="55">
							</li>
							<li>
								<label>Address 2</label> <input name="inaddr2" type="text" class="text"id="inaddr2" value="<?php echo $inaddr2;?>" size="55">
							</li>
							<li>
								<label>Address 3</label> <input name="inaddr3" type="text" class="text"id="inaddr3" value="<?php echo $inaddr3;?>" size="55">
							</li>
							<li>
								<label>Address 4</label> <input name="inaddr4" type="text" class="text"id="inaddr4" value="<?php echo $inaddr4;?>" size="55">
							</li>
							<li>
								<label>Address 5</label> <input name="inaddr5" type="text" class="text"id="inaddr5" value="<?php echo $inaddr5;?>" size="45">
							</li>
							<li>
								<label>Country</label>  
								<select name="incountry" id="incountry" style="width:195px">
								<option value=''>Make a choice</option>
									<?php
									
										$q = sql_select('*','spip_geo_pays','','','pays');
										while ($p = sql_fetch($q)) {
											echo "<option value='".$p['code_iso']."'";
											if ($incountry == $p['code_iso']) { echo 'selected'; }
											echo ">".$p['pays']."</option>\n";
										}
										
									?>
								</select>
							</li>
							<li>
								<label>Country Type</label>  
								<select name="countrytype" id="countrytype" style="width:195px">
									<?php
									
										$q = spip_query("select DISTINCT countrytype FROM ecta_members order by countrytype",'ectamembersdev');
											
										while($p = spip_fetch_array($q)) {
											echo "<option value='".$p['countrytype']."'";
											if ($countrytype == $p['countrytype']) { echo 'selected'; }
											echo ">".$p['countrytype']."</option>\n";
										}
										
									?>
								</select>
							</li>
							</ul>
						<li>
							<label>Nationality</label> 
							<select name="nationality" id="nationality" style="width:195px">
								<option value=''>Make a choice</option>
								<?php
								
									$q = sql_select('*','spip_geo_pays','','','pays');
									while ($p = sql_fetch($q)) {
										echo "<option value='".$p['code_iso']."'";
										if ($nationality == $p['code_iso']) { echo 'selected'; }
										echo ">".$p['pays']."</option>\n";
									}
									
								?>
							</select>
						</li>
					</fieldset>
				</li>

				<li>
					<label>Phone / Fax</label>
					<fieldset>
						
						<ul>
							<li>
								<label>Fax 1</label>
								+ <input name="infax1_pn" type="text" class="text-mini" style="width:30px;" id="infax1_pn" value="<?php echo $infax1_pn;?>">
								<input name="infax1_pl" type="text"  class="text-mini" style="width:30px;" id="infax1_pl" value="<?php echo $infax1_pl;?>">								
								<input name="infax1" type="text" class="text" id="infax1" value="<?php echo $infax1;?>">
								
							</li>
							<li>
					
								<label>Fax 2</label>
								+ <input name="infax2_pn" type="text" class="text-mini" style="width:30px;" id="infax2_pn" value="<?php echo $infax2_pn;?>">				
								<input name="infax2_pl" type="text" class="text-mini" style="width:30px;" id="infax2_pl" value="<?php echo $infax2_pl;?>">				
								<input name="infax2" type="text" class="text" id="infax2" value="<?php echo $infax2;?>">
							</li>
							<li>
								<label>Telephone 1</label>
								+ <input name="intel1_pn" type="text" class="text-mini" style="width:30px;" id="intel1_pn" value="<?php echo $intel1_pn;?>">				
								<input name="intel1_pl" type="text" class="text-mini" style="width:30px;" id="intel1_pl" value="<?php echo $intel1_pl;?>">
								<input name="intel1" type="text" class="text" id="intel1" value="<?php echo $intel1;?>">
							</li>
							<li>
								<label>Telephone 2</label>
								+ <input name="intel2_pn" type="text" class="text-mini" style="width:30px;" style="width:30px;" id="intel2_pn" value="<?php echo $intel2_pn;?>">	
								<input name="intel2_pl" type="text" class="text-mini" style="width:30px;" id="intel2_pl" value="<?php echo $intel2_pl;?>">
								<input name="intel2" type="text" class="text" id="intel2" value="<?php echo $intel2;?>">
							</li>
							<li>
								<label>Telephone 3</label>
								+ <input name="intel3_pn" type="text" class="text-mini" style="width:30px;" id="intel3_pn" value="<?php echo $intel3_pn;?>">
								<input name="intel3_pl" type="text" class="text-mini" style="width:30px;" id="intel3_pl" value="<?php echo $intel3_pl;?>">
								<input name="intel3" type="text" class="text" id="intel3" value="<?php echo $intel3;?>">
							</li>							
						</ul>
					</fieldset>
				</li>

				<li>
					<strong>Member shown in member directory ?</strong> <input name="listed_in_dir" type="checkbox" id="listed_in_dir" value="Yes" <?php if ($listed_in_dir == 'Yes') { echo 'checked'; } ?>>
				</li>
				<li>
					<strong>Last modification done on : </strong><?echo $datestamp; ?>
				</li>

			</ul>

			<p class='boutons_formulaire'>
				<input type="submit" name="Submit" value="Submit">
			</p>
			
		</div><!-- general -->
		
		<div id="membership" class="formulaire_spip">
			<ul>
				<li>
					<label>Member number</label> <?php echo $inmembernumber;?>
				</li>
				<li>
					<label>OHIM number</label> <input name="OHIM" type="text" class="text"id="ohimnumber" value="<?php echo $OHIM;?>">
				</li>							
				<li>
					<label>Member type</label> 
					<select name="inmembertype" id="inmembertype">
						<option value=''>Make a choice</option>
						<?php
						
						$q = spip_query("select *, 0+title AS num_order FROM ecta_members_type order by num_order",'ectamembersdev');
						
						while($type = spip_fetch_array($q)) {
							$type['title'] = supprimer_numero($type['title']);

							echo "<option value='{$type['id_member_type']}' ";
							if ($inmembertype == $type['id_member_type'])  echo ' selected ';
							echo ">{$type['title']}</option>";

						}
						
						?>
					</select>
				</li>

				<li class="com">
					<label>Committee member</label>
					<fieldset>
						
						<ul>

							<li>
								<label>Committee member</label> <select name="incommem" id="incommem">
									<option value=''>Make a choice</option>
									<option value="Yes" <?php if ($incommem == 'Yes') { echo 'selected'; } ?>>
										Yes
									</option>
									<option value="No" <?php if ($incommem == 'No') { echo 'selected'; } ?>>
										No
									</option>
									<option value="Com" <?php if ($incommem == 'Com') { echo 'selected'; } ?>>
										Com
									</option>
								</select>
							</li>
                            <li class="explication">
                                <small>Please specify a role and a year in the fields below. Once you have saved you will be able to add a new period by committee.</small>
                            </li>
							<?php
							$r=sql_select('*','ecta_commitee_role');
							$roles=array();
							while($rs=sql_fetch($r)){
							  $roles[$rs['id_commitee_role']]=$rs['title'];  
							}

							
							$q = spip_query("select ecta_commitees.id_commitee, ecta_commitees.title, 0+title AS num_order FROM ecta_commitees order by num_order");
							while($commitee = spip_fetch_array($q)){
							      
    							$commitee['title'] = supprimer_numero($commitee['title']);
    							$start_date=0000;    
                                $end_date=0000;
                                $select.='';	
                                $champ1='<span> <b>From:</b> </span><input class="start_date" name="start_date['.$commitee['id_commitee'].'][new]" type="text" value="'.$start_date.'"/>';
                                $champ2='<span> <b>To:</b> </span><input class="end_date"  name="end_date['.$commitee['id_commitee'].'][new]" type="text" value="'.$end_date.'"/></div>';
                                 $select='';                                				
                                foreach($roles as $id=>$title){
                                    $selected='';
                                    if($id==0)$selected='selected="selected"';		    
                                    $select.='<option value="'.$id.'" ' .$selected.'>'.$title.'</option>';                          
                                                                   
                                    }                               
                                $champ0='<div><span> <b>Role:</b> </span><select class="member_role" name="id_commitee_role['.$commitee['id_commitee'].'][new]">'.$select.'</select>';  

                                $champs='';
                                $champs.=$champ0.$champ1.$champ2;
                                echo "
                                            <li class='membership'>
                                                <label>{$commitee['title']}</label> ";                   

                                foreach($roles as $id=>$title){
                                    $sql = sql_select('*','ecta_members_commitees','id_commitee='.$commitee['id_commitee'].' AND id_member='.$seq.' AND id_commitee_role='.$id,'','id_commitee_role,start_date DESC'); 
                                    

                                     $end_tag='';
                                      $count=0;
     
                                    while($data=sql_fetch($sql)){
                              $start_date=0000;    
                                        $end_date=''; 
                                        $begin_tag='';
                                        $end_tag='';
                                        $limit=3;
                                        $count++;
     
                                        if($data['start_date']>0)$start_date=affdate($data['start_date'],'Y');
                                        if($data['end_date']>0){
                                            $end_date=affdate($data['end_date'],'Y');
                                            if( $count==1){
                                               // if($data['start_date']>0)$champs.=$champ0.$champ1.$champ2;
                                                 //$limit= 2;
                                            }
                                        }
                                        if($count==$limit){
                                            $begin_tag='<span class="switch">
                                                <span class="open">+</span>
                                                <span class="close">-</span>
                                                </span><div class="hidden">';
                                            $end_tag='</div>';
                                        }
                                        
                                        $champs.=recuperer_fond('formulaires/field_period',
                                            array(
                                            'begin_tag'=>$begin_tag,
                                            'end_tag'=>$end_tag, 
                                            'id_commitee'=>$commitee['id_commitee'],
                                            'id_membership'=>$data['id_membership'], 
                                            'id_commitee_role'=>$data['id_commitee_role'],
                                            'start_date'=>$start_date,
                                            'end_date'=>$end_date,
                                            'roles'=>$roles, 
                                            'seq'=>$seq,                                             
                                            ));
                                        }  
                              $champs.=$end_tag;
                                        
                           echo "<div>$champs</div>";
                           $champs='';
                                     }
                           echo"</li>";
    									  
                              $l_commitees[$commitee['id_commitee']] = $commitee['title'];
							     }
							?>
							
						</ul>
					</fieldset>
				</li>	
				<li>
					<label>Executive Bodies
					</label>
					<select name="executivebodies" id="executivebodies">
						<option value=''>Make a choice</option>
						<?php
						$q = spip_query("select *, 0+title AS num_order FROM ecta_executive_bodies order by num_order");
						while($executive = spip_fetch_array($q)) {
							$executive['title'] = supprimer_numero($executive['title']);

							echo "<option value='{$executive['title']}' ";
							if ($executivebodies == $executive['title'])  echo ' selected ';
							echo ">{$executive['title']}</option>";
						}
						?>
						
					</select>
				</li>
				<li><label>Council Member</label>
				    <div class="explication">
				        <small>Please specify a type and a year in the fields below. Once you have saved you will be able to add a new period.</small>
				    </div>
				<?php
                    $start_date=0000;    
                    $end_date=0000;  
                    
                   $champ0='<div><select class="statut" name="council_statut[new]">
                        <option value="">No</option>
                        <option value="Yes">Yes</option>
                        <option value="Com">Com</option>                                        
                   </select>';  
                   $champ1='<span> <b>From:</b> </span><input class="start_date" name="council_start_date[new]" type="text" value="'.$start_date.'"/>';
                   $champ2='<span> <b>To:</b> </span><input class="start_date"  name="council_end_date[new]" type="text" value="'.$end_date.'"/></div>';
                   
 
                     
                    $sql=sql_select('*','ecta_members_council','seq='.$seq,'','start_date DESC');
                    $count=0;
                                     
                    while($councils=sql_fetch($sql)){
                        $start_date=0000;    
                        $end_date=''; 
                        $begin_tag='';
                        $end_tag='';
                        $limit=3;   
                        $count++;
     
                        if($councils['start_date']>0)$start_date=affdate($councils['start_date'],'Y');
                        if($councils['end_date']>0){
                            $end_date=affdate($councils['end_date'],'Y');
                            if($count==1){
                                if($councils['start_date']>0)$champs=$champ0.$champ1.$champ2;
                                 $limit= 2;
                            }
                        }
                        if($count==$limit){
                            $begin_tag='<span class="switch">
                                <span class="open">+</span>
                                <span class="close">-</span>
                                </span><div class="hidden">';
                            $end_tag='</div>';
                        }
                        
                        $champs.=recuperer_fond('formulaires/field_period_councils',
                            array(
                            'begin_tag'=>$begin_tag,
                            'end_tag'=>$end_tag, 
                            'id_membership_council'=>$councils['id_membership_council'], 
                            'statut'=>$councils['statut'],
                            'start_date'=>$start_date,
                            'end_date'=>$end_date,
                            'seq'=>$seq
                            ));       
                             
                           }
 $champs.=$end_tag;
                           echo "<div>$champs</div>";
                          

					
					?>
				</li>
				<li>
					<label>Members of Honours
					</label>
					<select name="memberofhonour" id="memberofhonour">
						<option value=''>Make a choice</option>
						<option value="Yes" <?php if ($memberofhonour == 'Yes') { echo 'selected'; } ?>>
							Yes
						</option>
						<option value="No" <?php if ($memberofhonour == 'No') { echo 'selected'; } ?>>
							No
						</option>
					</select>
				</li>
				<li>
					<label>Past President
					</label>
					<select name="pastpresident" id="pastpresident">
						<option value=''>Make a choice</option>
						<option value="Yes" <?php if ($pastpresident == 'Yes') { echo 'selected'; } ?>>
							Yes
						</option>
						<option value="N                            echo 1;o" <?php if ($pastpresident == 'No') { echo 'selected'; } ?>>
							No
						</option>
					</select>
				</li>
				<li>
					<label>Sponsored by (1)
					</label>
					<input name="sponsoredby1" type="text" class="text"id="sponsoredby1" value="<?php echo $sponsoredby1;?>">
				</li>
				<li>
					<label>Sponsored by (2)</label>
					<input name="sponsoredby2" type="text" class="text"id="sponsoredby2" value="<?php echo $sponsoredby2;?>">
				</li>
				<li>
					<label>Date of membership
					</label>
					<input name="datemembership" type="text" class="text"id="datemembership" value="<?php echo $datemembership;?>">
				</li>
				<li>
					<label>Other Association
					</label>
					<fieldset><ul>
					<!-- select name="otherassociations" id="otherassociations">
						<option value=''>Make a choice</option>

						<?php
						
						/* $q = spip_query("select *, 0+title AS num_order FROM ecta_associations order by num_order",'ectamembersdev');
						
						while($association = spip_fetch_array($q)) {
							$association['title'] = supprimer_numero($association['title']);

							echo "<option value='{$association['title']}' ";
							if ($otherassociations == $association['title'])  echo ' selected ';
							echo ">{$association['title']}</option>";

						} */
						
						?>
						
					</select -->

							<?php
							
							$q = spip_query("select ecta_associations.id_association, ecta_associations.title, 0+title AS num_order FROM ecta_associations order by num_order",'ectamembersdev');
							
							while($association = spip_fetch_array($q)) {
								$association['title'] = supprimer_numero($association['title']);
							
								$q2 = spip_query("select id_association FROM ecta_members_associations 
										where ecta_members_associations.id_association='{$association['id_association']}' and id_member='$seq'",'ectamembersdev');
							
								if (spip_num_rows($q2)) $checked=" checked "; else $checked = '';
							
								echo "
										<li>
											<label>{$association['title']}</label> <input name='associations[{$association['id_association']}]' type='checkbox' value='{$association['id_association']}' $checked>
										</li>
									";
							}
							
							?>
	

					</ul></fieldset>
				<li>
					<label>Past member of Council/ Committees
					</label>
					<select name="pastcouncil" id="pastcouncil">
						<option value=''>Make a choice</option>
						<option value="Yes" <?php if ($pastcouncil == 'Yes') { echo 'selected'; } ?>>
							Yes
						</option>
						<option value="No" <?php if ($pastcouncil == 'No') { echo 'selected'; } ?>>
							No
						</option>
					</select>
				</li>
				
			</ul>
			<p class='boutons_formulaire'>
				<input type="submit" name="Submit" value="Submit">
			</p>
		</div><!-- membership -->
		
		<div id="professional" class="formulaire_spip">
			<ul>
				<li>
					<label>Member number</label> <?php echo $inmembernumber;?>
				</li>
				<li>
					<label>Company</label> 
					<?php echo $incompany;?>
				</li>
				<li>
					<label>Practice in</label> 
					<select name="inpracticein" id="inpracticein" style="width:195px">
						<option value=''>Make a choice</option>
						<?php
						
							$q = sql_select('*','spip_geo_pays','','','pays');
							while ($p = sql_fetch($q)) {
								echo "<option value='".$p['code_iso']."'";
								if ($inpracticein == $p['code_iso']) { echo 'selected'; }
								echo ">".$p['pays']."</option>\n";
							}
							
						?>
					</select>
				</li>
				<li>
					<label>In activity since</label> 
					<input name="inactivitysince" type="text" class="text" id="inactivitysince" value="<?php echo $inactivitysince;?>">
				</li>
				<li>
					<label>Categories of Professional</label> 
					<fieldset><ul>
				<?php
							
							$q = spip_query("select ecta_categories_of_professional.id_category, ecta_categories_of_professional.title, 0+title AS num_order FROM ecta_categories_of_professional order by num_order",'ectamembersdev');
							
							while($category = spip_fetch_array($q)) {
								$category['title'] = supprimer_numero($category['title']);
							
								$q2 = spip_query("select id_category FROM ecta_members_categories_of_professional 
										where ecta_members_categories_of_professional.id_category='{$category['id_category']}' and id_member='$seq'",'ectamembersdev');
							
								if (spip_num_rows($q2)) $checked=" checked "; else $checked = '';
							
								echo "
										<li>
											<label>{$category['title']}</label> <input name='categories_of_professional[{$category['id_category']}]' type='checkbox' value='{$category['id_category']}' $checked>
										</li>
									";
							}
							
							?>
								</ul></fieldset>
				</li>
				
			</ul>
			<p class='boutons_formulaire'>
				<input type="submit" name="Submit" value="Submit">
			</p>
		</div> <!-- professional -->
		
		<div id="conferencies" class="formulaire_spip">
			<ul>
				<li>
					<label>Member number</label> <?php echo $inmembernumber;?>
				</li>
				<li>
					<label>Spring conferences attended</label>
					<fieldset>
						<ul>
<?php 
	$q = spip_query("select * FROM ecta_conferencies WHERE type='spring' order by ecta_conferencies.year DESC",'ectamembersdev');

	while($conf = spip_fetch_array($q)) {
		$q2 = spip_query("select * FROM ecta_members_conferencies WHERE id_member='$seq' and id_conference='{$conf['id_conference']}'",'ectamembersdev');
		$spring_conferences[$conf['id_conference']] = '';
		if (spip_num_rows($q2)) {
			$participation = spip_fetch_array($q2);
			$spring_conferences[$conf['id_conference']] = $participation['participation'];
		}
	
?>
						<li>
								<label><?php echo $conf['title'].' '.$conf['year']; ?></label>
								<select name="spring_conferences[<?=$conf['id_conference']?>]">
									<option>No</option>
									<option <?php if ($spring_conferences[$conf['id_conference']]=='Speaker') echo 'selected';?> >Speaker</option>
									<option <?php if ($spring_conferences[$conf['id_conference']]=='Chair') echo 'selected';?> >Chair</option>
									<option <?php if ($spring_conferences[$conf['id_conference']]=='Delegate') echo 'selected';?> >Delegate</option>
								</select>
							</li>
<?php
	}
?>
						</ul>
					</fieldset>
				</li>
				<li>
					<label>Autumn Council meeting attended</label>
					<fieldset>
						<ul>
<?php 
	$q = spip_query("select * FROM ecta_conferencies WHERE type='autumn' order by ecta_conferencies.year DESC",'ectamembersdev');

	while($conf = spip_fetch_array($q)) {
		$q2 = spip_query("select * FROM ecta_members_conferencies WHERE id_member='$seq' and id_conference='{$conf['id_conference']}'",'ectamembersdev');
		$autumn_council[$conf['year']] = '';
		if (spip_num_rows($q2)) {
			$participation = spip_fetch_array($q2);
			$autumn_council[$conf['id_conference']] = $participation['participation'];
		}
?>
						<li>
								<label><?php echo $conf['title'].' '.$conf['year']; ?></label>
								<select name="autumn_council[<?=$conf['id_conference']?>]">
									<option>No</option>
									<option <?php if ($autumn_council[$conf['id_conference']]=='Speaker') echo 'selected';?> >Speaker</option>
									<option <?php if ($autumn_council[$conf['id_conference']]=='Chair') echo 'selected';?> >Chair</option>
									<option <?php if ($autumn_council[$conf['id_conference']]=='Delegate') echo 'selected';?> >Delegate</option>
								</select>
							</li>
<?php
	}
?>
						</ul>
					</fieldset>
				</li>
			</ul>
			<p class='boutons_formulaire'>
				<input type="submit" name="Submit" value="Submit">
			</p>
		</div>	 <!-- conferencies -->
		
		<div id="cotisation" class="formulaire_spip">
			<ul>
				<li>
					<label>Member number</label> <?php echo $inmembernumber;?>
				</li>
				<li>
					<label>Membership fee</label>
					<fieldset>
						<ul>
							<li>
								<label>Year</label>
								<select name="membership_year">
									<option value=''></option>
									<?php
										$q = spip_query("select DISTINCT membership_year FROM ecta_members WHERE membership_year!=0 ORDER BY membership_year",'ectamembersdev');
										$year='';
										while ($y = spip_fetch_array($q)) {
											$year = $y['membership_year'];
											echo "<option";
											if ($membership_year == $y['membership_year']) { echo ' selected'; }
											echo ">{$y['membership_year']}</option>";
										}
										if ($year < date('Y')-1) echo "<option>".(date('Y')-1)."</option>";
										if ($year < date('Y')) echo "<option>".(date('Y'))."</option>";
										if ($year < date('Y')+1) echo "<option>".(date('Y')+1)."</option>";
									?>
								</select>
							</li>
							<li>
								<label>Type</label>
								<select name="membership_fee" id="membership_subscription_fee">
									<?php 
										$q = spip_query("select DISTINCT membership_fee FROM ecta_membership_type order by membership_fee",'ectamembersdev');
							
										while($sub = spip_fetch_array($q)) {
											echo "<option value='{$sub['membership_fee']}' ";
											if ($membership_fee == $sub['membership_fee']) { echo 'selected'; }
											echo ">{$sub[membership_fee]}</option>";
										}	
										?>
								</select>
							</li>
						</ul>
					</fieldset>	
				</li>
				<li>
					<label>Payment</label>
					<fieldset>
						<ul>
							<li>
								<label>Error / Delayed reason</label>
								<input type="text" name="payment_error" value="<?php echo $payment_error; ?>" />
							</li>	
							<li>
								<label>Method of payment</label>
								<input type="text" name="method_of_payment" value="<?php echo $method_of_payment; ?>" />
							</li>	
							<li>
								<label>Date of payment</label>
								<input type="text" name="date_of_payment" value="<?php echo $date_of_payment; ?>" />
							</li>	
						</ul>
					</fieldset>
				</li>
				<li>
					<label>Reference</label>
					<input type="text" name="reference" class="text" value="<?php echo $reference; ?>" />
				</li>	
				<li>
					<label>VAT Number</label>
					<input type="text" name="vat_number" class="text" value="<?php echo $vat_number; ?>" />
				</li>	
				<li>
					<label>Special requests</label>
					<textarea name="special_requests" rows=10><?php echo $special_requests; ?></textarea>
				</li>	
			</ul>
			<p class='boutons_formulaire'>
				<input type="submit" name="Submit" value="Submit">
			</p>
		</div>	 <!-- cotisation -->


	</div>

</form>


<p align="center">
	<a href="?exec=membre_ecta_list">Back to the main menu</a>
</p>
		
		<?php
		echo fin_gauche(); 
		?>
		
<script type="text/javascript">

   $("#tabs").tabs({
    load: function(event, ui) {
        $(ui.panel).delegate('a', 'click', function(event) {
            $(ui.panel).load(this.href);
           event.preventDefault();
        });
  
    },
   cache: false 
});

</script>

<?php

	echo fin_page();
}

?>
