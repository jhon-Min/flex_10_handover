<?php
    namespace App\Mail;

    class MailType{
        public const  NEWORDERRECIEVED = 1;
        public const ADMINORDERACTION= 2;
        public const ORDERCONFIRMATION= 3;
        public const ORDERCANCELATION= 4;
        public const USERNOTIFICATION= 5;
        public const ACCOUNTAPPROVEREQUEST=6;
        public const USERACCOUNTCREATED=7;
        public const CONTACT=8;
    }