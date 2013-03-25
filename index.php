<?php
// Slim PHP
require 'Slim/Slim.php';
require 'Views/TwigView.php';

// Paris and Idiorm
require 'Paris/idiorm.php';
require 'Paris/paris.php';


// Models
require 'models/Reservation.php';
require 'models/UserData.php';
require 'models/Newsletter.php';

// Configuration
TwigView::$twigDirectory = __DIR__ . '/Twig/lib/Twig/';

ORM::configure('mysql:host=localhost;dbname=boboiteapi');
ORM::configure('username', 'root');
ORM::configure('password', '');

// Start Slim.
$app = new Slim(array(
	'view' => new TwigView
));

// Home.
$app->get('/', function() use ($app) {
	$data = Model::factory('Newsletter')
					->order_by_desc('nom')
					->find_many();
					
	return $app->render('blog_home.html', array('data' => $data));		
});

/*----------------------------------------------------------------------------
 *                  RESERVATION
 *---------------------------------------------------------------------------*/
 
// ajouter une résérvation | afficher le formulaire
$app->get('/reservation/add', function() use ($app) {
	return $app->render('reservation_input.html', array('action_name' => 'Add', 'action_url' => 'add'));
});

// ajouter une résérvation | recuperer le formulaire
$app->post('/reservation/add', function() use ($app) {
 
});

// supprimer une résérvation
$app->get('/reservation/delete/(:id)', function($id) use ($app) {
 
});
/*----------------------------------------------------------------------------
 *                  USER DATA BASE
 *---------------------------------------------------------------------------*/
// ajouter les données d'un utilisateur | afficher le formulaire
$app->get('/data/add', function() use ($app) {
	return $app->render('user_input.html', array('action_name' => 'Add', 'action_url' => 'add/'));
});

// ajouter les données d'un utilisateur | recuperer le formulaire
$app->post('/data/add/', function() use ($app) {
        $client 	= Model::factory('UserData')->create();
	$client->nom 	= $app->request()->post('nom');
	$client->prenom = $app->request()->post('prenom');
	$client->mail 	= $app->request()->post('mail');
	$client->zip_code 	= $app->request()->post('zip_code');
	$client->rue 	= $app->request()->post('rue');
	$client->ville 	= $app->request()->post('ville');
	$client->tel 	= $app->request()->post('tel');
	$client->mdp 	= $app->request()->post('mdp');
        if($app->request()->post('newsletter') === 'on'){
            $client->newsletter 	= 1;
        }else{
            $client->newsletter 	= 0;
            
        }
	$client->save();
	
	//$app->redirect('/boBoiteApi/');
});

// modifier les données d'un utilisateur
$app->get('/data/edit/(:id)', function($id) use ($app) {
	$client = Model::factory('UserData')->find_one($id);
	if (! $client instanceof UserData) {
		$app->notFound();
	}	
	
	return $app->render('user_input.html', array(
		'action_name' 	=> 	'Edit', 
		'action_url' 	=> 	'' . $id,
		'data'		=> 	$client
	));
});

// modifier les données d'un utilisateur
$app->post('/data/edit/(:id)', function($id) use ($app) {
    $client = Model::factory('UserData')->find_one($id);
    if (! $client instanceof UserData) {
            $app->notFound();
    }

    $client->nom 	= $app->request()->post('nom');
    $client->prenom = $app->request()->post('prenom');
    $client->mail 	= $app->request()->post('mail');
    $client->zip_code 	= $app->request()->post('zip_code');
    $client->rue 	= $app->request()->post('rue');
    $client->ville 	= $app->request()->post('ville');
    $client->tel 	= $app->request()->post('tel');
    $client->mdp 	= $app->request()->post('mdp');
    if($app->request()->post('newsletter') === 'on'){
        $client->newsletter 	= 1;
    }else{
        $client->newsletter 	= 0;

    }

    $client->save();
});
 
// supprimer les données d'un utilisateur
$app->get('/data/delete/(:id)', function($id) use ($app) {
    $client = Model::factory('UserData')->find_one($id);
	if ($client instanceof UserData) {
		$client->delete();
    }
});
/*----------------------------------------------------------------------------
 *                  NEWSLETTER
 *---------------------------------------------------------------------------*/

// inscription à une newsletter | afficher le formulaire
$app->get('/newsletter/add', function() use ($app) {
	return $app->render('add_newsletter.html', array('action_name' => 'Add', 'action_url' => 'add'));
});

// inscription à une newsletter | recuperer le formulaire
$app->post('/newsletter/add', function() use ($app) {
    $client 			= Model::factory('Newsletter')->create();
	$client->nom 	= $app->request()->post('nom');
	$client->prenom 	= $app->request()->post('prenom');
	$client->mail 	= $app->request()->post('mail');
	$client->save();
	
	$app->redirect('/boBoiteApi/');
});

// modifier info newsletter | afficher le formulaire
$app->get('/newsletter/edit/(:id_newsletter)', function($id_newsletter) use ($app) {
	$client = Model::factory('Newsletter')->find_one($id_newsletter);
	if (! $client instanceof Newsletter) {
		$app->notFound();
	}	
	
	return $app->render('add_newsletter.html', array(
		'action_name' 	=> 	'Edit', 
		'action_url' 	=> 	'' . $id_newsletter,
		'data'		=> 	$client
	));
});

// modifier info newsletter | recuperer le formulaire
$app->post('/newsletter/edit/(:id)', function($id) use ($app) {
	$client = Model::factory('Newsletter')->find_one($id);
	if (! $client instanceof Newsletter) {
		$app->notFound();
	}
	
	$client->nom 	= $app->request()->post('nom');
	$client->prenom = $app->request()->post('prenom');
	$client->mail 	= $app->request()->post('mail');
	
	$client->save();
	
	//$app->redirect('/boBoiteApi/');
});

// enlever l'utilisateur de la base de donné newsletter
$app->get('/newsletter/delete/(:id)', function($id) use ($app) {
	$client = Model::factory('Newsletter')->find_one($id);
	if ($client instanceof Newsletter) {
		$client->delete();
	}
	
	
});

// Slim Run.
$app->run();