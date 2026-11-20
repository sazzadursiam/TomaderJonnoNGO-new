-- Management Team update for production (MySQL).
--
-- Removes unnamed / "(sample)" placeholder members, then inserts the five real members.
-- Safe to run more than once: the five members are deleted by English name before being inserted again.
--
-- Photos: upload storage/app/public/photos/management-team/*.jpg to the same folder on the server
-- (and make sure `php artisan storage:link` has been run there).

SET NAMES utf8mb4;

START TRANSACTION;

DELETE FROM `team_members`
WHERE JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')) IS NULL
   OR JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')) IN ('', 'null')
   OR JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')) LIKE '%(sample)'
   OR JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.en')) IN (
       'Md. Forid Ahmed',
       'Md. Hamidul Haque',
       'Barrister H M Sanzid Siddiqi',
       'Khandoker Fuyad Oman',
       'Alve Shabab Bin Sharif'
   );

INSERT INTO `team_members` (`name`, `role`, `bio`, `photo`, `sort_order`, `is_published`, `created_at`, `updated_at`) VALUES
('{"en": "Md. Forid Ahmed", "bn": "মো. ফরিদ আহমেদ"}', '{"en": "President", "bn": "সভাপতি"}', '{"en": null, "bn": null}', 'photos/management-team/md-forid-ahmed.jpg', 1, 1, NOW(), NOW()),
('{"en": "Md. Hamidul Haque", "bn": "মো. হামিদুল হক"}', '{"en": "Secretary General", "bn": "সাধারণ সম্পাদক"}', '{"en": null, "bn": null}', 'photos/management-team/md-hamidul-haque.jpg', 2, 1, NOW(), NOW()),
('{"en": "Barrister H M Sanzid Siddiqi", "bn": "ব্যারিস্টার এইচ এম সানজিদ সিদ্দিকী"}', '{"en": "Executive Member", "bn": "নির্বাহী সদস্য"}', '{"en": null, "bn": null}', 'photos/management-team/barrister-h-m-sanzid-siddiqi.jpg', 3, 1, NOW(), NOW()),
('{"en": "Khandoker Fuyad Oman", "bn": "খন্দকার ফুয়াদ ওমান"}', '{"en": "Member", "bn": "সদস্য"}', '{"en": null, "bn": null}', 'photos/management-team/khandoker-fuyad-oman.jpg', 4, 1, NOW(), NOW()),
('{"en": "Alve Shabab Bin Sharif", "bn": "আলভে শাবাব বিন শরীফ"}', '{"en": "Member", "bn": "সদস্য"}', '{"en": null, "bn": null}', 'photos/management-team/alve-shabab-bin-sharif.jpg', 5, 1, NOW(), NOW());

COMMIT;
