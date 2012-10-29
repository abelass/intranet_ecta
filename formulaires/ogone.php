<?php 
/* http://www.dialoguedynamics.com/-Faire-un-don-;http://www.dialoguedynamics.com/Donate; */
define('_ACTION_OGONE','https://secure.ogone.com/ncol/prod/orderstandard.asp');
define('_PSPID','isaavedra');
define('_SHA_KEY','TestP0urR1re');

function formulaires_donation_charger_dist(){
	$valeurs = array();
		
	/* Pays */
	if (_request('ownercty')) $valeurs['pays'] = _request('ownercty');
	else $valeurs['pays'] = 'BE';
	
	$sel = '';
	$q = sql_select('*','codes_pays','','',array('nom'));
	while ($u = sql_fetch($q)) {
		$s = "<option value='%s' %s>%s</option>";
		if ($u['code'] == $valeurs['pays']) {
			$sel .= sprintf($s,$u['code'],"selected",htmlentities(utf8_decode($u['nom'])));
			$valeurs['pays'] = htmlentities(utf8_decode($u['nom']));
		}
		else $sel .= sprintf($s,$u['code'],"",htmlentities(utf8_decode($u['nom'])));
	}
	$valeurs['select_pays'] = $sel;


	if(_request('don')){
		
		$centimes = (int)_request('don') * 100;
		$owneraddress = substr(_request('address1').','._request('address2'),0,35);

		/* Nouvelle transaction => on stocke en base */
		$transaction_id = sql_insertq('transactions',array(
			'email'=>_request('EMAIL'),
			'CN'=>_request('CN'),
			'amount' => $centimes,
			'language' => _request('select_language'),
			'currency' => _request('select_currency'),
			'owneraddress' => $owneraddress,
			'address1' => _request('address1'),
			'address2' => _request('address2'),
			'ownerzip' => _request('ownerzip'),
			'ownertown' => _request('ownertown'),
			'ownercty' => _request('ownercty'),
			'COMPLUS' => _request('COMPLUS')			
			)
		);
		
		$reference = 'Don_'.$transaction_id.'_'.date('Ymd_Hi');
		$SHASign = bin2hex(mhash(MHASH_SHA1, $reference.$centimes._request('select_currency')._PSPID._SHA_KEY));
		$masque = "<div style=\"position:absolute;top:0;left:0;width:100%;height:100%;background:white url(".find_in_path('ajax-loader.gif').") center center no-repeat\"><span></span></div><script language=\"JavaScript\">document.getElementById(\"form_ogone\").submit()</script>";
		$valeurs =  array(
			'email'=>_request('EMAIL'),
			'don' => (int)_request('don'),
			'CN'=>_request('CN'),
			'reference'=>$reference,
			'pspid' => _PSPID,
			'amount' => $centimes,
			'action' => _ACTION_OGONE,
			'lang_code' => _request('select_language'),
			'currency' => _request('select_currency'),
			'owneraddress' => couper(_request('address1').','._request('address2'),35),
			'address1' => _request('address1'),
			'address2' => _request('address2'),
			'ownerzip' => _request('ownerzip'),
			'ownertown' => _request('ownertown'),
			'ownercty' => _request('ownercty'),
			'COMPLUS' => _request('COMPLUS'),
			'SHASign' => $SHASign,
			'masque' => $masque);
			
	}
	
	if ($GLOBALS['visiteur_session']['email'])
		$valeurs['email'] = $GLOBALS['visiteur_session']['email'];

	if ($GLOBALS['visiteur_session']['nom'])
		$valeurs['CN'] = $GLOBALS['visiteur_session']['nom'];

	return $valeurs;
}

function formulaires_donation_verifier_dist(){
	$erreurs = array();
	// verifier que les champs obligatoires sont bien la :
	foreach(array('EMAIL','don','select_currency','select_language','CN') as $obligatoire)
		if (!_request($obligatoire)) $erreurs[$obligatoire] = 'Ce champ est obligatoire';
	
	// verifier que si un email a &eacute;t&eacute; saisi, il est bien valide :
	include_spip('inc/filtres');
	if (_request('EMAIL') AND !email_valide(_request('EMAIL')))
		$erreurs['EMAIL'] = 'Cet email n\'est pas valide';

	if (_request('don') AND !intval(_request('don')))
		$erreurs['don'] = 'Ce champ doit &ecirc;tre un nombre entier';

	if (count($erreurs))
		$erreurs['message_erreur'] = 'Votre saisie contient des erreurs !';
		
	return $erreurs;
}

function formulaires_donation_traiter_dist(){
	$return =  array('message_ok'=>'Votre don a &eacute;t&eacute; pris en compte. Vous allez &ecirc;tre redirig&eacute; automatiquement vers notre syst&egrave;me de banque en ligne. Si ce n\'est pas le cas, veuillez cliquer de nouveau sur le bouton "envoyer"');
	return $return;
}

?>