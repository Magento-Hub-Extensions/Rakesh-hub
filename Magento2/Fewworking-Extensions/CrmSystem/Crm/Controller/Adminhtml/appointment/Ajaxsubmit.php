<?php

namespace Ueg\Crm\Controller\Adminhtml\appointment;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Ueg\Crm\Model\AppointmentFactory;
use Magento\User\Model\UserFactory;
use \Magento\Backend\Model\Auth\Session as AdminSession;
use \Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class Ajaxsubmit extends \Magento\Backend\App\Action {

    /**
     * @var PageFactory
     */
    protected $resultPagee;
    protected $appointmentFactory;
    protected $userFactory;
    protected $authSession;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
    Context $context, PageFactory $resultPageFactory,
            AppointmentFactory $appointmentFactory,
            UserFactory $userFactory,
            AdminSession $authSession,
            DateTimeFactory $dateTimeFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->appointmentFactory = $appointmentFactory;
        $this->userFactory = $userFactory;
        $this->authSession = $authSession;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute() {
        $customerId = $this->getRequest()->getPost('customer_id');
        if(isset($customerId) && !empty($customerId)) {
            $formVar = $this->getRequest()->getPost( 'form_var' );
            if ( isset( $formVar ) && ! empty( $formVar ) ) {
                parse_str( $formVar, $formParams );
                //echo '<pre>'; print_r( $formParams ); echo '</pre>';

                $appointmentModel = $this->appointmentFactory->create();

                if(isset($formParams['appointment'])) {
                    $param = $formParams['appointment'];

                    $param['customer_id']    = $customerId;

                    $repUserId = $param['rep_user_id'];
                    $rep = $this->userFactory->create()->load($repUserId);
                    $param['rep_user_name'] = $rep->getUsername();
                    $param['rep_user_fullname'] = $rep->getFirstname() .' '. $rep->getLastname();

                    $user = $this->authSession->getUser();
                    $param['created_user_id'] = $user->getUsername();
                    $param['created_user_name'] = $user->getUsername();
                    $param['created_user_fullname'] = $user->getFirstname() .' '. $user->getLastname();

                    $param['appointment_time'] =$this->dateTimeFactory->create()->gmtDate(null, strtotime($param['appointment_time']));
                    $currentTime = $this->dateTimeFactory->create()->gmtDate();
                    $param['created_at']     = $currentTime;
                    //$param['update_at']      = $currentTime;

                    //echo '<pre>'; print_r( $param ); echo '</pre>';

                    $appointmentModel->setData($param)->save();
                    $this->_eventManager->dispatch('customer_crm_appointment_create_after', array('customer_id'=>$customerId));
                }
            }
        }

        echo 1;
    }

}

?>