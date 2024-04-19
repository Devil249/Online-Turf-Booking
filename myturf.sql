create database myTurf; 
use myTurf;

create table user(userid int AUTO_INCREMENT,name varchar(50), email varchar(50), pass varchar(70), verificationCode varchar(70), createTime datetime, verificationTime datetime, verified boolean, primary key(userid));



create table login_management(id int auto_increment, session_id varchar(100), isLoggedin boolean, primary key(id), loggedinat datetime, loggedoutat datetime);
create table booking(id int auto_increment, slot_from int, slot_to int, slot_date date, booking_status int, payment_status int, transaction_id varchar(100), booked_by int, canby int, transaction_amount int, bookingdatetime datetime, primary key(id)); 
ALTER TABLE login_management ADD COLUMN userid int, ADD CONSTRAINT fk_userid FOREIGN KEY (userid) REFERENCES user(userid);
Alter table user add column role int;
ALTER TABLE booking ADD COLUMN turn_name varchar(125);