<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register1()
    {
        if (isModuleActive('LmsSaas')) {
            $config = array(
                'driver' => saasEnv('MAIL_DRIVER'),
                'host' => saasEnv('MAIL_HOST'),
                'port' => saasEnv('MAIL_PORT'),
                'from' => array('address' => saasEnv('MAIL_FROM_ADDRESS'), 'name' => saasEnv('MAIL_FROM_NAME')),
                'encryption' => saasEnv('MAIL_ENCRYPTION'),
                'username' => saasEnv('MAIL_USERNAME'),
                'password' => saasEnv('MAIL_PASSWORD'),
                'sendmail' => '/usr/sbin/sendmail -bs',
                'pretend' => false,
            );
            Config::set('mail', $config);
        }
    }

    public function register()
    {
        if (isModuleActive('LmsSaas')) {

            Config::set('mail.default', saasEnv('MAIL_MAILER', 'smtp'));

            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => saasEnv('MAIL_HOST'),
                'port' => saasEnv('MAIL_PORT'),
                'encryption' => saasEnv('MAIL_ENCRYPTION'),
                'username' => saasEnv('MAIL_USERNAME'),
                'password' => saasEnv('MAIL_PASSWORD'),
                'timeout' => null,
                'local_domain' => null,
            ]);

            Config::set('mail.from', [
                'address' => saasEnv('MAIL_FROM_ADDRESS'),
                'name' => saasEnv('MAIL_FROM_NAME'),
            ]);
        }
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
