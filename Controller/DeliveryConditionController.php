<?php

namespace PaymentCondition\Controller;

use PaymentCondition\Model\PaymentDeliveryCondition;
use PaymentCondition\Model\PaymentDeliveryConditionQuery;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\ModuleQuery;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module/paymentcondition/delivery", name="payment_condition_delivery_condition_")
 */
class DeliveryConditionController extends BaseAdminController
{
    /**
     * @Route("", name="view", methods="GET")
     */
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

    /**
     * @Route("", name="save", methods="POST")
     */
    public function saveAction(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        try {
            $paymentId = $request->request->get("paymentId");
            $deliveryId = $request->request->get("deliveryId");
            $isValid = $request->request->get("isValid") == "true" ? 1 : 0;

            $paymentDelivery = PaymentDeliveryConditionQuery::create(   )
                ->filterByPaymentModuleId($paymentId)
                ->filterByDeliveryModuleId($deliveryId)
                ->findOneOrCreate();

            $paymentDelivery->setIsValid($isValid)
                ->save();

        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
        return new JsonResponse("Success");
    }
}
