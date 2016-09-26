<?php namespace MailiumOauthClient\MailiumOauthClientLaravel;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MailiumAppUser
 * @package MailiumOauthClient\MailiumOauthClientLaravel
 * @property string $accid
 * @property array $oauth_tokens
 */
class MailiumAppUser extends Model
{

    # ---------------------------------------------------------------------------------------------- Eloquent Attributes
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mailium_app_users';

    /**
     * The database columns used by the model.
     *
     * @var array
     */
    protected $columns = ['id', 'accid', 'oauth_tokens', 'created_at', 'updated_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['accid', 'oauth_tokens'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends =[];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'oauth_tokens' => 'object',
    ];

    # ---------------------------------------------------------------------------------------------------- Relationships
    //

    # ------------------------------------------------------------------------------------------------------- Attributes
    //

    # ----------------------------------------------------------------------------------------------------------- Scopes
    //

    # ---------------------------------------------------------------------------------------------------------- Methods

    public static function createUser($accId)
    {
        $appUser = static::getByAccId($accId);
        if (is_null($appUser)) {
            $appUser = new MailiumAppUser();
            $appUser->accid = $accId;
            $appUser->oauth_tokens = new \stdClass();
            $appUser->save();
        }
        return $appUser;

    }

    public static function getByAccId($accId)
    {
        return static::where('accid', $accId)->first();
    }

    public function getAccessToken()
    {
        if (! is_object($this->oauth_tokens) || ! isset($this->oauth_tokens->access_token)) {
            return '';
        }
        return $this->oauth_tokens->access_token;
    }

    public function getOauthTokens()
    {
        return $this->oauth_tokens;
    }

    public static function saveOauthToken($resourceOwner, $oauthTokens)
    {
        $appUser = static::getByAccId($resourceOwner->acc_id);
        if (is_null($appUser)) {
            $appUser = new MailiumAppUser();
            $appUser->accid = $resourceOwner->acc_id;
        }
        $appUser->oauth_tokens = $oauthTokens;
        $appUser->save();
    }
}
