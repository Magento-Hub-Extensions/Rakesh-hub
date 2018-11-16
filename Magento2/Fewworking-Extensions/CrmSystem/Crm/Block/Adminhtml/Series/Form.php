<?php
namespace Ueg\Crm\Block\Adminhtml\Series;

class Form extends \Magento\Backend\Block\Template
{

		protected $_coinOptions;
		protected $_coin;
		protected $_seriesCollection;
		protected $_resource;

		public function __construct(
	       \Magento\Backend\Block\Template\Context $context, 
	       \Ueg\Crm\Model\Coinoptions $coinOptions,
	       \Ueg\Crm\Model\CoinFactory $CoinFactory,
	       \Ueg\Crm\Model\SeriesFactory $SeriesFactory,
	       \Magento\Framework\App\ResourceConnection $resource,
	       array $data = []
	    ) 
	    {
	    	$this->_coinOptions = $coinOptions;
	    	$this->_coin = $CoinFactory;
	    	$this->_seriesCollection = $SeriesFactory;
	    	$this->_resource = $resource;

	    	parent::__construct($context, $data);
	    }


		public function getAllSeriesList()
		{
			$list = array();

			
			$readConnection = $this->_resource->getConnection('core_read');
			$tableName = $this->_resource->getTableName('ueg_individual_coin');
			$query = "SELECT series_id FROM $tableName WHERE coin_type = 2 GROUP BY series_id";
			$seriesIds = $readConnection->fetchCol($query);

			if(isset($seriesIds) && count($seriesIds) > 0) {
				$series = $this->_seriesCollection->create()->getCollection()
				              ->addFieldToFilter( 'series_id', array( 'in', $seriesIds ) );

				foreach ( $series as $_series ) {
					$list[ $_series->getId() ] = $_series->getData( 'series_name' );
				}
			}

			return $list;
		}

		public function getCoinOptions()
		{
			return $this->_coinOptions;
		}

}