<?php

$linkvertise_link = "https://direct-link.net/262244/lnyxhub-key1"; 
$oauth_client_id = "991779189730574417"; 
$oauth_client_secret = "DkWoJt-COA_cowXgDpzR6ozEPb6LdTO0"; 
$redirect_uri = "https://lnyxhub.000webhostapp.com/index.php"; 
$roblox_script_url = "https://raw.githubusercontent.com/KylnDantas/LnyxHub/main/lnyxhub.lua";  
$generatekey_cooldown = 3600;  
?>

<head>
    <script src="/cdn-cgi/apps/head/SLwQaXnaxsU2cTNGknlTuxVURdM.js"></script>
    <script src="/cdn-cgi/apps/body/UXTJ_vBY8enkI0yLbtjBjPg8Hj8.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<style>
    body {
        background-image: url('https://media.discordapp.net/attachments/684794691358949407/990210157910843413/992fa12d13fe630fd49379e85775cc86.png');
        background-repeat: no-repeat;
        background-position: center;
        background-color: #151B23;
        color: white;
    }

    #keyholder {
        position: relative;
        top: 30px;
        width: 800px;
    }
</style>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);  

error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', "$oauth_client_id");
define('OAUTH2_CLIENT_SECRET', "$oauth_client_secret");

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';

session_start();

if (get('action') == 'whitelist') {

    $params = array(
        'client_id' => OAUTH2_CLIENT_ID,
        'redirect_uri' => "$redirect_uri",
        'response_type' => 'code',
        'scope' => 'identify'
    );

    header('Location: https://discord.com/api/oauth2/authorize' . '?' . http_build_query($params));
    die();
}

if (get('code')) {

    $token = apiRequest($tokenURL, array(
        "grant_type" => "authorization_code",
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
        'redirect_uri' => "$redirect_uri",
        'code' => get('code')
    ));
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;

    header('Location: ' . $_SERVER['PHP_SELF']);
}

$referer = $_SERVER['HTTP_REFERER'];
$referer_parse = parse_url($referer);

if ($referer_parse['host'] == "linkvertise.com") {
    if (session('access_token')) {
        if (isset($_COOKIE['cooldown'])) {
            $user = apiRequest($apiURLBase);
            $id = $user->id;
            $url = "https://api.luawl.com/getKey.php";

            $data = '{"discord_id": ' . $user->id . ',"token": "bd26e9ccca24669afb82c12f53ae1a092dada5a0"}';
            $additional_headers = array('Content-Type: application/json');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $additional_headers);
            $json = curl_exec($ch);

            $outer = json_decode($json, true);
            $currentkey = $outer["wl_key"];

            $timeleft = $generatekey_cooldown - (time() - $_COOKIE['cooldown']);
            $hours = floor($timeleft / 3600);
            $minutes = floor(($timeleft / 60) % 60);
            $timeleft = "$hours Hours $minutes Minutes";
            echo '<center>';
            echo "<h2>You Already Generated A Key Please Wait $timeleft, But Heres Your <br> Current Key If You're Wanting That</h2>";
            $compiled_oldkey = "_G.wl_key = '$currentkey'
loadstring(game:HttpGet('$roblox_script_url'))()";
?>

            <div id='keyholder' class="card formSmall" style="border:1px solid darkgrey;">
                <pre><code style='color:black'><?php echo $compiled_oldkey ?></code></pre>
            </div>
        <?php
            echo '</center>';
        } else {
            echo '<center>';

            $user = apiRequest($apiURLBase);

            echo '<h4>Welcome, ' . $user->username . "#" . $user->discriminator . '</h4>';

            echo '<script> window.setTimeout("window.close()", 60000); </script>';
            $id = $user->id;
            $url = "https://api.luawl.com/whitelistUser.php";
            $data = '{"discord_id": ' . $user->id . ',"token": "bd26e9ccca24669afb82c12f53ae1a092dada5a0","isTrial": "1","trial_hours": "24"}';
            $additional_headers = array('Content-Type: application/json');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $additional_headers);
            $server_output = curl_exec($ch);
            $output = str_replace('"', "", $server_output);
            $compiled = "_G.wl_key = '$output'
loadstring(game:HttpGet('$roblox_script_url'))()";
        ?>


            <div id='keyholder' class="card formSmall" style="border:1px solid darkgrey;">
                <pre><code style='color:black'><?php echo $compiled ?></code></pre>
            </div>

            <br>
            <br>
            </center>

<?php
            setcookie('cooldown', time(), time() + $generatekey_cooldown);
        }
    } else {
        header("Location: ?action=whitelist");
    }
} else {
    header("Location: $linkvertise_link");
    exit();
}


function apiRequest($url, $post = FALSE, $headers = array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);

    if ($post)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $headers[] = 'Accept: application/json';

    if (session('access_token'))
        $headers[] = 'Authorization: Bearer ' . session('access_token');

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response);
}

function get($key, $default = NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default = NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

?>