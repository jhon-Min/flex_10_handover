<?php
    namespace App\Mail;

    class MailType{
        public const  NewOrderRecieved = 1;
        public const AdminOrderAction= 2;
        public const OrderConfirmation= 3;
        public const OrderCancelation= 4;
        public const UserNotification= 5;
        public const AccountApproveRequest=6;
        public const UserAccountCreated=7;
        public const Contact=7;
    }