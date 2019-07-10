<?php

namespace cabinet\services\manage\shop;

use cabinet\entities\shop\order\CustomerData;
use cabinet\entities\shop\order\DeliveryData;
use cabinet\forms\manage\shop\order\OrderEditForm;
use cabinet\forms\manage\shop\order\OrderSentForm;
use cabinet\repositories\shop\OrderRepository;
use yii\db\ActiveQuery;
use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\Status;
use common\mail\services\Email;

class OrderManageService
{
    private $repository;
    private $email;

    public function __construct(OrderRepository $repository, Email $email)
    {
        $this->repository = $repository;
        $this->email = $email;
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->repository->get($id);

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
                $form->delivery->address,
                $form->delivery->city
            )
        );

        $this->repository->save($order);
    }

    public function remove($id): void
    {
        $order = $this->repository->get($id);
        $this->repository->remove($order);
    }

    /**
     * Генерация excel файла для отправки заказа
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

            $worksheet->setCellValueByColumnAndRow(0, $row + 2, "$order->delivery_index, $order->delivery_city, $order->delivery_address");
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

    /**
     * @param $id
     * @param OrderSentForm $form
     * @return void
     */
    public function setSent($id, OrderSentForm $form): void
    {
        $order = $this->repository->get($id);

        if(!$order->isSent()){
            $order->setSent($form);
            $order->save();
            $this->email->sendEmailNotifySentOrder($order->user, $order);
        }else{
            new \DomainException('Такой заказ уже был отправлен');
        }
    }

    /**
     * Уведомление о напоминании об оплате
     * @param Order[] $orders
     * @return string
     */
    public function notifyPay(array $orders): string
    {
        $messages = [];
        $res = 'Unpaid orders not found';

        try{
            foreach($orders as $order):
                if($order->created_at  < time()){
                    $messages[] = $this->email->emailNotifyPay($order->user, $order);
                    $order->notify_send = 1;
                    $order->update(false);
                }
            endforeach;

            if($messages){
                $this->email->mailer->sendMultiple($messages);
                $res = 'Send email for notify pay';
            }
        }catch(\DomainException $e){
            \Yii::$app->errorHandler->logException($e);
            $res = $e->getMessage();
        }

        return $res;
    }
}