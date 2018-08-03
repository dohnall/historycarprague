<?php
define("MODE", 'CMS');
require_once dirname(dirname(__FILE__))."/config/config.php";

$smarty = Smarty::getInstance();
$smarty->template_dir = CMSTEMPLATES;
$smarty->compile_dir = CMSTEMPLATESC;

$db = Database::connect();
$session = Session::getInstance(MODE);

Config::setVar('USER_TIMEZONE', DEFAULT_TIMEZONE);
Config::setVar('CURRENT_DOMAIN_URL', ROOT);

$lang = new Lang(1);
$lang->load();
$langData = $lang->get();
$langCode = $langData['item']['code'];
Config::setVar('CURRENT_LANG_CODE', $langCode);

if(file_exists(STATOR.$langCode.".ini")) {
	$smarty->configLoad(STATOR.$langCode.".ini");
	$dictionary = $smarty->getConfigVars();
} else {
	$dictionary = array();
}

$query = "SELECT *
          FROM ".Config::db_table_newsletter()."
          WHERE prepared = '1'
          ORDER BY inserted ASC";
$newsletters = $db->select($query);

foreach($newsletters as $newsletter) {
    $query = "SELECT nu.nuser_id, nu.email, nu.md5check
              FROM ".Config::db_table_nqueue()." nq
              LEFT JOIN ".Config::db_table_nuser()." nu ON (nu.nuser_id=nq.nuser_id)
              WHERE nu.status='1' AND
                    nq.newsletter_id='".$newsletter['newsletter_id']."'
              ORDER BY nu.nuser_id ASC
              LIMIT 0, 350";
    $nusers = $db->select($query);

    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->IsHTML(true);
    $mail->CharSet  = "utf-8";
    $mail->FromName = "Divadlo Zbraslav";
    $mail->From     = "kultura@mc-zbraslav.cz";
    $mail->WordWrap = 50;
    $mail->Subject  = $newsletter['subject'];
    $mail->AddReplyTo("kultura@mc-zbraslav.cz");

	$smarty->assign(array(
		'subject' => $newsletter['subject'],
		'content' => $newsletter['content'],
		'DESIGN' => DESIGN,
		'SERVICE' => ROOT.'lib/service/',
	));
	$content = $smarty->fetch('newsletter-email.html');

    preg_match_all('/\<img.*src=\"(.*)\".*\/?\>/Ui', $content, $matches);
    $matches[1] = array_unique($matches[1]);

    foreach($matches[1] as $cid => $image) {
        $cid = "image".(++$cid);
        $newimage = str_replace(ROOT, LOCAL, $image);

        switch(substr($image, -3)) {
            case "jpg": $type = "image/jpeg"; break;
            case "gif": $type = "image/gif"; break;
            case "png": $type = "image/png"; break;
            default: $type = ""; break;
        }
        
        if(!$type) {
            continue;
        }

        $mail->AddEmbeddedImage($newimage, $cid, "", "base64", $type);
        $content = str_replace($image, "cid:".$cid, $content);
    }

    foreach($nusers as $k => $nuser) {
        $mail->AddAddress($nuser['email']);

        $content = str_replace('###NEWSLETTER###', $newsletter['newsletter_id'], $content);
        $content = str_replace('###HASH###', $nuser['md5check'], $content);

        $mail->Body = $content;
        $mail->AltBody  =  var_export($content, true);

        //echo $nuser['email']."<br />";
        $mail->Send();
        $mail->ClearAddresses();

		$db->insert("INSERT INTO ".Config::db_table_nlog()."
					 (newsletter_id, email, inserted)
					 VALUES
					 (".$newsletter['newsletter_id'].", '".$nuser['email']."', NOW())");
        $db->delete("DELETE FROM ".Config::db_table_nqueue()." WHERE newsletter_id='".$newsletter['newsletter_id']."' AND nuser_id='".$nuser['nuser_id']."'");
        sleep(1);
    }

    $query = "SELECT COUNT(*) AS cnt FROM ".Config::db_table_nqueue()." WHERE newsletter_id='".$newsletter['newsletter_id']."'";
    $count = $db->select($query, true, "cnt");
    if($count == 0) {
        $db->update("UPDATE ".Config::db_table_newsletter()." SET sent = NOW(), prepared = '0' WHERE newsletter_id='".$newsletter['newsletter_id']."'");
    }
}

$db->execute("OPTIMIZE TABLE ".Config::db_table_nqueue());
