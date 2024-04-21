<?php 

require __DIR__.'/includes/app.php';

use App\Http\Router;

$obRouter = new Router(URL);

include 'routes/api.php';
include 'routes/pages.php';

$obRouter->run()->sendReponse();