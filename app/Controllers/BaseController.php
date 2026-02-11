<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\App as AppConfig;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Start session
        $session = session();

        // Determine current language:
        // 1. From session (if user chose),
        // 2. Else from logged-in user profile (if available in session),
        // 3. Else default app locale.
        $config     = new AppConfig();
        $defaultLoc = $config->defaultLocale ?? 'hi';

        $lang = $session->get('language') ?: $defaultLoc;

        // Normalize to supported locales
        if (! in_array($lang, $config->supportedLocales, true)) {
            $lang = $defaultLoc;
        }

        // Set locale for this request
        service('request')->setLocale($lang);

        // Make sure language is accessible in views
        $session->set('language', $lang);
    }



}
