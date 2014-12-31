<?php

require_once __DIR__ . "/lib/SEHelper.php";

$user = SEHelper::getUserById(3746009);
print_r($user);
$ids = SEHelper::getUsersByName("Amy Codes");
$tags = SEHelper::getTagsByUserId(3746009);
$php_questions = SEHelper::getQuestionsByTag("php");
$php_java_questions = SEHelper::getQuestionsByTags(array("php", "java"));