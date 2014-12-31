<?php
    require_once __DIR__ . "/lib/SEHelper.php";
    if ( isset($_GET["userid"]) ) {
        $user_id = $_GET["userid"];
        $user = SEHelper::getUserById($user_id);
        $headline = "Unanswered Questions for " . $user["display_name"];
    } else if ( isset($_GET["username"]) ) {
        $username = $_GET["username"];
        $headline = "What is your StackExchange User Name?";
    } else {
        $headline = "What is your StackExchange User Name?";
    }
?>

<html>
    <head><title>Find Answerable Questions</title></head>
    <body>
        <h1><?php echo $headline?></h1>
        <?php
        if ( isset($username) ) {
            $users = SEHelper::getUsersByName($username);
            if ( count($users) == 1 ) {
                $redirect = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "?userid=" . $users[0]["user_id"];
                echo $redirect;
                header("Location: " . $redirect);
                exit();
            } else {
                foreach ( $users as $user ) {
                    // $redirect = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "?userid=" . $user["user_id"];
                    $redirect = "index.php?userid=" . $user["user_id"];
                    $cta_str = "Find questions for " . $user["display_name"];
                    echo "<a href='$redirect' target='_self'>$cta_str</a><br/>";
                }
            }
        } else if ( isset($user_id)) {
            $tags = SEHelper::getTagsByUserId($user_id);
            $questions = SEHelper::getQuestionsByTags($tags);
            foreach ($questions as $question) {
                $owner = $question["owner"];
                echo $owner["display_name"]. " asked \"" . $question["title"] . "\".<br>";
            }
        } else {
            $html_form = "<form action='index.php' method='GET'>";
            $html_form .= "<input type='text' name='username'/><input type='submit'/>";
            $html_form .= "</form>";
            echo $html_form;
        }
        ?>
    </body>
</html>