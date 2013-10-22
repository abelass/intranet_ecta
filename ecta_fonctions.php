<?php
/**
 * Fonctions utiles au plugin Intranete Ecta
 *
 * @plugin     Intranete Ecta
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Ecta\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function tableau_roles(){
     $r=sql_select('*','spip_commitee_role');
        $roles=array();
        while($rs=sql_fetch($r)){
          $roles[$rs['id_commitee_role']]=supprimer_numero($rs['title']);  
        }
   return $roles;
}
// Generates the form entry for the council memberships
function council_member($seq){
	$start_date=0000;    
	$end_date=0000;  
	
	$champ0='<div><select class="statut" name="council_statut[new]">
		<option value="">No</option>
		<option value="Yes">Yes</option>
		<option value="Com">Com</option>                                        
	</select>';  
	$champ1='<span> <b>From:</b> </span><input class="start_date" name="council_start_date[new]" type="text" value="'.$start_date.'"/>';
	$champ2='<span> <b>To:</b> </span><input class="start_date"  name="council_end_date[new]" type="text" value="'.$end_date.'"/></div>';
	$champs=$champ0.$champ1.$champ2;
	$sql=sql_select('*','spip_members_council','seq='.$seq,'','start_date DESC');

	if(sql_count( $sql)>0)$champs='';
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
				</span><div class="cache">';
			$end_tag=		'</div>';
			}
			
		$champs.=recuperer_fond('formulaires/field_period_councils',
			array(
			'begin_tag'=>$begin_tag,
			'end_tag'=>$end_tag, 
			'id_membership'=>$councils['id_membership_council'], 
			'statut'=>$councils['statut'],
			'start_date'=>$start_date,
			'end_date'=>$end_date,
			'seq'=>$seq
			));       		 
		}
	$champs.=$end_tag;
    return $champs;
}
?>
