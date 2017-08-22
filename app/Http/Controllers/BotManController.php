<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use Spatie\SslCertificate\SslCertificate;
use App\Subscriber;

class BotManController extends Controller
{
	/**
	 * Place your BotMan logic here.
	 */
    public function handle()
    {
    	$botman = app('botman');
        $botman->verifyServices(env('TOKEN_VERIFY'));

        // Simple respond method
        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hi there :)');
        });

        //get ssl info by domain
        $botman->hears('ssl-info {domain}', function($bot, $domain){

            try {

                $certificate = SslCertificate::createForHostName($domain);
                $issuer = $certificate->getIssuer();
                $valid = $certificate->isValid() ? 'True' : 'False';
                $expire = $certificate->expirationDate()->diffInDays();

                $bot->reply('Issuer: ' . $issuer);
                $bot->reply('Is Valid: ' . $valid);
                $bot->reply('Expired: ' . $expire . ' days');


            } catch (\Exception $e) {

                $bot->reply('Error! Check domain again.');

            }

        });

        //Subscribe to messages
        $botman->hears('subscribe', function($bot){

            try {

                $user = $bot->getUser();

                $subscriber = new Subscriber();
                $subscriber->recipient_id = $user->getId();
                $subscriber->save();

                $bot->reply('You are subscribed to messages from the admin');

            } catch (\Exception $e) {

                $bot->reply('You are already subscribed');

            }

        });

        //Unsubscribe from messages
        $botman->hears('unsubscribe', function($bot){

            try {
                $user = $bot->getUser();
                $subscriber = Subscriber::where('recipient_id', '=', $user->getId())->firstOrFail();
                $subscriber->delete();
                $bot->reply('You unsubscribed');

            } catch (\Exception $e) {

                $bot->reply('You are not subscribed');

            }

        });

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

}
