<?php
namespace HomeOffice\CtsBundle\Routing;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router as BaseRouter;

/**
 * Class Router
 * @package HomeOffice\CtsBundle\Routing
 */
class Router implements RouterInterface
{
    /**
     * @var BaseRouter
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var bool
     */
    private $sslRedirects;

    /**
     * Constructor
     *
     * @param BaseRouter   $router
     * @param RequestStack $request
     * @param bool         $sslRedirects
     */
    public function __construct(BaseRouter $router, RequestStack $request, $sslRedirects = true)
    {
        $this->router = $router;
        $this->request = $request;
        $this->sslRedirects = $sslRedirects;
    }

    /**
     * {@inheritdoc}
     *
     * We decorated the router with this class to force all generated absolute URLs to use the https scheme even if the
     * referral URL was http. We require this as the data transfer between the proxy and the app is over http where as
     * the site actually sits behind SSL. If we were to simply force routes to require https then the proxy wouldn't be
     * able to communicate with the app.
     *
     * If you go directly to a url:80 then the site loads correctly. But if the app redirects to a new absolute URL
     * (e.g. /login_check) then the URL generated with use SSL.
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $url = $this->router->generate($name, $parameters, $referenceType);
        // Hack to work with ACP - Strip the port
        $url = preg_replace('/\:[0-9]{2,5}/','', $url);

        return $this->sslRedirects ? preg_replace('/^http\:\/\//', 'https://', $url) : $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        return $this->router->match($pathinfo);
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->router->getContext();
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->router->setContext($context);
    }
}
