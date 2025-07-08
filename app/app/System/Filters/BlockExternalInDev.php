<?php
/** @author: Adam Pawełczyk */

namespace App\System\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class BlockExternalInDev implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): ?ResponseInterface
    {
        if (ENVIRONMENT === 'development') {
            $ip = $request->getIPAddress();

            if (! $this->isAllowedIp($ip)) {
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

    private function isAllowedIp(string $ip): bool
    {
        $allowed = ['127.0.0.1', '::1'];

        if (in_array($ip, $allowed)) {
            return true;
        }

        if (str_starts_with($ip, '192.168.')) {
            return true;
        }

        return false;
    }
}
