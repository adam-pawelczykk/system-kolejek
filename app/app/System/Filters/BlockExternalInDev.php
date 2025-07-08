<?php
/** @author: Adam Pawełczyk */

namespace App\System\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class BlockExternalInDev implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (ENVIRONMENT === 'development') {
            $ip = $request->getIPAddress();

            if (!in_array($ip, ['127.0.0.1', '::1'])) {
                return Services::response()
                    ->setStatusCode(403)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Access denied in development mode.'
                    ]);
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
