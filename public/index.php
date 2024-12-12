<?php

/**
 * public/index.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/controllers.php';

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

// The component also serves to display route information by its name
// echo '<br>---' . PHP_EOL . '<pre>Inverse "admin_route": ';
// var_dump($routes->get('admin_route')->getPath());
// echo '</pre>';