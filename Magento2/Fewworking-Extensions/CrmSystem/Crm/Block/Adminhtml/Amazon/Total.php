<?php
namespace Ueg\Crm\Block\Adminhtml\Amazon;


class Total extends \Magento\Backend\Block\Template
{

	protected $resource;
	    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ){
        $this->date = $date;
        $this->resource = $resource;
        $this->_backendSession = $context->getBackendSession();
        parent::__construct($context, $data);
     }

     public function getConnection()
     {
     	return $this->resource->getConnection();
     }

     public function getamazonlogTable()
     {
     	return $this->resource->getTableName( 'amazonlog' );
     }
}