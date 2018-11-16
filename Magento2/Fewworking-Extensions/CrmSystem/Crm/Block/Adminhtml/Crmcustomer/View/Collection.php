<?php
namespace Ueg\Crm\Block\Adminhtml\Crmcustomer\View;

class Collection extends \Magento\Backend\Block\Template
{

    protected $seriesFactory;
    protected $_registry;
    protected $_resource;
    protected $coinoptions;
    protected $_session;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Ueg\Crm\Model\SeriesFactory $SeriesFactory, \Magento\Framework\Registry $registry,\Magento\Framework\App\ResourceConnection $resource,\Ueg\Crm\Model\Coinoptions $coinoptions,\Magento\Backend\Model\Auth\Session $authSession

    ) {
        $this->seriesFactory = $SeriesFactory;
        $this->_registry = $registry;
        $this->_resource = $resource;
        $this->coinoptions = $coinoptions;
        $this->_session = $authSession;

        parent::__construct($context);
    }

	protected function _prepareLayout()
	{
		$gridBlock = $this->getLayout()->createBlock(
			'Ueg\Crm\Block\Adminhtml\Crmcustomer\View\Collection\Grid', 'collection.grid'
		);
		$this->setChild('grid', $gridBlock);
		return parent::_prepareLayout();
	}

	public function getSeriesInfo($series)
	{
		$series = $this->seriesFactory->create()->load($series);
		if($series->getId()) {
			$customer = $this->_registry->registry('current_customer');
			$customerId = $customer->getId();
			$resource = $this->_resource;
			$readConnection = $resource->getConnection();
			$tableName = $resource->getTableName('ueg_coin_series_info');
			$query = "SELECT * FROM $tableName WHERE customer_id = $customerId AND series_id = ". $series->getId();
			$seriesInfo = $readConnection->fetchRow($query);
			if(isset($seriesInfo['series_info_id'])) {
				$series['series_info_id'] = $seriesInfo['series_info_id'];
				$series['service_prefered'] = $seriesInfo['service_prefered'];
				$series['website'] = $seriesInfo['website'];
				$series['series_note'] = $seriesInfo['series_note'];
				$series['registry_name'] = $seriesInfo['registry_name'];
			}
		}

		return $series;
	}

	public function getAllSeriesList()
	{
		$list = array();

		$customer = $this->_registry->registry('current_customer');
		$customerId = $customer->getId();
		
		$resource = $this->_resource;
		$readConnection = $resource->getConnection();
		$tableName = $resource->getTableName('ueg_individual_coin');
		$query = "SELECT series_id FROM $tableName WHERE coin_type = 2 AND customer_id != $customerId GROUP BY series_id";
		$seriesIds = $readConnection->fetchCol($query);

		if(isset($seriesIds) && count($seriesIds) > 0) {
			$series = $this->seriesFactory->create()->getCollection()
			              ->addFieldToFilter( 'series_id', array( 'in', $seriesIds ) );

			foreach ( $series as $_series ) {
				$list[ $_series->getId() ] = $_series->getData( 'series_name' );
			}
		}

		return $list;
	}

	public function getSeriesList()
	{
		$list = array();

		$customer = $this->_registry->registry('current_customer');
		$customerId = $customer->getId();
		//echo $customerId;exit();
		$resource = $this->_resource;
		$readConnection = $resource->getConnection();
		$tableName = $resource->getTableName('ueg_individual_coin');
		$query = "SELECT series_id FROM $tableName WHERE coin_type = 2 AND customer_id = $customerId GROUP BY series_id";
		//echo $query;exit();
		$seriesIds = $readConnection->fetchCol($query);

		if(isset($seriesIds) && count($seriesIds) > 0) {
			$series = $this->seriesFactory->create()->getCollection()
			              ->addFieldToFilter( 'series_id', array( 'in', $seriesIds ) );

			foreach ( $series as $_series ) {
				$list[ $_series->getId() ] = $_series->getData( 'series_name' );
			}
		}

		return $list;
	}

	function getCoinoptionsModel() {
        return $this->coinoptions;
    }

    function getAdminSessionModel() {
        return $this->_session;
    }

    function getCoreResource() {
        return $this->_resource;
    }
}