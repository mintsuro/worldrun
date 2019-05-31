<?php

namespace cabinet\entities\user;

use cabinet\entities\user\Profile;
use cabinet\helpers\ProfileHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\helpers\Json;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Network[] $networks
 * @property Profile   $profile
 * @property Strava  $strava
 */
class User extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    public static function create(string $username, string $email, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_INACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->profile = Profile::createLight($username);

        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * Edit data for admin page user
     */
    public function edit(string $username, string $email): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->updated_at = time();

        $this->profile->first_name = $username;
        $this->profile->save();
    }

    /**
     * @param string $email
     * Edit data for cabinet user
     */
    public function editProfile(string $email): void
    {
        $this->email = $email;
        $this->updated_at = time();
    }

    public function editUsername(string $username): void
    {
        $this->username = $username;
        $this->updated_at = time();
    }

    public static function requestSignup(string $username, string $email): array
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $password = mt_rand(1000, 9999);
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_INACTIVE;
        $user->verification_token = Yii::$app->security->generateRandomString(10); // . '_' . time()
        $user->generateAuthKey();
        $user->profile = Profile::createLight($username);

        return array('userObject' => $user, 'password' => $password);
    }

    /**
     * @param $network
     * @param $identity
     * @param string $username
     * @param string $email
     * @param string $profileData
     * @return array
     * @throws \yii\base\Exception
     */
    public static function signupByNetwork($network, $identity, string $username,
        string $email, string $profileData): array
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $password = mt_rand(1000, 9999);
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_INACTIVE;
        $user->verification_token = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        $user->networks = [Network::create($network, $identity)];
        $user->profile = Profile::create($profileData);

        return array('userObject' => $user, 'password' => $password);
    }

    /** Network $current */
    public function attachNetwork($network, $identity): void
    {
        $networks = $this->networks;
        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException('Соц. сеть уже были привязана.');
            }
        }
        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function attachStrava($token): void
    {
        $strava = $this->strava;

        /*if($strava->isFor($token)){
            throw new \DomainException('Профиль Strava уже была привязан.');
        } */

        $strava = Strava::create($token);
        $this->strava = $strava;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('Этот пользователь уже активен.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->verification_token = null;
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Сброс пароля уже запрошен.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Сброс пароля не требуется.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::class, ['user_id' => 'id']);
    }

    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    public function getStrava(): ActiveQuery
    {
        return $this->hasOne(Strava::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['networks', 'profile', 'strava'],
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function attributeLabels(){
        return [
            'username' => 'Имя',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата добавления',
            'status' => 'Статус',
        ];
    }
}