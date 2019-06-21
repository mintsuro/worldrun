<?php

namespace frontend\widgets\auth;

use yii\base\Widget;
use rmrevin\yii\ulogin\ULogin;

class UloginAuthWidget extends Widget
{
    public function run(): string
    {
        return ULogin::widget([
            // widget look'n'feel
            'display' => ULogin::D_PANEL,

            // required fields
            'fields' => [ULogin::F_EMAIL],

            // optional fields
            'optional' => [],

            // login providers
            'providers' => [ULogin::P_VKONTAKTE, ULogin::P_GOOGLE, ULogin::P_YANDEX],

            // login providers that are shown when user clicks on additonal providers button
            'hidden' => [],

            // where to should ULogin redirect users after successful login
            'redirectUri' => ['network/ulogin-auth'],

            // force use https in redirect uri
            'forceRedirectUrlScheme' => 'http',

            // optional params (can be ommited)
            // force widget language (autodetect by default)
            'language' => ULogin::L_RU,

            // providers sorting ('relevant' by default)
            'sortProviders' => ULogin::S_RELEVANT,

            // verify users' email (disabled by default)
            'verifyEmail' => '0',

            // mobile buttons style (enabled by default)
            'mobileButtons' => '1',
        ]);
    }
}