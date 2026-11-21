-- Site settings for production (MySQL): office address, plus the donation payment details
-- as published on tomaderjonno.org/sponsor-a-child.
--
-- Fills each setting only when it is empty or still holds a demo sample value;
-- anything already entered from the admin panel is left untouched. Safe to run more than once.
--
-- IMPORTANT: settings are cached, so run `php artisan cache:clear` on the server afterwards.

SET NAMES utf8mb4;

START TRANSACTION;

-- Office address (footer and Contact page)
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('address', '{"en": "Master Para, Gabtali, Bogura", "bn": "মাস্টার পাড়া, গাবতলী, বগুড়া"}', NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL'
        OR (COALESCE(JSON_UNQUOTE(JSON_EXTRACT(`value`, '$.en')), 'null') IN ('', 'null') AND COALESCE(JSON_UNQUOTE(JSON_EXTRACT(`value`, '$.bn')), 'null') IN ('', 'null'))
        OR JSON_UNQUOTE(JSON_EXTRACT(`value`, '$.en')) LIKE '%(sample address)%', VALUES(`value`), `value`), `updated_at` = NOW();

-- Donation payment details
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_bank_name', '"Rupali Bank"', NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_account_name', NULL, NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_account_number', '"4036010011175"', NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_branch', '"Gabtoli Branch, Bogra"', NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_routing_number', NULL, NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_bkash', '"01922232794"', NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES ('donation_nagad', NULL, NOW(), NOW())
    ON DUPLICATE KEY UPDATE `value` = IF(`value` IS NULL OR JSON_TYPE(`value`) = 'NULL' OR JSON_UNQUOTE(`value`) IN ('', '01700-000000 (sample)', '01800-000000 (sample)', 'Sample Bank PLC', 'Tomader Jonno', '0000-0000-0000-0 (sample)', 'Mirpur Branch, Dhaka', '000000000'), VALUES(`value`), `value`), `updated_at` = NOW();

COMMIT;
