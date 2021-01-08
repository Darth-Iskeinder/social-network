<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use LinkedIn\Client;
use LinkedIn\Scope;

class LinkedInController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function getProfileData()
    {
        session_start();
        $client = new Client(
            '860qd9fjh95nea',
            'H1hhuvR7TFNIcZH4'
        );
        /*dump($client); die();*/
       /* dump($_GET); die();*/
        if (isset($_GET['code'])) {
            if (isset($_GET['state']) &&
                isset($_SESSION['state']) &&
                $_GET['state'] === $_SESSION['state']
            ) {
                try{
                   /* $scopes = [
                        'r_liteprofile',
                        Scope::READ_EMAIL_ADDRESS,
                    ];
                    $loginUrl = $client->getLoginUrl($scopes);
                    dump($loginUrl); die();*/
                   /* dump($_GET['code']); die();*/
                    /*dump($_SESSION['redirect_url']); die();*/
                    $client->setRedirectUrl($_SESSION['redirect_url']);
                    $accessToken = $client->getAccessToken($_GET['code']);

                    $this->h1('Access Token');
                    $this->pp($accessToken);
                    $this->h1('Profile');

                    $profile = $client->get(
                        'me'
                    );

                    $this->pp($profile);
                    $emailInfo = $email = $client->get(
                        'emailAddress',
                        ['q' => 'members', 'projection' => '(elements*(handle~)']
                    );
                    $this->pp($emailInfo);
                } catch (\LinkedIn\Exception $exception){
                    $this->pp($exception->getMessage());
                    $this->pp($exception->getCode());

                    $this->pp($_SESSION);
                }
                echo '<a href="/">Start over</a>';
            } else {
                echo 'Invalid state';
                $this->pp($_GET);
                $this->pp($_SESSION);
                echo '<a href="/">Start over</a>';
            }
        } elseif (isset($_GET['error'])){
            $this->pp($_GET);
            echo '<a href="/">Start over</a>';
        } else {
            $scopes = [
                'r_liteprofile',
                Scope::READ_EMAIL_ADDRESS,
            ];
            $loginUrl = $client->getLoginUrl($scopes);
            $_SESSION['state'] = $client->getState();
            $_SESSION['redirect_url'] = $client->getRedirectUrl();
            echo 'LoginUrl: <a href="'. $loginUrl.'">'.$loginUrl.'</a>';
        }
        return $this->render('index.html.twig');
    }

    /**
     * Pretty print whatever passed in
     *
     * @param mixed $anything
     */
    function pp($anything)
    {
        echo '<pre>' . print_r($anything, true) . '</pre>';
    }

    /**
     * Add header
     *
     * @param string $h
     */
    function h1($h) {
        echo '<h1>' . $h . '</h1>';
    }

}