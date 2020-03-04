<?php

namespace PaymentCondition\Controller;

use PaymentCondition\Model\PaymentDeliveryCondition;
use PaymentCondition\Model\PaymentDeliveryConditionQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\ModuleQuery;

class AdminController extends BaseAdminController
{
    public function viewAction()
    {
        return $this->render('payment-condition/configuration');
    }
}
