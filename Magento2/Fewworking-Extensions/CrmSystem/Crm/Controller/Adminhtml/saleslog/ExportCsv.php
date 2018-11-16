<?php
namespace Ueg\Crm\Controller\Adminhtml\saleslog;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;

class ExportCsv extends \Magento\Backend\App\Action
{
    protected $_fileFactory;

    protected $SaleslogFactory;

    protected $resource;

    protected $currency;

    protected $response;



    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Ueg\Crm\Model\SaleslogFactory $SaleslogFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Pricing\Helper\Data $currency,
        \Magento\Framework\App\Response\Http $response
    ) {
        $this->SaleslogFactory = $SaleslogFactory;
        $this->resource = $resource;
        $this->currency = $currency;
        $this->response = $response;
        parent::__construct($context);
    }

    public function execute()
    {   

       
        $this->_view->loadLayout(false);

        $fileName = 'saleslog.csv';

        $exportBlock = $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Saleslog\Griddup');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $this->_fileFactory = $objectManager->create('Magento\Framework\App\Response\Http\FileFactory');

        return $this->_fileFactory->create(
            $fileName,
            $exportBlock->getCsvFile(),
            DirectoryList::VAR_DIR
        );

        //  $content = $this->_view->getLayout()->createBlock('Ueg\Crm\Block\Adminhtml\Saleslog\Griddup')->getCsv();
        // //echo 'hii';exit();
        // //$content = '';
        // $content = $this->getTotalGridCsvData();
        // //echo $content;exit();
        // $this->_sendUploadResponse($fileName, $content);
    }


    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->response;
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }



     public function getTotalGridCsvData()
  {
    $csvData = PHP_EOL . PHP_EOL;

    $resource        = $this->resource;
    $readConnection  = $resource->getConnection();
    $salesLog     = $resource->getTableName('saleslog');

    $header = array('', 'Store Numismatic', 'eBay Bullion', 'eBay Numismatic', 'Semi 2%', 'Comm/Fee', '2% Bullion', 'Monthly Total', 'Rare Coins/Comm Totals', '% Numis');
    foreach ($header as $row) {
      $csvData .= '"' . $row . '",';
    }
    $csvData .= PHP_EOL;

    $monthData = array();
    $maxMonth = date('m') + 1;
    for($mon = 1; $mon <= $maxMonth; $mon++) {
      $minDate = date('Y-m-d', mktime(0, 0, 0, $mon, 1));
      $maxDate = date('Y-m-d', mktime(0, 0, 0, $mon, date('t', mktime(0, 0, 0, $mon, 10))));
      $sql = "SELECT 
                    SUM(store_numis) AS store_numis, 
                    SUM(ebay_bullion) AS ebay_bullion, 
                    SUM(ebay_numis) AS ebay_numis, 
                    SUM(store_bullion) AS store_bullion 
                  FROM $salesLog 
                  WHERE order_date >= '$minDate' AND order_date <= '$maxDate'";
      $result = $readConnection->fetchRow($sql);
      //echo "<pre>"; print_r($result); echo "</pre>";
      $monthlyTotal = (float)($result['store_numis'] + $result['ebay_bullion'] + $result['ebay_numis'] + $result['store_bullion']);
      $commTotal = (float)($result['store_numis'] + $result['ebay_numis']);
      $percentageNumis = 0;
      if($monthlyTotal !=0)
      {
        $percentageNumis = (float)(($commTotal/$monthlyTotal)*100);
      }
      

      $monthData[] = array(
        date('F Y', mktime(0, 0, 0, $mon, 10)),
        $this->currency->currency($result['store_numis'], true, false),
        $this->currency->currency($result['ebay_bullion'], true, false),
        $this->currency->currency($result['ebay_numis'], true, false),
        $this->currency->currency(0, true, false),
        $this->currency->currency(0, true, false),
        $this->currency->currency($result['store_bullion'], true, false),
        $this->currency->currency($monthlyTotal, true, false),
        $this->currency->currency($commTotal, true, false),
        number_format($percentageNumis, 2)
      );
    }
    foreach ($monthData as $_monthData) {
      foreach ($_monthData as $row) {
        $csvData .= '"' . $row . '",';
      }
      $csvData .= PHP_EOL;
    }

    $csvData .= PHP_EOL;

    $yearData = array();
    for($year = 2008; $year <= date("Y"); $year++) {
      $minDate = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
      $maxDate = date('Y-m-d', mktime(0, 0, 0, 12, date('t', mktime(0, 0, 0, 12, 10)), $year));
      $sql = "SELECT 
                    SUM(store_numis) AS store_numis, 
                    SUM(ebay_bullion) AS ebay_bullion, 
                    SUM(ebay_numis) AS ebay_numis, 
                    SUM(store_bullion) AS store_bullion 
                  FROM $salesLog 
                  WHERE order_date >= '$minDate' AND order_date <= '$maxDate'";
      $result = $readConnection->fetchRow($sql);
      //echo "<pre>"; print_r($result); echo "</pre>";
      $monthlyTotal = (float)($result['store_numis'] + $result['ebay_bullion'] + $result['ebay_numis'] + $result['store_bullion']);
      $commTotal = (float)($result['store_numis'] + $result['ebay_numis']);
      $percentageNumis = 0;
      if($monthlyTotal != 0)
      {
        $percentageNumis = (float)(($commTotal/$monthlyTotal)*100);
      }
      

      $yearData[] = array(
        $year,
        $this->currency->currency($result['store_numis'], true, false),
        $this->currency->currency($result['ebay_bullion'], true, false),
        $this->currency->currency($result['ebay_numis'], true, false),
        $this->currency->currency(0, true, false),
        $this->currency->currency(0, true, false),
        $this->currency->currency($result['store_bullion'], true, false),
        $this->currency->currency($monthlyTotal, true, false),
        $this->currency->currency($commTotal, true, false),
        number_format($percentageNumis, 2)
      );
    }
    foreach ($yearData as $_yearData) {
      foreach ($_yearData as $row) {
        $csvData .= '"' . $row . '",';
      }
      $csvData .= PHP_EOL;
    }

    return $csvData;
  }




}