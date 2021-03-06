<?php
namespace Wizin\Bundle\SimpleCmsBundle\Event;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Wizin\Bundle\SimpleCmsBundle\Controller\FrontController;
use Wizin\Bundle\SimpleCmsBundle\Controller\AdminController;

class FilterControllerListener
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param EventDispatcher $dispatcher
     */
    public function setEventDispatcher(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller) === false) {
            return;
        }
        if ($controller[0] instanceof FrontController) {
            $event = new FrontControllerEvent(
                $event->getKernel(),
                $controller,
                $event->getRequest(),
                $event->getRequestType()
            );
            $this->dispatcher->dispatch(Event::ON_FRONT_CONTROLLER, $event);
        } elseif ($controller[0] instanceof AdminController) {
            $event = new AdminControllerEvent(
                $event->getKernel(),
                $controller,
                $event->getRequest(),
                $event->getRequestType()
            );
            $this->dispatcher->dispatch(Event::ON_ADMIN_CONTROLLER, $event);
        }
    }
}