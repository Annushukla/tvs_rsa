/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.31-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `dealer_pos_link` (
	`id` int (11),
	`dealer_id` int (11),
	`pos_id` int (11),
	`created_date` datetime 
); 
insert into `dealer_pos_link` (`id`, `dealer_id`, `pos_id`, `created_date`) values('1','1','41','2018-06-06 15:24:48');
insert into `dealer_pos_link` (`id`, `dealer_id`, `pos_id`, `created_date`) values('2','2','2','2018-06-08 17:06:22');
