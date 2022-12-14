<?php

namespace PaymentCondition\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module/PaymentCondition", name="admin_config")
 */
class AdminController extends BaseAdminController
{
    /**
     * @Route("", name="view")
     */
    public function viewAction()
    {
        return $this->render('payment-condition/configuration');
    }
}
