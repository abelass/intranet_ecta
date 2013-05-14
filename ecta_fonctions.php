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


?>