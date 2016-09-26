<?php namespace MailiumOauthClient\MailiumOauthClientLaravel;

use Closure;
use Illuminate\Http\Request;
use Auth;
use MailiumOauthClient\MailiumOauthClient;
use MailiumOauthClient\MailiumOauthClientLaravel;
use MailiumOauthClient\MailiumOauthClientLaravel\MailiumAppUser;
use Mailium\API\MailiumAPI3;
class MailiumOauthClientMiddleware
{
    const VERSION = '1.0.26';
    /**
     * @var MailiumOauthClient MailiumOauthClient\MailiumOauthClient
     */
    protected $mailiumOauthClient;

    /**
     * @var \MailiumOauthClient\MailiumOauthClientLaravel\MailiumAppUser
     */
    protected $user;

    /**
     * @var \stdClass
     */
    protected $request;

    /**
     * @var \stdClass
     */
    protected $session;

    protected $mailium_app_just_installed = false;

    public function __construct(MailiumOauthClient $mailiumOauthClient)
    {
        $this->mailiumOauthClient = $mailiumOauthClient;

        $this->request = new \stdClass();

        $this->session = new \stdClass();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->request->accId = $request->input('accid');
        $this->request->timestamp = $request->input('timestamp');
        $this->request->hmac = $request->input('hmac');
        $this->request->authorizationCode = $request->input('code');
        $this->request->authorizationState = $request->input('state');
        $this->request->queryString = $request->getQueryString();

        $this->session->accId = $request->session()->get('mailium_app_user_accid', null);
        $this->session->authorizationState = $request->session()->get('mailium_authorization_state', null);

        $isOauthValid = null;
        $isTokenValid = null;
        $verifyHmac = $this->request->accId && $this->request->hmac && $this->request->timestamp;

        if ($verifyHmac) {
            $isHmacValid = $this->mailiumOauthClient->verifyHmac($this->request->queryString,
                config('mailium-oauth.client_secret'));
            if ($isHmacValid) {
                $this->setUser($this->request->accId);
            } else {
                return response('not authorized, HMAC validation failed', 401);
            }
        } else {
            if ($this->session->accId) {
                $this->setUser($this->session->accId);
            } else {
                // we don't have a user
            }
        }

        // If this is an authorization request
        if ($this->request->authorizationCode && $verifyHmac) {
            // Check Authorization State
            if ($this->request->authorizationState == $this->session->authorizationState) {
                // Get access token
                $this->mailiumOauthClient->authorize($this->request->authorizationCode);
                $this->user = MailiumAppUser::getByAccId($this->request->accId);

                $this->mailium_app_just_installed = true;
                $request->session()->forget('mailium_authorization_state');
            } else {
                $request->session()->flush();
                return $this->redirectForAuthorization($request);
            }
        }

        // find out if this is an uninstall webhook request
        $uriSegments = $request->segments();
        $isUninstallWebhook = last($uriSegments) === 'uninstall';
        $userExists = $this->user;
        $shouldValidateOauth = $verifyHmac; // validate oauth only if this is a hmac request (to prevent oauth validation on every request)

        if ($userExists && $shouldValidateOauth && !$isUninstallWebhook) {
            // Be sure that the access token works and oauth tokens are valid
            $isOauthValid = $this->mailiumOauthClient->validateOauth($this->user->getOauthTokens());
            $isTokenValid = $this->mailiumOauthClient->verifyToken($this->user->getAccessToken());
            if (!$isTokenValid || !$isOauthValid) {
                $this->user->oauth_tokens = new \stdClass();
                $this->user->save();
            }
        }


        // If we still don't have a correlating app user then we haven't seen this user before, go to authorization url
        if ($this->user) {
            $isOauthValid = $this->mailiumOauthClient->validateOauth($this->user->getOauthTokens());
            if (!$isOauthValid) {
                return $this->redirectForAuthorization($request);
            }
        } else {
            return $this->redirectForAuthorization($request);
        }

        // From here on we are sure that we have a valid app user
        $request->session()->set('mailium_app_user_accid', $this->user->accid);
        $request->session()->forget('mailium_authorization_state');
        $this->mailiumOauthClient->setToken($this->user->getOauthTokens());

        $apiClient = new MailiumAPI3('', $this->user->getAccessToken(), '', 'json');
        // Add attributes to request
        $request->attributes->add(
            [
                'mailium_app_accid' => $this->user->accid,
                'mailium_app_user' => $this->user,
                'mailium_app_just_installed' => $this->mailium_app_just_installed,
                'mailium_api_client' => $apiClient,
            ]
        );
        return $next($request);
    }

    protected function setUser($accId)
    {
        $this->user = MailiumAppUser::getByAccId($accId);
        if ($this->user) {
            $this->mailiumOauthClient->setToken($this->user->getOauthTokens());
        } else {
            $this->user = MailiumAppUser::createUser($accId);
        }
        return $this->user;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function redirectForAuthorization(Request $request)
    {
        $authorizationUrl = $this->mailiumOauthClient->createAuthorizationUrl();
        $state = $this->mailiumOauthClient->getState();
        $request->session()->put('mailium_authorization_state', $state);

        if ($this->mailiumOauthClient->getAppType() == "embedded") {
            $content = $this->mailiumOauthClient->createEmbeddedAppHtmlForRedirection($authorizationUrl);
            return response($content, 200)->header('Content-Type', 'text/html');
        } else {
            return redirect($authorizationUrl);
        }
    }
}
