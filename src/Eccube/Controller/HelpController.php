<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
define ( 'WP_USE_THEMES', false );
require_once (WORDPRESS_PATH . "/wp-blog-header.php");
class HelpController extends AbstractController
{
    /**
     * HelpController constructor.
     */
    public function __construct()
    {
    }

    /**
     * 特定商取引法.
     *
     * @Route("/help/tradelaw", name="help_tradelaw")
     * @Template("Help/tradelaw.twig")
     */
    public function tradelaw()
    {
        return [];
    }

    /**
     * ご利用ガイド.
     *
     * @Route("/guide", name="help_guide")
     * @Template("Help/description.twig")
     */
    public function guide()
    {
    	return [];
    }

   /**
     * ご利用ガイド.
     *
     * @Route("/guide/description", name="help_description")
     * @Template("Help/description.twig")
     */
    public function description()
    {
    	return [];
    }

/**
     * ご利用ガイド.
     *
     * @Route("/guide/rule", name="guide_rule")
     * @Template("Help/rule.twig")
     */
    public function rule()
    {
    	return [];
    }


	/**
     * ご利用ガイド.
     *
     * @Route("/guide/delivery", name="guide_delivery")
     * @Template("Help/delivery.twig")
     */
    public function delivery()
    {
    	return [];
    }

	/**
     * ご利用ガイド.
     *
     * @Route("/guide/cancel", name="guide_cancel")
     * @Template("Help/cancel.twig")
     */
    public function cancel()
    {
    	return [];
    }


	/**
     * ご利用ガイド.
     *
     * @Route("/guide/discount", name="guide_discount")
     * @Template("Help/discount.twig")
     */
    public function discount()
    {
    	return [];
    }

	/**
     * ご利用ガイド.
     *
     * @Route("/guide/qa", name="guide_qa")
     * @Template("Help/qa.twig")
     */
    public function qa()
    {
    	return [];
    }




    
    /**
     * 当サイトについて.
     *
     * @Route("/help/about", name="help_about")
     * @Template("Help/about.twig")
     */
    public function about()
    {
        return [];
    }

    /**
     * プライバシーポリシー.
     *
     * @Route("/help/privacy", name="help_privacy")
     * @Template("Help/privacy.twig")
     */
    public function privacy()
    {
        return [];
    }

    /**
     * 利用規約.
     *
     * @Route("/help/agreement", name="help_agreement")
     * @Template("Help/agreement.twig")
     */
    public function agreement()
    {
        return [];
    }
}
