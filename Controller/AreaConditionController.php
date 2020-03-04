<?php

namespace PaymentCondition\Controller;

use PaymentCondition\Model\PaymentAreaCondition;
use PaymentCondition\Model\PaymentAreaConditionQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\AreaQuery;
use Thelia\Model\ModuleQuery;

class AreaConditionController extends BaseAdminController
{
    public function viewAction()
    {
        $areaPaymentConditionArray = [];

        $paymentModules = ModuleQuery::create()
            ->findByCategory('payment');

        $shippingAreas = AreaQuery::create()->find();

        $paymentAreaConditions = PaymentAreaConditionQuery::create()
            ->find();

        if (null !== $paymentAreaConditions) {
            /** @var PaymentAreaCondition $paymentAreaCondition */
            foreach ($paymentAreaConditions as $paymentAreaCondition) {
                $areaPaymentConditionArray[$paymentAreaCondition->getPaymentModuleId()][$paymentAreaCondition->getAreaId()] = $paymentAreaCondition->getIsValid();
            }
        }

        return $this->render('payment-condition/shipping_area', [
            'paymentModules' => $paymentModules,
            'shippingAreas' => $shippingAreas,
            "areaPaymentCondition" => $areaPaymentConditionArray
        ]);
    }

    public function saveAction()
    {
        $request = $this->getRequest();

        try {
            $paymentId = $request->request->get("paymentId");
            $areaId = $request->request->get("areaId");
            $isValid = $request->request->get("isValid") == "true" ? 1 : 0;

            $paymentArea = PaymentAreaConditionQuery::create()
                ->filterByPaymentModuleId($paymentId)
                ->filterByAreaId($areaId)
                ->findOneOrCreate();

            $paymentArea->setIsValid($isValid)
                ->save();

        } catch (\Exception $e) {
            return JsonResponse::create($e->getMessage(), 500);
        }
        return JsonResponse::create("Success");
    }
}
