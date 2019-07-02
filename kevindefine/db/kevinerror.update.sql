
#2019-6-14
#default items
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('0', '0', 'ERRCODE_0_OK', '正常', 'Success', '200', '正常返回值', 'haihuay', '2019-06-11 12:49:00');
UPDATE kv_errcode set id = 0 where name = '正常';
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('1', '0', 'ERRCODE_1_UNKNOWN', '未知错误', 'unknown error', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('2', '0', 'ERRCODE_2_RUNTIMEERROR', '运行时错误', 'Runtime Error', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('10', '0', 'ERRCODE_10_NOITEMWITHID', '没有查询到输入id的条目', 'Can not find the item with input id.', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('11', '0', 'ERRCODE_11_NOTENOUGHPARAM', '参数输入不足', 'Parameters are not enough.', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('12', '0', 'ERRCODE_12_UPDATEFAILED', '更新出错', 'Update failed', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('13', '0', 'ERRCODE_13_CREATEFAILED', '创建出错', 'Create failed', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('14', '0', 'ERRCODE_14_SAVEFAILED', '保存出错', 'Save failed', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('15', '0', 'ERRCODE_15_ERRORSEARCHITEM', '查询出错', 'Error when select ittems.', '200', '', 'haihuay', '2019-06-11 18:27:31');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('20', '0', 'ERRCODE_20_NOERRCODE', 'errcode类：没有errcode参数', 'errcode object: no errcode parameter', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('21', '0', 'ERRCODE_21_NOERRMSG', 'errcode类：没有errmsg参数', 'errcode object: no errmsg parameter', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('22', '0', 'ERRCODE_22_NODATA', 'errcode类：没有data参数', 'errcode object: no data parameter', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('50', '0', 'ERRCODE_50_LOGINFAILED', '登陆失败', 'Login failed', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('51', '0', 'ERRCODE_51_USERPASSWORDERROR', '用户或密码错误', 'User or password Error', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('52', '0', 'ERRCODE_52_ACCOUNTSHORT', '帐号过短', 'Account is too short.', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('53', '0', 'ERRCODE_53_PASSWORDSHORT', '密码过短', 'Password is too short.', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('54', '0', 'ERRCODE_54_USERNOTLOGIN', '用户没有登陆', 'User is not login', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('55', '0', 'ERRCODE_55_CANNOTUSELOGIN', '用户不能使用login函数，因为不是访客', 'user can not use the login function.He is not the', '200', '', 'haihuay', '2019-06-11 12:49:00');
INSERT INTO `kv_errcode` (`id`, `project`, `code`, `name`, `nameEn`, `status`, `description`, `createdBy`, `createdDate`) VALUES ('56', '0', 'ERRCODE_56_ACCOUNTLOCKED', '帐号被锁定', 'Account is locked!', '200', '', 'haihuay', '2019-06-11 12:49:00');

#kv_errcode 2019-6-23
ALTER TABLE `kv_errcode` ADD COLUMN `code`  char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'code name' AFTER `project`;
update kv_errcode set code = CONCAT('ERRCODE_',id);
ALTER TABLE `kv_errcode` ADD UNIQUE INDEX (`code`);

