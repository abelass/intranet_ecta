<?php
/**
 * Options du plugin Intranete Ectaau chargement
 *
 * @plugin     Intranete Ecta
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Ecta\Options
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/*
 * Un fichier d'options permet de définir des éléments
 * systématiquement chargés à chaque hit sur SPIP.
 *
 * Il vaut donc mieux limiter au maximum son usage
 * tout comme son volume !
 * 
 */
// ne jamais afficher les préfixes numériques des titres
 $table_des_traitements['TITLE'][]= 'typo(supprimer_numero(%s))';

?>