<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/vieilles_defs');
include_spip('inc/safe_utf8');

function exec_membre_ecta_list(){
	global $connect_statut, $connect_id_auteur;
	spip_query("SET NAMES 'utf8'",'ectamembersdev');
	spip_query("SET NAMES 'utf8'");
	
	$seq = _request('seq');
	$action = _request('act');
	$sequp = _request('sequp');
	
	if ($action == 'delete')
	{
		$sql="SELECT id_auteur FROM ecta_members where seq='$seq' ";
		$reponse = spip_query($sql,'ectamembersdev');
		$aut = spip_fetch_array($reponse);		

		// SPIP-Liste : Abonnement à la liste "membres" (id = 4)
		sql_delete('spip_auteurs_listes', 'id_auteur = '.$aut['id_auteur']);
		sql_delete('spip_auteurs', 'id_auteur = '.$aut['id_auteur']);
		sql_delete('ecta_members', 'id_auteur = '.$aut['id_auteur'], 'ectamembersdev');

		$message_maj = "The member has been deleted";
	}
	if ($action == 'desactivate')
	{
		spip_query("update ecta_members set active='No' where seq='$seq'",'ectamembersdev');
		$q = spip_query("select id_auteur from ecta_members where seq='$seq'",'ectamembersdev');
		while ($result = spip_fetch_array($reponse))
			spip_query("update spip_auteurs set statut='5poubelle' where id_auteur='{$result['id_auteur']}'");
		$message_maj = "The member has been desactivated";
	}
	if ($action == 'activate')
	{
		spip_query("update ecta_members set active='Yes' where seq='$seq'",'ectamembersdev');
		$q = spip_query("select id_auteur from ecta_members where seq='$seq'",'ectamembersdev');
		while ($result = spip_fetch_array($reponse))
			spip_query("update spip_auteurs set statut='6forum' where id_auteur='{$result['id_auteur']}'");
		$message_maj = "The member has been activated";
	}

	$commencer_page = charger_fonction('commencer_page', 'inc');
	echo $commencer_page('SUBSCRIPTIONS - ADMINISTRATION', "naviguer", "articles", $id_rubrique);

		echo debut_gauche();
			echo pipeline('affiche_gauche',array('args'=>array('exec'=>'membres_page'),'data'=>''));
			echo debut_boite_info();
				echo '<p class="style1" style="text-align:center"><img src="'._DIR_PLUGIN_ECTA.'img_pack/admin-logo.png" alt="ECTA"></p>';
				echo '<p class="style1" style="text-align:center"><strong>SUBSCRIPTIONS</strong></p>';
				?>
				
			  <?php
			echo fin_boite_info();

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

						jQuery.timer(2000,function(){jQuery('.message_ok').slideUp('slow')})
					</script>
				";
				

			}

			?>
    <style type="text/css">
		a.cellule-h {text-decoration:none;}
				tr.disabled td {color:#999 !important;}
				tr.disabled {
					background-color:#F6F6F6;
				}
				.titrem a.selected {
					color:#000;
					text-decoration: underline;
				}	
		</style>   

					
     
	<div style="" class="cadre cadre-e">
	<div class="cadre_padding formulaire_spip" style="padding: 6px;">
    <span class="verdana2" style="font-size: 80%; text-transform: uppercase; font-weight: bold;">SHORTCUTS:</span>
    <ul class="verdana2">
    
    <li>
    <table class="cellule-h-table" style="vertical-align: middle;" cellpadding="0">
    <tbody><tr><td><a href="?exec=membre_ecta_new" class="cellule-h"><span class="cell-i"><?php echo '<img src="'._DIR_PLUGIN_ECTA.'img_pack/user-add.png" alt="Add a new member">' ?></span></a></td>
    <td class="cellule-h-lien"><a href="?exec=membre_ecta_new" class="cellule-h">Add a new member</a></td></tr></tbody></table>
    </li>
        
    </ul>
	
	<div class="nettoyeur"/></div>
    
</div>			
</div>	

		
		<?php echo debut_droite(); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo chemin('js/autocomplete.css'); ?>">
		<script type="text/javascript" src="<?php echo chemin('js/jquery-1.3.1.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo chemin('js/jquery.autocomplete.pack.js'); ?>"></script>
        	<script type="text/javascript" src="<?php echo chemin('js/jquery.select-autocomplete.js'); ?>"></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
	$('select.s_company').select_autocomplete();
	$('select.s_country').select_autocomplete()
	});
	
	</script>

		<div class="liste">
        <div style="position: relative;margin-top:20px;">
        <div style="position: absolute; top: -36px; left: 10px;"><?php echo '<img src="'._DIR_PLUGIN_ECTA.'img_pack/admin.png" alt="member administration">' ?></div>
        <div style='background-color:white; color:black; padding:3px; padding:10px; border-bottom:1px solid #444;' class='verdana2'>
		<strong></strong><br />
        <!-- filtres -->
        <div class="titrem cadre cadre-e">
        <form name="form1" method="get" action="" id="filtre_membres">
        <input type="hidden" name="exec" value="membre_ecta_list">
				<?php echo "<input type='hidden' name='init' value='".(_request('init')?_request('init'):'All')."'>";?>
            <input type="hidden" name="act" value="search">

						        <div style="padding-top:3px">
						            <label style="float: left; clear: left; display: block; width: 200px; line-height:18px">Subscription :</label>
												<select name="membership_year">
													<option value=''>YEAR</option>
													<?php
														$q = spip_query("select DISTINCT membership_year FROM ecta_members WHERE membership_year!=0 ORDER BY membership_year",'ectamembersdev');
														$year='';
														while ($y = spip_fetch_array($q)) {
															$year = $y['membership_year'];
															echo "<option";
															if (_request('membership_year') == $y['membership_year']) { echo ' selected'; }
															echo ">{$y['membership_year']}</option>";
														}
													?>
												</select>
												&nbsp;
												<select name="membership_fee" id="membership_subscription_fee">
													<option value=''>TYPE</option>
													<?php 
														$q = spip_query("select DISTINCT membership_fee FROM ecta_membership_type order by membership_fee",'ectamembersdev');

														while($sub = spip_fetch_array($q)) {
															echo "<option value='{$sub['membership_fee']}' ";
															if (_request('membership_fee') == $sub['membership_fee']) { echo 'selected'; }
															echo ">{$sub[membership_fee]}</option>";
														}	
														?>
												</select>
            					</div>


			            <div style="padding-top:3px">
						            <label style="float: left; clear: left; display: block; width: 200px; line-height:18px">Name or member number :</label>
									<input type="text" class="text" name="s_name" value="<?php echo _request('s_name'); ?>" style="width:160px"> 
									</div>


					        <div style="padding-top:3px">
					            <label style="float: left; clear: left; display: block; width: 200px; line-height:18px">Filter by Company :</label>
					            <select name="s_company" class="s_company" style="width:200px">
												<option value='' <?php if (!_request('s_company')) echo 'selected';?>>ALL</option>
																	<?php

															$q = spip_query("select DISTINCT company FROM ecta_members order by company",'ectamembersdev');
															// Les apostrophe ne passe pas dans le js autocomplete, à defaut de mieux
															$r_company=str_replace("_","'",_request('s_company'));
															while($m = spip_fetch_array($q)) {
																//$company = utf8_encode($m['company']);
																$company = $m['company'];
																
																if (!$company) $company = 'NULL';
																echo '<option value="'.str_replace("'","_",$m['company']).'"';
																if ($r_company == $company || ($r_company == 'NULL' && !$company))  echo ' selected ';
																echo ">{$company}</option>";

															}

															?>
												</select></div>
												<div style="padding-top:3px">
									            <label style="float: left; clear: left; display: block; width: 200px; line-height:18px">Filter by Country :</label>
									            <select name="s_country" class="s_country" style="width:200px">
													<option value='' <?php if (!_request('s_country')) echo 'selected';?>>ALL</option>
			            <?php

			                $q = sql_select('*','spip_geo_pays');
			                while ($p = sql_fetch($q)) {
			                    $list[$p['code_iso']] = $p['pays'];
			                }
			                $q = sql_query('select distinct country from ecta_members order by country','ectamembersdev');
			                while ($p=spip_fetch_array($q)) {
												if (!$p['country']) {$p['country'] = 'NULL';$list[$p['country']] = 'NULL';}
												$liste_c[$p['country']] = $p['country'];
											}
											asort($list);
											foreach ($list as $code => $country) {
												if (isset($liste_c[$code])) {
			                    echo "<option value='".$code."'";
			                    if (_request('s_country') == $code || (_request('s_country') == 'NULL' && !$code)) { echo 'selected'; }
			                    echo ">".$country."</option>\n";
												}
			                }
			            ?>
			            </select>
			            </div>

						<div style="padding-top:12px;text-align:right">
							<input type="submit" name="Submit" value="Search">&nbsp;
							 
							<?php 
							if (_request('init')) echo "<a target='blank' href=\"./?exec=export_csv&init="._request('init')."&s_company=".urlencode(_request('s_company'))."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."\",\"export CSV\">Export to .CSV</a>&nbsp;&nbsp;";
							//if (_request('init')) echo "&nbsp;|&nbsp;<a target='blank' href=\"./?exec=export_xls&init="._request('init')."&s_company=".urlencode(_request('s_company'))."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."\",\"export CSV\">.XLS</a>&nbsp;&nbsp;";
							?>
						</div>
  		</form>
        </div>
		<!-- filtres -->
        
        <!-- alphabet -->
        <div class="titrem cadre cadre-e">
					<?php
						echo "<a href='?exec=membre_ecta_list&init=All&s_company=".urlencode(_request('s_company'))."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."' ",
							(!_request('init') || _request('init')=='All')?'class="selected"':'',
							"><strong>All</strong></a>&nbsp;|";
						$alph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
						foreach($alph as $let) {
							echo "&nbsp;<a href='?exec=membre_ecta_list&init=$let&s_company=".urlencode(_request('s_company'))."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."' ",
							(_request('init')==$let)?'class="selected"':'',
							"><strong>$let</strong></a>";
						}
					?>

        </div>
        <!-- alphabet -->
		</div>
		</div>
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <thead>
			<tr class="formulaire_spip tr_liste">
		    <!-- th><div align="center"><strong>N&deg;</strong></div></th -->
				<th><div align="center"><strong>Name <?php 

			$sort_company = (_request('by')=='company' ? (_request('order')=='ASC' ?'DESC':'ASC') : '');
			$sort_name = (_request('by')=='surname' ? (_request('order')=='ASC' ?'DESC':'ASC') : '');
			$sort_country = (_request('by')=='country' ? (_request('order')=='ASC' ?'DESC':'ASC') : '');
			$sort_active = (_request('by')=='active' ? ( _request('order')=='ASC' ?'DESC':'ASC') : '');

			echo '<a href="?exec=membre_ecta_list&by=surname&order='.$sort_name."&init="._request('init')."&s_company="._request('s_company')."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."".'" title="filter by name"><img src="'._DIR_PLUGIN_ECTA.'img_pack/puce_filter_'.$sort_name.'.gif" alt="Filter"></a>' ?></strong></div></th>
			  <th><div align="center"><strong>Company <?php echo '<a href="?exec=membre_ecta_list&by=company&order='.$sort_company."&init="._request('init')."&s_company="._request('s_company')."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."".'" title="filter by company"><img src="'._DIR_PLUGIN_ECTA.'img_pack/puce_filter_'.$sort_company.'.gif" alt="Filter"></a>' ?></strong></div></th>
			  <th width="5%"><div align="center"><?php echo '<a href="?exec=membre_ecta_list&by=country&order='.$sort_country."&init="._request('init')."&s_company="._request('s_company')."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."".'" title="filter by country"><img src="'._DIR_PLUGIN_ECTA.'img_pack/puce_filter_'.$sort_country.'.gif" alt="Filter"></a>' ?></div></th>
			  <th width="5%"><div align="center"><?php echo '<a href="?exec=membre_ecta_list&by=active&order='.$sort_active."&init="._request('init')."&s_company="._request('s_company')."&s_country="._request('s_country')."&membership_fee="._request('membership_fee')."&membership_year="._request('membership_year')."&s_name=".urlencode(_request('s_name'))."".'" title="filter by status"><img src="'._DIR_PLUGIN_ECTA.'img_pack/puce_filter_'.$sort_active.'.gif" alt="Filter"></a>' ?></div></th>
		    <th width="5%"></th>
            <th width="5%"></th>
		  </tr>
		</thead>
        <tbody>
        
        <?php
		
		if (!_request('init') && !_request('act')) :
			echo "<tr><td colspan=7>Please enter some search criteria</td></tr>";
		else :
		

		if ($s_name = mysql_real_escape_string(strtolower(_request('s_name')))) 
			$where[] = "(LOWER(name) like '%$s_name%' OR LOWER(surname) like '%$s_name%' OR membernumber = '$s_name')";
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
		
		$sql=translitteration("SELECT seq,title,surname,name,company,country,membernumber,active FROM ecta_members $where ORDER BY $ORDER_BY ");
		
		$reponse = spip_query($sql,'ectamembersdev');
		if (!spip_num_rows($reponse)) echo ("<tr><td colspan=6>No result found <!-- (requete : ".$sql.") --></td></tr>");
		else {
		$il=0;
		while($row = spip_fetch_array($reponse)){
			if ($il==0) {$il=1; echo ("<tr><td colspan=6>".spip_num_rows($reponse)." results found</td></tr>");}
			//foreach($row as $k => $v) {$row[$k] = htmlentities($v,ENT_QUOTES,'UTF-8');}
			$rseq = $row['seq'];
			$rsuffixe = $row['title'];
			//$rsuffixe = stripslashes($rsuffixe);
			$rsurname = $row['surname'];
			//$rsurname = stripslashes($rsurname);
			$rname = $row['name'];
			//$rname = stripslashes($rname);
			$rcompany = $row['company'];
			//$rcompany = stripslashes($rcompany);
			$rcountry = $row['country'];
			//$rcountry = stripslashes($rcountry);
			$rmembernumber = $row['membernumber'];
			$rmembernumber = stripslashes($rmembernumber);

			if ($row['active']=='Yes') {
				echo "<tr class='tr_liste'>\n
				<!-- td class='arial1'><center><div class='style3'><input type='checkbox' value='$rmembernumber'></div></center></td -->\n
				<td class='arial1'><div class='style3'><a href='?exec=membre_ecta_edit&seq=$rseq'>$rsuffixe $rname $rsurname</a></div></td>\n
				<td class='arial1'><div class='style3'>$rcompany</div></td>\n
				<td class='arial1'><div class='style3'>$rcountry</div></td>\n
				<td class='arial2'><center><div class='style3'><a href='?exec=membre_ecta_list&act=desactivate&seq=$rseq' title='disable this member'><img src='".find_in_path('img_pack/user-desactivate.png')."' border='0' /></a></center></div></td>\n
				<td class='arial2'><center><div class='style3'><a href='?exec=membre_ecta_edit&seq=$rseq' title='edit this member'><img src='".find_in_path('img_pack/user-edit.png')."' border='0' /></a></center></div></td>\n
				<td class='arial2'><center><div class='style3'><a href='?exec=membre_ecta_list&act=delete&seq=$rseq' onCLick=\"return confirm('Are you SURE you want to delete this member ?')\" title='delete this member'><img src='".find_in_path('img_pack/user-delete.png')."' border='0' /></a></center></div></td>\n
				</tr>\n";
			} else {
				echo "<tr class='tr_liste disabled'>\n
				<!-- td class='arial1'><center><div class='disabled'><input type='checkbox' value='$rmembernumber'></div></center></td -->\n
				<td class='arial1'><div class='style3'>$rsuffixe $rname $rsurname</div></td>\n
				<td class='arial1'><div class='style3'>$rcompany</div></td>\n
				<td class='arial1'><div class='style3'>$rcountry</div></td>\n
				<td class='arial2'><center><div class='style3'><a href='?exec=membre_ecta_list&act=activate&seq=$rseq' title='activate this member'><img src='".find_in_path('img_pack/user-activate.png')."' border='0' /></a></center></div></td>\n
				<td class='arial2'><center><div class='style3'><a href='?exec=membre_ecta_edit&seq=$rseq' title='edit this member'><img src='".find_in_path('img_pack/user-edit.png')."' border='0' /></a></center></div></td>\n
				<td class='arial2'><center><div class='style3'><a href='?exec=membre_ecta_list&act=delete&seq=$rseq' onCLick=\"return confirm('Are you SURE you want to delete this member ?')\" title='delete this member'><img src='".find_in_path('img_pack/user-delete.png')."' border='0' /></a></center></div></td>\n
				</tr>\n";			
			} // if
			} // while
		} //if

			endif; // Affichage des résultats si recherche
			
			echo ("</tbody>");
			/*¨ /// Plus d'envoi en nombre : ce n'était pas prévu. On passe par select puis export csv puis outils de ML
			if (spip_num_rows($reponse)) echo ("<tfoot><tr><td colspan=6><a href=''>Select all</a> | <a href=''>Select none</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&gt;&nbsp;with selected : <select><option></option><option>Send a message</option></select></td></tr></tfoot>");
			*/
			echo "</table></div>";	

			echo fin_gauche(); 
			echo fin_page();
	}
// http://www.ecta.org/ecrire/?exec=membre_ecta_list&init=All&act=search&membership_year=&membership_fee=&s_name=&s_company=&s_country=&Submit=Search

?>