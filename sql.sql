drop database if exists `sss`;
create database `sss`;

grant select, insert, delete, update, alter, create, index, drop on `sss`.*
to admin identified by 'admin';

use sss;