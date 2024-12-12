<?php

/**
 * public/index.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/controllers/homePageFunction.php';
require_once dirname(__DIR__) . '/src/controllers/listUsersFunction.php';
require_once dirname(__DIR__) . '/src/controllers/userFunction.php';
require_once dirname(__DIR__) . '/src/controllers/createUserFunction.php';
require_once dirname(__DIR__) . '/src/controllers/updateUserFunction.php';
require_once dirname(__DIR__) . '/src/controllers/deleteUserFunction.php';
require_once dirname(__DIR__) . '/src/controllers/listResultsFunction.php';
require_once dirname(__DIR__) . '/src/controllers/resultFunction.php';
require_once dirname(__DIR__) . '/src/controllers/createResultFunction.php';
require_once dirname(__DIR__) . '/src/controllers/updateResultFunction.php';
require_once dirname(__DIR__) . '/src/controllers/deleteResultFunction.php';

use MiW\Results\Utility\Utils;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Exception\{MethodNotAllowedException, ResourceNotFoundException};
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

Utils::loadEnv(dirname(__DIR__));

// Using the symfony/config component to load all routes
$locator = new FileLocator([ dirname(__DIR__) . '/' . $_ENV['CONFIG_DIR'] ]);
$loader  = new YamlFileLoader($locator);
$routes  = $loader->load($_ENV['ROUTES_FILE']);

// Get the HTTP request context
$context = new RequestContext(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);

// Get the matcher object for route resolution
$matcher = new UrlMatcher($routes, $context);

// Get the information associated with the request
$path_info = $_SERVER['REQUEST_URI'] ?? '/';

try {
    $parameters = $matcher->match($path_info);
    $action = $parameters['_controller'];

    // Remove extra parameters that are not part of the route
    unset($parameters['_controller'], $parameters['_route']);

    // Call the controller action with route parameters
    call_user_func_array($action, $parameters);

} catch (ResourceNotFoundException $e) {
    echo 'Caught exception: The resource could not be found' . PHP_EOL;
} catch (MethodNotAllowedException $e) {
    echo 'Caught exception: the resource was found but the request method is not allowed' . PHP_EOL;
}