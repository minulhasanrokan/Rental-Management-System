<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        if (\Schema::hasTable('mail_setups')) {
            $mail = DB::table('mail_setups')->first();
            if ($mail) //checking if table is not empty
            {

                $encrypt_method = $this->app_session_name = config('app.app_encrypt_method');
                $secret_key = $this->app_session_name = config('app.app_encrypt_secret_key');
                $secret_iv = $this->app_session_name = config('app.app_encrypt_secret_iv');

                // hash
                $key = hash($this->app_session_name = config('app.app_encrypt_hash'), $secret_key);

                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv = substr(hash($this->app_session_name = config('app.app_encrypt_hash'), $secret_iv), 0, 16);

                // decrypt token;
                $password = openssl_decrypt(base64_decode($mail->email_password), $encrypt_method, $key, 0, $iv);

                $config = array(
                    'driver'     => $mail->email_driver,
                    'transport' =>  $mail->email_mailer,
                    'host'       => $mail->email_host,
                    'port'       => $mail->email_port,
                    'from'       => array('address' => $mail->email_id, 'name' => $mail->email_name),
                    'encryption' => $mail->email_encription,
                    'username'   => $mail->email_username,
                    'password'   => $password,
                    'local_domain' => $mail->system_url,
                    'timeout' => null,
                );
                Config::set('mail', $config);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
