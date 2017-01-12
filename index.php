<?php

include("dbconfig.inc");
include("MyDB.php");
include("PageView.php");

$pv = new PageView();
$db = new MyDB( $dbconfig );

$users = $db->select("select a.name, a.email, z.name as role from account a left join role r on a.id = r.userId left join roles z on r.roleId = z.id where z.name = 'visitor'");

echo $pv->page(
		$pv->addStyle("https://bootswatch.com/superhero/bootstrap.min.css") .
		$pv->addScript("https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js") .
		$pv->jumbotron("Visitors","These users do not have a membership" ) .
		$pv->form(
			$pv->inputs( $users ),
			'{"method":"post","action":"process.php"}'
		)
	);

?>

