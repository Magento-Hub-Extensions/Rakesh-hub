<?php
namespace Ueg\Crm\Block\Adminhtml\Crmcustomer\View;


/**
 * Customer account form block
 */
class Appointment extends \Magento\Backend\Block\Template {

	protected $_registry;
	protected $appointmentoptionsModel;


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Framework\Registry $registry,
    \Ueg\Crm\Model\Appointmentoptions $appointmentoptionsModel,
            array $data = []
    ) {
    	$this->_registry = $registry;
    	$this->appointmentoptionsModel = $appointmentoptionsModel;

        parent::__construct($context, $data);
    }


    public function _prepareLayout() {
        $gridBlock = $this->getLayout()->createBlock(
                'Ueg\Crm\Block\Adminhtml\Crmcustomer\View\Appointment\Grid', 'appointment.grid'
        );
        $this->setChild('grid', $gridBlock);
        return parent::_prepareLayout();
    }
    public function getContactList()
	{
		$list = array();

		$customer =$this->_registry->registry('current_customer');

		if (!$customer) {
			return $list;
		}

		$list[] = $customer->getEmail();

		foreach ($customer->getAddresses() as $address) {
			if($address->getTelephone()) {
				$list[] = $address->getTelephone();
			}
		}

		return $list;
	}

	public function getAppointmentoptionsModel()
	{
		return $this->appointmentoptionsModel;
	}
}