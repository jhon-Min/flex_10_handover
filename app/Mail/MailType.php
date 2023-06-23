<?php
    namespace App\Mail;

    class MailType{
        public const  NewOrderRecieved = 1;
        public const AdminOrderAction= 2;
        public const OrderConfirmation= 3;
        public const OrderCancelation= 4;
        public const UserNotification= 5;
    }