<?php

namespace App\Controllers;

use App\System\Exception\ValidationException;
use App\System\Validator\DTOValidator;
use App\System\Validator\ValidatableDTO;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    protected function validateDto(string $dtoClass): ValidatableDTO|ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        /** @var DTOValidator $validator */
        $validator = service('validator');

        try {
            return $validator->validate($data, $dtoClass);
        } catch (ValidationException $e) {
            return $this->createValidationErrorResponse($e->errors);
        }
    }

    protected function response(mixed $data, int $statusCode = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON([
                'status' => $statusCode === 200 ? 'success' : 'error',
                'code' => $statusCode,
                'data' => $data,
            ]);
    }

    protected function createNotFoundResponse(string $message): ResponseInterface
    {
        return $this->response(['message' => $message], 404);
    }

    protected function createValidationErrorResponse(array $errors): ResponseInterface
    {
        return $this->response(
            [
                'message' => 'Validation failed',
                'errors' => $errors,
            ],
            422
        );
    }
}
