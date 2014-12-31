<?php
    require_once __DIR__ . "/lib/SEHelper.php";
    if ( isset($_GET["userid"]) ) {
        $user_id = $_GET["userid"];
        // $user = SEHelper::getUserById($user_id);
        $user = array (
            "reputation" => 9001,
            "user_id" => 1,
            "user_type" => "registered",
            "accept_rate" => 55,
            "profile_image" => "https://www.gravatar.com/avatar/a007be5a61f6aa8f3e85ae2fc18dd66e?d=identicon&r=PG",
            "display_name" => "Amy Codes",
            "link" => "http =>//example.stackexchange.com/users/1/example-user"
        );
        $headline = "Unanswered Questions for " . $user["display_name"];
    } else if ( isset($_GET["username"]) ) {
        $username = $_GET["username"];
        $headline = "What is your StackExchange User Name?";
    } else {
        $headline = "What is your StackExchange User Name?";
    }
?>

<html>
    <head>
        <title>Find Answerable Questions</title>
        <style>
            #main {
                width:760px;
                margin: 0 auto;
                text-align:center;
                font-family:Lucida Console, monospace;
            }
            
            #main input {
                width:50%;
                font-size: 16pt;
            }
            
            .user {
                width:100px;
                border: thin solid gray;
                padding: 9px 0;
                float:left;
                margin: 10px;
            }
            
            .user img {
                border: thin solid gray;
                margin-bottom: 10px;
            }
            
            .user span {
                font-size:8pt;
            }
            
            .question {
                width: 350px;
                float:left;
            }
            
            .question_content a:hover {
                background-color: lightblue;
            }
            
            .question_title {
                padding-top: 20px;
            }
            
            .question_link {
                width:100%;
                padding-top: 10px;
                text-align:center;
                font-size:10pt;
            }
            
            a:link, a:visited, a:hover, a:active {
                text-decoration: none;
                color: black;
            }
        </style>
    </head>
    <body>
        <div id="main">
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
                    $redirect = "index.php?userid=" . $user["user_id"];
                    $cta_str = "Find questions for " . $user["display_name"];
                    $user_html = "<a href='" . $redirect. "'><div class='user'><img src='" . $user['profile_image']. "'/>" . $user['display_name']. "<br/><span>Rep " . $user['reputation'] . "</span></div></a>";
                    echo $user_html;
                }
            }
        } else if (isset($user_id)) {
            $tags = SEHelper::getTagsByUserId($user_id);
            $questions = SEHelper::getQuestionsByTags($tags);
            foreach ($questions as $question) {
                $owner = $question["owner"];
                $user_html = "<a href='" . $redirect. "'><div class='user'><img src='" . $owner['profile_image']. "'/>" . $owner['display_name']. "<br/><span>Rep " . $owner['reputation'] . "</span></div></a>";
                $question_html = "<div class='question'>$user_html<div class='question_content'><a href='" . $question['link'] . "'><div class='question_title'>" . $question['title']. "</div><div class='question_link'>Click to Answer</div></div></a></div>";
                echo $question_html;
            }
        } else {
            $html_form = "<form action='index.php' method='GET'>";
            $html_form .= "<input type='text' name='username'/><br/><input type='submit'/>";
            $html_form .= "</form>";
            echo $html_form;
        }
        ?>
        </div>
    </body>
</html>