<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
//            KernelEvents::RESPONSE => ['onKernelResponse', 0],
        ];
    }

//    public function onKernelResponse(ResponseEvent $event): void
//    {
//        $request = $event->getRequest();
//        $response = $event->getResponse();
//
//        // Добавляем заголовки только для API запросов
//        if (str_starts_with($request->getPathInfo(), '/api/')) {
//            $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');
//            $response->headers->set('Access-Control-Allow-Credentials', 'true');
//            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
//            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//            $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
//        }
//    }
}