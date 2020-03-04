<?php

namespace PaymentCondition\EventListeners;

use CustomerFamily\Model\CustomerCustomerFamilyQuery;
use PaymentCondition\Model\Map\PaymentAreaConditionTableMap;
use PaymentCondition\Model\Map\PaymentCustomerConditionTableMap;
use PaymentCondition\Model\Map\PaymentCustomerFamilyConditionTableMap;
use PaymentCondition\Model\Map\PaymentCustomerModuleConditionTableMap;
use PaymentCondition\Model\Map\PaymentDeliveryConditionTableMap;
use PaymentCondition\Model\PaymentCustomerConditionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Loop\LoopExtendsBuildModelCriteriaEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Model\AddressQuery;
use Thelia\Model\Map\CountryAreaTableMap;
use Thelia\Model\Map\CustomerTableMap;
use Thelia\Model\Map\ModuleTableMap;
use Thelia\Model\ModuleQuery;
use Thelia\Model\Order;

class PaymentLoopExtend implements EventSubscriberInterface
{
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function paymentDeliveryCondition(LoopExtendsBuildModelCriteriaEvent $event)
    {
        /** @var Order $order */
        $order = $this->request->getSession()->get('thelia.order');

        if (null === $order) {
            return;
        }

        $deliveryModuleId = $order->getDeliveryModuleId();

        if (null === $deliveryModuleId) {
            return;
        }

        $join = new Join();
        $join->addExplicitCondition(
            ModuleTableMap::TABLE_NAME,
            'ID',
            null,
            PaymentDeliveryConditionTableMap::TABLE_NAME,
            'PAYMENT_MODULE_ID',
            null
        );

        $join->setJoinType(Criteria::JOIN);
        $event->getModelCriteria()->addJoinObject($join, 'payment_delivery_condition_join')
            ->addJoinCondition('payment_delivery_condition_join', PaymentDeliveryConditionTableMap::DELIVERY_MODULE_ID . ' = ' . $deliveryModuleId)
            ->addJoinCondition('payment_delivery_condition_join', PaymentDeliveryConditionTableMap::IS_VALID . ' = 1');
    }

    public function paymentCustomerFamilyCondition(LoopExtendsBuildModelCriteriaEvent $event)
    {
        /** @var Session $session */
        $session = $this->request->getSession();

        $customer = $session->getCustomerUser();

        if (null === $customer) {
            return null;
        }

        $customerCustomerFamily = CustomerCustomerFamilyQuery::create()
            ->findOneByCustomerId($customer->getId());

        if (null === $customerCustomerFamily) {
            return null;
        }

        $join = new Join();
        $join->addExplicitCondition(
            ModuleTableMap::TABLE_NAME,
            'ID',
            null,
            PaymentCustomerFamilyConditionTableMap::TABLE_NAME,
            'PAYMENT_MODULE_ID',
            null
        );

        $join->setJoinType(Criteria::JOIN);
        $event->getModelCriteria()->addJoinObject($join, 'payment_customer_family_condition_join')
            ->addJoinCondition('payment_customer_family_condition_join', PaymentCustomerFamilyConditionTableMap::CUSTOMER_FAMILY_ID.' = '.$customerCustomerFamily->getCustomerFamilyId())
            ->addJoinCondition('payment_customer_family_condition_join', PaymentCustomerFamilyConditionTableMap::IS_VALID . ' = 1');
    }

    public function paymentAreaCondition(LoopExtendsBuildModelCriteriaEvent $event)
    {
        /** @var Order $order */
        $order = $this->request->getSession()->get('thelia.order');

        if (null === $order) {
            return;
        }

        $chosenDeliveryAddressId = $order->getChoosenDeliveryAddress();

        if (null === $chosenDeliveryAddressId) {
            return;
        }

        $chosenDeliveryAddress = AddressQuery::create()->findOneById($chosenDeliveryAddressId);

        if (null === $chosenDeliveryAddress) {
            return;
        }

        $chosenCountryId = $chosenDeliveryAddress->getCountryId();

        $query = ModuleTableMap::ID . ' IN (SELECT '.PaymentAreaConditionTableMap::PAYMENT_MODULE_ID.' FROM '.PaymentAreaConditionTableMap::TABLE_NAME
            .' WHERE '.PaymentAreaConditionTableMap::IS_VALID.' = 1 AND area_id IN'
            .' (SELECT '.CountryAreaTableMap::AREA_ID.' FROM '.CountryAreaTableMap::TABLE_NAME.' WHERE '.CountryAreaTableMap::COUNTRY_ID.' = '.$chosenCountryId.')'
            .')';

        $event->getModelCriteria()->add(ModuleTableMap::ID, $query, Criteria::CUSTOM);
    }

    public function paymentCustomerCondition(LoopExtendsBuildModelCriteriaEvent $event)
    {
        $criteria = $event->getModelCriteria();
        $customer = $this->request->getSession()->getCustomerUser();

        if (null === $customer) {
            return;
        }

        $paymentCustomerCondition = PaymentCustomerConditionQuery::create()
            ->findOneByCustomerId($customer->getId());

        if (null === $paymentCustomerCondition || false == $paymentCustomerCondition->getModuleRestrictionActive()) {
            return;
        }

        // Join customer module condition to know if the payment module is valid
        $paymentModuleConditionJoin = new Join();
        $paymentModuleConditionJoin->addExplicitCondition(
            ModuleTableMap::TABLE_NAME,
            'ID',
            null,
            PaymentCustomerModuleConditionTableMap::TABLE_NAME,
            'PAYMENT_MODULE_ID',
            null
        );

        $paymentModuleConditionJoin->setJoinType(Criteria::JOIN);
        $criteria->addJoinObject($paymentModuleConditionJoin, 'payment_customer_module_condition_join')
            ->addJoinCondition('payment_customer_module_condition_join', PaymentCustomerModuleConditionTableMap::COL_CUSTOMER_ID.' = '.$customer->getId())
            ->addJoinCondition('payment_customer_module_condition_join', PaymentCustomerModuleConditionTableMap::COL_IS_VALID.' = 1');
    }

    public static function getSubscribedEvents()
    {
        $events = [];

        $events[TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_BUILD_MODEL_CRITERIA, 'payment')][] = ['paymentDeliveryCondition', '64'];
        $events[TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_BUILD_MODEL_CRITERIA, 'payment')][] = ['paymentAreaCondition', '62'];

        $customFamilyModuleIsActive = ModuleQuery::create()
            ->filterByCode('CustomerFamily')
            ->filterByActivate(1)
            ->findOne();

        if (null !== $customFamilyModuleIsActive) {
            $events[TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_BUILD_MODEL_CRITERIA, 'payment')][] = ['paymentCustomerFamilyCondition', '63'];

        }

        $events[TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_BUILD_MODEL_CRITERIA, 'payment')][] = ['paymentCustomerCondition', '60'];

        return $events;
    }
}
