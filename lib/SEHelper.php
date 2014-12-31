<?php
/**
 * A collection of methods to access the public Stack Exchange API
 *
 * @author amynegrette
 */
class SEHelper {
    
    public static $se_baseurl = "http://api.stackexchange.com/2.2/";
    
    public static function stackExchangeGetRequest($endpoint, $params = []) {
        $url = SEHelper::$se_baseurl . $endpoint . "?site=stackoverflow";
        if ( $params != NULL && is_array($params) && count($params) > 0 ) {
            foreach ( $params as $key => $value ) {
                $url .= "&" . $key . "=" . urlencode($value);
            }
        }
        
        // echo "$url\n";
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
            CURLOPT_ENCODING => 'identity'
        );
        curl_setopt_array( $ch, $options );

        $results = curl_exec($ch);
        $results = json_decode($results,true);
        return $results;
    }
    
    public static function getUserById($userId) {
        // echo "SEHelper::getUserById($userId);\n";
        $endpoint = implode("/", array("users", $userId));
        $result = SEHelper::stackExchangeGetRequest($endpoint);
        if (is_array($result) && isset($result["items"])) {
            return $result["items"][0];
        } else return NULL;
    }


    public static function getUsersByName($name) {
        // echo "SEHelper::getUsersByName('$name');\n";
        $params = array( "inname" => $name );
        $result = SEHelper::stackExchangeGetRequest("users", $params);
        if (is_array($result) && isset($result["items"])) {
            return $result["items"];
        } else return NULL;
    }
    
    public static function getTagsByUserId($userId) {
        // echo "SEHelper::getTagsByUserId($userId);\n";
        $endpoint = implode("/", array("users", $userId, "top-tags"));
        $response = SEHelper::stackExchangeGetRequest($endpoint);
        $response_arr = $response["items"];
        $tags = [];
        foreach( $response_arr as $tag_info ) {
            $tags[] = $tag_info["name"];
        }
        return $tags;
    }
    
    public static function getQuestionsByTag($tag) {
        // echo "SEHelper::getQuestionsByTag($tag);\n";
        $params = array( "order" => "desc", "sort" => "creation", "pagesize" => 10);
        $endpoint = implode("/", array("questions" , "no-answers"));
        $response = SEHelper::stackExchangeGetRequest($endpoint, $params);
        return $response["items"];
    }
    
    public static function getQuestionsByTags($tags) {
        // echo "SEHelper::getQuestionsByTags([" . implode(",", $tags) . "]);\n";
        $questions = [];
        foreach ( $tags as $tag ) {
            $tagged_questions = SEHelper::getQuestionsByTag($tag);
            foreach ( $tagged_questions as $question ) {
                $questions[$question["question_id"]] = $question;
            }
        }
        return $questions;
    }
}
