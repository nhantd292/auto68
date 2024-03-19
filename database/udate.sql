-- Cập nhật database 17/04/2020
-- ALTER TABLE `x_project`
-- 	ADD COLUMN `total_value_partner` INT(11) NULL DEFAULT '0' COMMENT 'Tổng tiền thuê đối tác triển khai' AFTER `rest_value`,
-- 	ADD COLUMN `list_bill` TEXT NULL COMMENT 'Danh sách hóa đơn đã xuất' AFTER `partners`,
-- 	CHANGE COLUMN `repaid_value` `repaid_value` INT(11) NULL DEFAULT '0' COMMENT 'Tiền đã trả cho đối tác' AFTER `total_value_partner`;


-- 	Cập nhật upload file
-- 	ALTER TABLE `x_project`
-- 	ADD COLUMN `file_attach` CHAR(255) NULL DEFAULT NULL COMMENT 'File hợp đồng' AFTER `note`,
-- 	CHANGE COLUMN `name` `name` TEXT NULL DEFAULT NULL COMMENT 'Tên dự án' AFTER `code`;

-- Cập nhật database 21/04/2020
-- ALTER TABLE `x_contact_link`
-- 	ADD COLUMN `phone2` CHAR(50) NULL DEFAULT NULL AFTER `phone`,
-- 	ADD COLUMN `phone3` CHAR(50) NULL DEFAULT NULL AFTER `phone2`;
-- ALTER TABLE `x_contact`
-- 	ADD COLUMN `company_code` CHAR(50) NULL DEFAULT NULL COMMENT 'Mã số thuế' AFTER `type`;

-- Cập nhật database 23/04/2020
-- ALTER TABLE `x_pay`
-- 	CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT '0' AFTER `pay_type`;
-- update x_pay
-- set status = 0 where status is null

-- Cập nhật database 24/04/2020
-- ALTER TABLE `x_pay`
-- 	ADD COLUMN `accept` TINYINT(1) NULL DEFAULT '0' COMMENT 'Xác nhận/Duyệt thanh toán' AFTER `status`;
-- ALTER TABLE `x_pay`
--     ALTER `date` DROP DEFAULT;
-- ALTER TABLE `x_pay`
-- 	CHANGE COLUMN `date` `date` DATE NULL COMMENT 'Ngày thanh toán dự kiến' AFTER `index`,
-- 	ADD COLUMN `date_pay` DATE NULL COMMENT 'Ngày thanh toán thực tế' AFTER `date`;

-- Cập nhật database 28/04/2020
-- ALTER TABLE `x_contact`
-- 	ADD COLUMN `index` BIGINT(20) NULL AUTO_INCREMENT AFTER `id`,
-- 	ADD INDEX `index` (`index`),
-- 	ADD COLUMN `code` CHAR(50) NULL DEFAULT NULL COMMENT 'Mã khách hàng' AFTER `index`,
-- 	ADD INDEX `code` (`code`);

-- Cập nhật ngày 01/08/2020
-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `status_store` TINYINT(1) NULL DEFAULT '0' COMMENT 'Có phải đơn tạo từ cửa hàng không 1/0' AFTER `guarantee_note`;

-- Bổ sung cấu hình đánh giá thợ may trong bảng sản phẩm:05/08/2020
-- ALTER TABLE `x_product`
-- 	ADD COLUMN `tailors_status` TINYINT(1) NULL DEFAULT '0' AFTER `status`;
--
-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `evaluate` TINYINT(1) NULL DEFAULT '0' COMMENT 'Đơn đã được đánh giá chưa 1/0' AFTER `status_store`;
--
-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `status_technical` TINYINT(1) NULL DEFAULT '0' COMMENT 'Đơn đã nhập thợ kỹ thuật chưa' AFTER `evaluate`,
-- 	ADD COLUMN `status_tailors` TINYINT(1) NULL DEFAULT '0' COMMENT 'Đơn đã nhập thợ may chưa' AFTER `status_technical`;

-- Sửa lại mẫu in
-- ALTER TABLE `x_contact`
-- 	ADD COLUMN `license_plate` CHAR(50) NOT NULL COMMENT 'Biển số xe' AFTER `phone`;

-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `hidden` TINYINT(1) NULL DEFAULT '0' COMMENT 'Đơn hàng có bị ẩn không 1-ẩn,  0-không' AFTER `status_tailors`;

-- ALTER TABLE `x_user`
-- 	ADD COLUMN `company_position_care_id` TINYTEXT NULL DEFAULT NULL COMMENT 'Chức vụ kiêm nhiệm' AFTER `company_position_id`;

-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `production_date_send` DATE NULL COMMENT 'Ngày chuyển trạng thái giao hàng' AFTER `production_type_id`;

-- 13/10/2020
-- -- Thêm ngày ra đơn đầu tiên và mã đơn đầu tiên cho khách hàng.
-- ALTER TABLE `x_contact`
-- 	ADD COLUMN `contract_first_date` DATETIME NULL DEFAULT NULL COMMENT 'Ngày ra đơn hàng đầu tiên' AFTER `mkt_code`,
-- 	ADD COLUMN `contract_first_code` CHAR(50) NULL DEFAULT NULL COMMENT 'Mã đơn hàng đầu tiên' AFTER `contract_first_date`;
-- -- Thêm doanh số cho mkt
-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `mkt_sales_new` INT(11) NULL DEFAULT '0' COMMENT 'Doanh số mới mkt' AFTER `sales_cross`,
-- 	ADD COLUMN `mkt_sales_care` INT(11) NULL DEFAULT '0' COMMENT 'Doanh số chăm sóc mkt' AFTER `mkt_sales_new`;

-- Ghi nhận doanh số mới hay doanh số mua thêm cho nhân viên sale
--     ALTER TABLE `x_contract`
-- 	ADD COLUMN `date_success` DATETIME NULL DEFAULT NULL COMMENT 'Ngày ghi nhận trạng thái thành công' AFTER `delete`;
--
--     ALTER TABLE `x_contract`
-- 	ADD COLUMN `time_sales` INT(11) NULL DEFAULT NULL COMMENT 'Khoảng thời gian tính doanh số cấu hình hiện tại' AFTER `date_success`;
--
-- 	ALTER TABLE `x_contact`
-- 	ADD COLUMN `contract_time_success` DATETIME NULL DEFAULT NULL COMMENT 'Ngày thành công của đơn hàng' AFTER `contract_first_date`;

-- 12/12/2020
-- ALTER TABLE `x_product_listed`
-- 	ADD COLUMN `percenter` INT(11) NULL DEFAULT '0' COMMENT '% khuyến mãi' AFTER `price`;

-- 14/12/2020
-- CREATE TABLE `x_notifi` (
-- 	`id` VARCHAR(25) NOT NULL,
-- 	`content` VARCHAR(255) NOT NULL,
-- 	`link` VARCHAR(255) NOT NULL,
-- 	`type` CHAR(50) NULL DEFAULT NULL,
-- 	`options` TEXT NULL,
-- 	`created` DATETIME NOT NULL,
-- 	PRIMARY KEY (`id`)
-- )
-- COMMENT='Bảng lưu thông báo'
-- COLLATE='utf8_general_ci'
-- ENGINE=InnoDB
-- ;
--
-- CREATE TABLE `x_notifi_user` (
-- 	`id` VARCHAR(25) NOT NULL,
-- 	`user_id` VARCHAR(25) NOT NULL,
-- 	`notifi_id` VARCHAR(25) NOT NULL,
-- 	`status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Trạng thái thông báo 0 chưa đọc, 1 đã đọc',
-- 	`created` DATETIME NOT NULL,
-- 	PRIMARY KEY (`id`)
-- )
-- COMMENT='Bảng lưu trạng thái thông báo thông báo'
-- COLLATE='utf8_general_ci'
-- ENGINE=InnoDB
-- ;
--
-- ALTER TABLE `x_user`
-- 	ADD COLUMN `notifi` CHAR(22) NULL DEFAULT NULL COMMENT 'nhận thông báo đơn hàng bán sai theo chi nhánh: \'branch\', tất cả chi nhánh \'all\'.' AFTER `login_time`;

-- Tạo bảng lưu combo sản phẩm 18/12/2020
-- CREATE TABLE `x_combo_product` (
-- 	`id` VARCHAR(25) NOT NULL,
-- 	`name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Tên combo sản phẩm',
-- 	`note` TINYTEXT NULL COMMENT 'Ghi chú',
-- 	`status` TINYINT(1) NULL DEFAULT '1' COMMENT 'Trạng thái sử dụng',
-- 	`price_total` INT(11) NULL DEFAULT '1' COMMENT 'Tổng đơn giá combo',
-- 	`options` TEXT NULL COMMENT 'Danh sách sản phẩm trong combo',
-- 	`created` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
-- 	`created_by` CHAR(25) NULL DEFAULT NULL COMMENT 'Người tạo combo',
-- 	PRIMARY KEY (`id`)
-- )
-- COMMENT='Bảng quản lý combo sản phẩm'
-- COLLATE='utf8_general_ci'
-- ENGINE=InnoDB
-- ;
-- Khóa đơn hàng 17/03/21
-- ALTER TABLE `x_contract`
-- 	ADD COLUMN `lock` TINYINT(1) NULL DEFAULT '0' COMMENT 'Đơn hàng đã khóa hay chưa 1-khóa, 0-chưa khóa' AFTER `delete`;

-- Mã hóa số điện thoại
-- ALTER TABLE `x_user`
-- 	ADD COLUMN `encode_phone` TINYTEXT NULL DEFAULT NULL COMMENT 'Mã hóa sđt' AFTER `company_position_care_id`;
--
-- ALTER TABLE `x_user`
-- 	ADD COLUMN `flag` TINYINT(1) NULL DEFAULT '0' AFTER `status`;

-- 21/05/2021
-- Tạo bảng đồng bộ kiotviet
CREATE TABLE `x_kov_branches` (
                                  `id` CHAR(25) NOT NULL COLLATE 'utf8_general_ci',
                                  `branchName` VARCHAR(255) NULL DEFAULT NULL COMMENT 'id nhóm cha' COLLATE 'utf8_general_ci',
                                  `address` TINYTEXT NULL DEFAULT NULL COMMENT 'Tên nhóm' COLLATE 'utf8_general_ci',
                                  `locationName` TINYTEXT NULL DEFAULT NULL COMMENT 'id shop' COLLATE 'utf8_general_ci',
                                  `contactNumber` CHAR(25) NULL DEFAULT NULL COMMENT 'Giới hạn phải' COLLATE 'utf8_general_ci',
                                  `retailerId` CHAR(50) NULL DEFAULT NULL COMMENT 'Level' COLLATE 'utf8_general_ci',
                                  `wardName` TINYTEXT NULL DEFAULT NULL COMMENT 'Giới hạn trái' COLLATE 'utf8_general_ci',
                                  `status` TINYINT(1) NULL DEFAULT '1',
                                  `created` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                                  `created_by` CHAR(25) NULL DEFAULT NULL COMMENT 'Người tạo combo' COLLATE 'utf8_general_ci',
                                  PRIMARY KEY (`id`) USING BTREE
)
    COMMENT='Quản lý kho hàng sản phẩm kioviet'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `x_kov_products` (
                                  `id` CHAR(25) NOT NULL COMMENT 'id sản phẩm bên kiotviet' COLLATE 'utf8_general_ci',
                                  `code` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Mã sản phẩm' COLLATE 'utf8_general_ci',
                                  `name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Tên sản phẩm' COLLATE 'utf8_general_ci',
                                  `fullName` TINYTEXT NULL DEFAULT NULL COMMENT 'Tên đầy đủ (Tên + Thuộc tính)' COLLATE 'utf8_general_ci',
                                  `categoryId` CHAR(25) NULL DEFAULT NULL COMMENT 'id nhóm hàng' COLLATE 'utf8_general_ci',
                                  `basePrice` BIGINT(20) NULL DEFAULT NULL COMMENT 'Giá bán',
                                  `images` TINYTEXT NULL DEFAULT NULL COMMENT 'Danh sách ảnh sản phẩm' COLLATE 'utf8_general_ci',
                                  `status` TINYINT(1) NULL DEFAULT '1' COMMENT 'Trạng thái',
                                  `product_type` TINYINT(1) NULL DEFAULT '1' COMMENT '1 sản phẩm bán sẵn, 2 sản phẩm sản xuất',
                                  `evaluate` TINYINT(1) NULL DEFAULT '1' COMMENT 'Có đánh giá thợ may không 1 - có, 2 - không',
                                  `created` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
                                  `created_by` CHAR(25) NULL DEFAULT NULL COMMENT 'Người tạo combo' COLLATE 'utf8_general_ci',
                                  PRIMARY KEY (`id`) USING BTREE
)
    COMMENT='Quản lý sản phẩm kioviet'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `x_kov_product_branch` (
                                        `branchId` CHAR(25) NOT NULL COMMENT 'id kho' COLLATE 'utf8_general_ci',
                                        `productId` VARCHAR(255) NOT NULL COMMENT 'id sản phẩm' COLLATE 'utf8_general_ci',
                                        `branchName` TINYTEXT NULL DEFAULT NULL COMMENT 'Tên chi nhánh' COLLATE 'utf8_general_ci',
                                        `cost` INT(11) NULL DEFAULT NULL COMMENT 'Giá vốn của kiotviet',
                                        `cost_new` INT(11) NULL DEFAULT NULL COMMENT 'Giá vốn của crm',
                                        `fee` INT(11) NULL DEFAULT NULL COMMENT 'Phụ phí',
                                        `onHand` INT(11) NULL DEFAULT NULL COMMENT 'Số lượng tồn kho',
                                        `reserved` INT(11) NULL DEFAULT NULL COMMENT 'Số lượng đặt hàng',
                                        `status` TINYINT(1) NULL DEFAULT '1',
                                        PRIMARY KEY (`branchId`, `productId`) USING BTREE
)
    COMMENT='Quản lý sản phẩm kioviet'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `x_user`
	ADD COLUMN `kov_branch_id` TEXT NULL COMMENT 'Kho hàng kiotviet' AFTER `notifi`;

ALTER TABLE `x_contact`
    ADD COLUMN `contact_group` CHAR(22) NULL DEFAULT NULL COMMENT 'nhóm khách hàng' AFTER `sale_group_id`;

CREATE TABLE `x_kov_discounts` (
	`id` VARCHAR(25) NOT NULL,
	`code` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Mã chương trình khuyến mãi',
	`name` TINYTEXT NULL COMMENT 'Tên chương trình',
	`note` TINYTEXT NULL COMMENT 'Ghi chú',
	`status` TINYINT(1) NULL DEFAULT '1' COMMENT 'Trạng thái sử dụng',
	`date_begin` DATETIME NULL DEFAULT NULL COMMENT 'Ngày bắt đầu',
	`date_end` DATETIME NULL DEFAULT NULL COMMENT 'Ngày kết thúc',
	`discounts_range_branchs` CHAR(10) NULL DEFAULT NULL COMMENT 'Phạm vi khuyến mãi theo chi nhánh all/some : tất cả/ chọn chi nhánh',
	`discounts_range_branchs_detail` TEXT NULL COMMENT 'Các chi nhánh được áp dụng',
	`discounts_range_sales` CHAR(10) NULL DEFAULT NULL COMMENT 'Phạm vi khuyến mãi theo ngườ bán all/some : tất cả/ chọn người bán',
	`discounts_range_sales_detail` TEXT NULL COMMENT 'Các nhân viên sale được áp dụng',
	`discounts_range_customers` CHAR(10) NULL DEFAULT NULL COMMENT 'Phạm vi khuyến mãi theo nhóm khách hàng all/some : tất cả/ chọn nhóm khách hàng',
	`discounts_range_customers_detail` TEXT NULL COMMENT 'Các nhóm khách hàng được áp dụng',
	`discounts_type` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Khuyến mãi theo hoa-don, hang-hoa',
	`discounts_option` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Hình thức giam-gia-hoa-don, tang-hang, giam-gia-hang, mua-hang-giam-gia-hang, mua-hang-tang-hang,gia-ban-thoe-so-luong',
	`contract_value_total_min` INT(11) NULL DEFAULT NULL COMMENT 'Tổng giá trị hóa đơn điều kiện thấp nhất được áp dụng khuyến mãi theo hóa đơn',
	`detail` TEXT NULL COMMENT 'Chi tiết điều kiện khuyến mãi.',
	`created` DATETIME NULL DEFAULT NULL COMMENT 'Ngày tạo',
	`created_by` CHAR(25) NULL DEFAULT NULL COMMENT 'Người tạo khuyến mãi',
	PRIMARY KEY (`id`)
)
COMMENT='Bảng quản lý chương trình khuyến mãi'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `x_contract`
	ADD COLUMN `kov_status` TINYINT(1) NULL DEFAULT '0' COMMENT 'Đơn lên theo sản phẩm từ kiotviet' AFTER `lock`;

CREATE TABLE `x_product_return` (
	`id` VARCHAR(25) NOT NULL,
	`productId` VARCHAR(255) NULL DEFAULT NULL COMMENT 'id sản phẩm',
	`branchId` CHAR(25) NULL DEFAULT NULL COMMENT 'id kho',
	`name_year` VARCHAR(255) NULL DEFAULT '1' COMMENT 'Tên xe - năm sản xuất',
	`quantity` INT(3) NULL DEFAULT '1' COMMENT 'Số lượng sản phẩm',
	`status` TINYINT(1) NULL DEFAULT '1',
	PRIMARY KEY (`id`)
)
COMMENT='Quản lý kho hàng hoàn những sản phẩm của đơn hàng có trạng thái đã nhận hoàn'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `x_contract`
	ADD COLUMN `id_kov` CHAR(50) NULL DEFAULT NULL COMMENT 'id đơn hàng bên kov' AFTER `code`;

ALTER TABLE `x_contract`
    ADD COLUMN `fee_other` INT(11) NULL DEFAULT NULL COMMENT 'thu khác' AFTER `vat`;

ALTER TABLE `x_contract`
	ADD COLUMN `total_contract_discount` INT(11) NULL DEFAULT NULL COMMENT 'Giảm giá tổng đơn hàng' AFTER `fee_other`;

ALTER TABLE `x_product_return`
    ADD COLUMN `sale_branch_id` CHAR(25) NULL DEFAULT NULL COMMENT 'id chi nhánh trên crm' AFTER `branchId`,
	ADD COLUMN `contract_code` CHAR(25) NULL DEFAULT NULL COMMENT 'Mã đơn hàng' AFTER `sale_branch_id`,
    ADD COLUMN `contract_id` CHAR(25) NULL DEFAULT NULL COMMENT 'id đơn hàng' AFTER `contract_code`;

ALTER TABLE `x_contract`
    ADD COLUMN `shipped` TINYINT(1) NULL DEFAULT '0' COMMENT 'Xác nhận đã xuất kho 1 đã xuất, 0 chưa xuất' AFTER `kov_status`;

