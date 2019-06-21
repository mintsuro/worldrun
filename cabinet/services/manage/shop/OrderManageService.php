<?php

namespace cabinet\services\manage\shop;

use cabinet\entities\shop\order\CustomerData;
use cabinet\entities\shop\order\DeliveryData;
use cabinet\forms\manage\shop\order\OrderEditForm;
use cabinet\repositories\shop\OrderRepository;
use yii\db\ActiveQuery;
use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\Status;

class OrderManageService
{
    private $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);

        $order->edit(
            new CustomerData(
                $form->customer->name,
                $form->customer->phone
            ),
            $form->weight,
            $form->track_post,
            $form->status
        );

        $order->setDeliveryInfo(
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }

    /**
     * @param $query ActiveQuery
     * @return string
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function export($query): string
    {
        $objPHPExcel = new \PHPExcel();

        $worksheet = $objPHPExcel->getActiveSheet();

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('H')->setAutoSize(true);
        $worksheet->getColumnDimension('J')->setAutoSize(true);
        $worksheet->getStyle('A1:Y1')->getFont()->setBold(true);

        $worksheet->setCellValue('A1', 'ADDRESSLINE');
        $worksheet->setCellValue('B1', 'ADRESAT');
        $worksheet->setCellValue('C1', 'MASS');
        $worksheet->setCellValue('D1', 'VALUE');
        $worksheet->setCellValue('E1', 'PAYMENT');
        $worksheet->setCellValue('F1', 'COMMENT');
        $worksheet->setCellValue('G1', 'TELADDRESS');
        $worksheet->setCellValue('H1', 'MAILTYPE');
        $worksheet->setCellValue('I1', 'MAILCATEGORY');
        $worksheet->setCellValue('J1', 'INDEXFROM');
        $worksheet->setCellValue('K1', 'VLENGTH');
        $worksheet->setCellValue('L1', 'VWIDTH');
        $worksheet->setCellValue('M1', 'VHEIGHT');
        $worksheet->setCellValue('N1', 'FRAGILE');
        $worksheet->setCellValue('O1', 'ENVELOPETYPE');
        $worksheet->setCellValue('P1', 'NOTIFICATIONTYPE');
        $worksheet->setCellValue('Q1', 'COURIER');
        $worksheet->setCellValue('R1', 'SMSNOTICERECIPIENT');
        $worksheet->setCellValue('S1', 'WOMAILRANK');
        $worksheet->setCellValue('T1', 'PAYMENTMETHOD');
        $worksheet->setCellValue('U1', 'NOTICEPAYMENTMETHOD');
        $worksheet->setCellValue('V1', 'COMPLETENESSCHECKING');
        $worksheet->setCellValue('W1', 'NORETURN');
        $worksheet->setCellValue('X1', 'VSD');
        $worksheet->setCellValue('Y1', 'TRANSPORTMODE');

        foreach ($query->each() as $row => $order) {
            /** @var Order $order */

            $worksheet->setCellValueByColumnAndRow(0, $row + 2, $order->delivery_index . ', ' . $order->delivery_address);
            $worksheet->setCellValueByColumnAndRow(1, $row + 2, $order->customer_name);
            $worksheet->setCellValueByColumnAndRow(2, $row + 2, $order->weight);
            $worksheet->setCellValueByColumnAndRow(3, $row + 2, 0);
            $worksheet->setCellValueByColumnAndRow(7, $row + 2, 47);
            $worksheet->setCellValueByColumnAndRow(7, $row + 2, '679016');
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $file = tempnam(sys_get_temp_dir(), 'export');
        $objWriter->save($file);

        return $file;
    }

}