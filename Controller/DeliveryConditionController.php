<?php

namespace PaymentCondition\Controller;

use PaymentCondition\Model\PaymentDeliveryCondition;
use PaymentCondition\Model\PaymentDeliveryConditionQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\ModuleQuery;

class DeliveryConditionController extends BaseAdminController
{
    public function viewAction()
    {
        $paymentDeliveryConditionArray = [];

        $paymentModules = ModuleQuery::create()
            ->findByCategory('payment');

        $deliveryModules = ModuleQuery::create()
            ->findByCategory('delivery');

        $paymentDeliveryConditions = PaymentDeliveryConditionQuery::create()
            ->find();

        if (null !== $paymentDeliveryConditions) {
            /** @var PaymentDeliveryCondition $paymentDeliveryCondition */
            foreach ($paymentDeliveryConditions as $paymentDeliveryCondition) {
                $paymentDeliveryConditionArray[$paymentDeliveryCondition->getPaymentModuleId()][$paymentDeliveryCondition->getDeliveryModuleId()] = $paymentDeliveryCondition->getIsValid();
            }
        }

        return $this->render('payment-condition/delivery', [
            'paymentModules' => $paymentModules,
            'deliveryModules' => $deliveryModules,
            "paymentDeliveryCondition" => $paymentDeliveryConditionArray
        ]);
    }

    public function saveAction()
    {
        $request = $this->getRequest();

        try {
            $paymentId = $request->request->get("paymentId");
            $deliveryId = $request->request->get("deliveryId");
            $isValid = $request->request->get("isValid") == "true" ? 1 : 0;

            $paymentDelivery = PaymentDeliveryConditionQuery::create()
                ->filterByPaymentModuleId($paymentId)
                ->filterByDeliveryModuleId($deliveryId)
                ->findOneOrCreate();

            $paymentDelivery->setIsValid($isValid)
                ->save();

        } catch (\Exception $e) {
            return JsonResponse::create($e->getMessage(), 500);
        }
        return JsonResponse::create("Success");
    }
}
