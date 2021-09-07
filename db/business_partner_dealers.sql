/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.31-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `business_partner_dealers` (
	`id` int (11),
	`name` varchar (765),
	`address` varchar (765),
	`email` varchar (765),
	`phone_number` varchar (765),
	`pan_card_number` varchar (765),
	`tan_card_number` varchar (765),
	`pan_document_uri` varchar (765),
	`tan_document_uri` varchar (765),
	`contact_person_name` varchar (765),
	`contact_person_mobile_number` varchar (765),
	`additional_business_phone_number` varchar (765),
	`additional_business_email` varchar (765),
	`payment_method` varchar (33),
	`status` char (24),
	`created_by` varchar (765),
	`created_date` datetime ,
	`updated_by` varchar (765),
	`updated_date` datetime 
); 
insert into `business_partner_dealers` (`id`, `name`, `address`, `email`, `phone_number`, `pan_card_number`, `tan_card_number`, `pan_document_uri`, `tan_document_uri`, `contact_person_name`, `contact_person_mobile_number`, `additional_business_phone_number`, `additional_business_email`, `payment_method`, `status`, `created_by`, `created_date`, `updated_by`, `updated_date`) values('1','Hyundai','Everest Chambers, Andheri Kurla Road, Andheri East, Mumbai - 400059, Next to Star Plus Building, Below Metro Marol Station','amit.yadav@indicosmic.com','8108818995','AOPPY8685N','123456','35caf07354855f6fbc6df87310bd81c7.PNG','9c061b7688941a226ae10f9b4f9633af.PNG','Amit Yadav','8108818995','8108818995','amit.yadav@indicosmic.com',NULL,'active','ADMINISTRATOR-Muhil suganthan','2018-06-06 13:03:17',NULL,NULL);
insert into `business_partner_dealers` (`id`, `name`, `address`, `email`, `phone_number`, `pan_card_number`, `tan_card_number`, `pan_document_uri`, `tan_document_uri`, `contact_person_name`, `contact_person_mobile_number`, `additional_business_phone_number`, `additional_business_email`, `payment_method`, `status`, `created_by`, `created_date`, `updated_by`, `updated_date`) values('2','UM Bike','Everest Chambers, Andheri Kurla Road, Andheri East, Mumbai - 400059, Next to Star Plus Building, Below Metro Marol Station','amit.yadav@mysolutionnow.com','8108818995','AOPPY8685N','123456','2bec68a0ab5e23e4281cf0c503e5f894.PNG','91321133e780776a872e57c28db39c33.PNG','Amit Yadav','7867868665','8108818995','amit.yadav@mysolutionnow.com',NULL,'active','ADMINISTRATOR-Muhil suganthan','2018-02-17 13:04:59',NULL,NULL);
