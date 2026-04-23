<?php
/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

require_once __DIR__ . "/auth-session.php";

auth_logout_user();

header("Location: ../index.php");
exit;
?>
