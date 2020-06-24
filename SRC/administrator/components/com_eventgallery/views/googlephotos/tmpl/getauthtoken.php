<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addScript('//apis.google.com/js/platform.js?onload=eg_auth_init', [], ['async'=>true, 'defer'=>true]);

/**
 * @var EventgalleryLibraryGooglephotosaccount $googlePhotosAccount
 */
$googlePhotosAccount = $this->googlePhotosAccount;
?>

<p>
    <?php echo JText::_('COM_EVENTGALLERY_GOOGLEPHOTOS_SELECT_ACCOUNT_WHY'); ?>
</p>
<p id="loading">
    Loading....
</p>
<p>
    <a class="btn btn-primary btn-large" style="display:none" id="google-account-selector" href="#" onclick="auth()"><?php echo JText::_('COM_EVENTGALLERY_GOOGLEPHOTOS_SELECT_ACCOUNT') ?></a>
</p>


<script>

    function eg_auth_init() {
        gapi.load('auth2', function() {
            auth2 = gapi.auth2.init({
                client_id: '<?php echo $googlePhotosAccount->getClientId()?>',
                scope: 'profile https://www.googleapis.com/auth/photoslibrary.readonly https://www.googleapis.com/auth/youtubepartner',
            });

            auth2.then(function() {
                setTimeout(function() {
                    document.getElementById('google-account-selector').style.display = 'block';
                    document.getElementById('loading').style.display = 'none';
                }, 500);
            }, function(err){
                document.getElementById('loading').innerHTML = '<h1>ERROR</h1>' + err.details + "<br><br> Error Code: " + err.error;

            })

        });
    }

    function auth() {

        auth2.grantOfflineAccess({'redirect_uri': 'postmessage', 'prompt':'select_account consent'}).then(auth_signInCallback);

    }

    function auth_signInCallback(result) {
        console.log(result);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", 'https://www.googleapis.com/oauth2/v4/token', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {//Call a function when the state changes.
            if(this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                window.opener.document.getElementsByClassName('google-photos-api-oauth-input')[0].value = JSON.parse(xhr.response).refresh_token;
                window.close();
            }
        };

        xhr.send('code=' + result.code + '&client_id=<?php echo $googlePhotosAccount->getClientId()?>&client_secret=<?php echo $googlePhotosAccount->getSecret()?>&redirect_uri=postmessage&grant_type=authorization_code');

    }
</script>
