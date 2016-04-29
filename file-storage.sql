CREATE TABLE IF NOT EXISTS `stored_files` (
  `id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL DEFAULT '1',
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `hash` varchar(42) CHARACTER SET utf8 NOT NULL,
  `relative_path` varchar(255) CHARACTER SET utf8 NOT NULL,
  `extension` varchar(16) CHARACTER SET utf8 NOT NULL,
  `mime_type` varchar(64) CHARACTER SET utf8 NOT NULL,
  `protected` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `stored_file_categories` (
  `id` int(10) unsigned NOT NULL,
  `slug` varchar(32) CHARACTER SET utf8 NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `created_at` int(11) NOT NULL,
  `status` enum('active','deleted') CHARACTER SET utf8 NOT NULL DEFAULT 'active'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `stored_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hash` (`hash`);

ALTER TABLE `stored_file_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `stored_files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `stored_file_categories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
