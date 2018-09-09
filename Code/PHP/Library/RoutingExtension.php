<?php
/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 17.01.17 21:00
 */

namespace mgbs\Library;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RoutingExtension extends \Twig_Extension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions() : array
    {
        return array(
            new \Twig_SimpleFunction(
                'url',
                array($this, 'getUrl'),
                array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))
            ),
            new \Twig_SimpleFunction(
                'path',
                array($this, 'getPath'),
                array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))
            ),
        );
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool $relative
     * @return string
     */
    public function getPath($name, array $parameters = array(), $relative = false): string
    {
        return $this->generator->generate(
            $name,
            $parameters,
            $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool $schemeRelative
     * @return string
     */
    public function getUrl($name, array $parameters = array(), $schemeRelative = false): string
    {
        return $this->generator->generate(
            $name,
            $parameters,
            $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * Determines at compile time whether the generated URL will be safe and thus
     * saving the unneeded automatic escaping for performance reasons.
     *
     * The URL generation process percent encodes non-alphanumeric characters. So there is no risk
     * that malicious/invalid characters are part of the URL. The only character within an URL that
     * must be escaped in html is the ampersand ("&") which separates query params. So we cannot mark
     * the URL generation as always safe, but only when we are sure there won't be multiple query
     * params. This is the case when there are none or only one constant parameter given.
     * E.g. we know beforehand this will be safe:
     * - path('route')
     * - path('route', {'param': 'value'})
     * But the following may not:
     * - path('route', var)
     * - path('route', {'param': ['val1', 'val2'] }) // a sub-array
     * - path('route', {'param1': 'value1', 'param2': 'value2'})
     * If param1 and param2 reference placeholder in the route, it would still be safe. But we don't know.
     *
     * @param \Twig_Node $argsNode The arguments of the path/url function
     *
     * @return array An array with the contexts the URL is safe
     */
    public function isUrlGenerationSafe(\Twig_Node $argsNode): array
    {
        // support named arguments
        if ($argsNode->hasNode(1)) {
            $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : $argsNode->getNode(1);
        } else {
            $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : null;
        }

        if (null === $paramsNode || ($paramsNode instanceof \Twig_Node_Expression_Array && \count($paramsNode) <= 2 &&
                (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof \Twig_Node_Expression_Constant))
        ) {
            return array('html');
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'routing';
    }
}
