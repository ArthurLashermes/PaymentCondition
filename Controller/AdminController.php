<?php

namespace PaymentCondition\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module/PaymentCondition", name="payment_condition_admin_config_")
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
