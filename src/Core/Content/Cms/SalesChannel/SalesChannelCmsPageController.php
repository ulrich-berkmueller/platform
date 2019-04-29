<?php declare(strict_types=1);

namespace Shopware\Core\Content\Cms\SalesChannel;

use Shopware\Core\Content\Cms\CmsPageDefinition;
use Shopware\Core\Content\Cms\Exception\PageNotFoundException;
use Shopware\Core\Framework\Api\Response\ResponseFactoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesChannelCmsPageController extends AbstractController
{
    /**
     * @var SalesChannelCmsPageLoader
     */
    private $cmsPageLoader;

    public function __construct(SalesChannelCmsPageLoader $cmsPageLoader)
    {
        $this->cmsPageLoader = $cmsPageLoader;
    }

    /**
     * @Route("/sales-channel-api/v1/cms-page/{pageId}", name="sales-channel-api.cms.page", methods={"GET"})
     */
    public function getPage(string $pageId, Request $request, SalesChannelContext $context, ResponseFactoryInterface $responseFactory): Response
    {
        $pages = $this->cmsPageLoader->load($request, new Criteria([$pageId]), $context);

        if (!$pages->has($pageId)) {
            throw new PageNotFoundException($pageId);
        }

        return $responseFactory->createDetailResponse(
            $pages->get($pageId),
            CmsPageDefinition::class,
            $request,
            $context->getContext()
        );
    }
}
