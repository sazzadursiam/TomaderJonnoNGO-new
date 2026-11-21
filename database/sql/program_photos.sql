-- Program photos update for production (MySQL).
--
-- Appends the new photos to the Education and Rehabilitation galleries and to the Women's Empowerment
-- wings. Existing photos are kept; each photo is only added if it is not already there, so the file is
-- safe to run more than once.
--
-- Photos: upload these folders from storage/app/public/photos/ to the same place on the server:
--   education/8.jpg … 15.jpg
--   rehabilitation-support/4.jpg … 6.jpg
--   womens-empowerment/30.jpg … 45.jpg

SET NAMES utf8mb4;

START TRANSACTION;

-- Education gallery
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/8.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/8.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/9.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/9.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/10.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/10.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/11.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/11.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/12.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/12.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/13.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/13.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/14.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/14.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/education/15.jpg') WHERE `slug` = 'education' AND NOT JSON_CONTAINS(`gallery`, '"photos/education/15.jpg"');

-- Rehabilitation & Support gallery
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/rehabilitation-support/4.jpg') WHERE `slug` = 'rehabilitation' AND NOT JSON_CONTAINS(`gallery`, '"photos/rehabilitation-support/4.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/rehabilitation-support/5.jpg') WHERE `slug` = 'rehabilitation' AND NOT JSON_CONTAINS(`gallery`, '"photos/rehabilitation-support/5.jpg"');
UPDATE `programs` SET `gallery` = JSON_ARRAY_APPEND(`gallery`, '$', 'photos/rehabilitation-support/6.jpg') WHERE `slug` = 'rehabilitation' AND NOT JSON_CONTAINS(`gallery`, '"photos/rehabilitation-support/6.jpg"');

-- Women's Empowerment: Textiles & Nakshi Kantha
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[0].images', 'photos/womens-empowerment/37.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/37.jpg"', '$[0].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[0].images', 'photos/womens-empowerment/38.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/38.jpg"', '$[0].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[0].images', 'photos/womens-empowerment/39.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/39.jpg"', '$[0].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[0].images', 'photos/womens-empowerment/40.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/40.jpg"', '$[0].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[0].images', 'photos/womens-empowerment/42.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/42.jpg"', '$[0].images');

-- Women's Empowerment: Artisan Crafts & Basketry
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/33.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/33.jpg"', '$[1].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/34.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/34.jpg"', '$[1].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/35.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/35.jpg"', '$[1].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/41.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/41.jpg"', '$[1].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/43.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/43.jpg"', '$[1].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/44.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/44.jpg"', '$[1].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[1].images', 'photos/womens-empowerment/45.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/45.jpg"', '$[1].images');

-- Women's Empowerment: Grassroots Mobilization
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[3].images', 'photos/womens-empowerment/30.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/30.jpg"', '$[3].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[3].images', 'photos/womens-empowerment/31.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/31.jpg"', '$[3].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[3].images', 'photos/womens-empowerment/32.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/32.jpg"', '$[3].images');
UPDATE `programs` SET `wings` = JSON_ARRAY_APPEND(`wings`, '$[3].images', 'photos/womens-empowerment/36.jpg') WHERE `slug` = 'womens-empowerment' AND NOT JSON_CONTAINS(`wings`, '"photos/womens-empowerment/36.jpg"', '$[3].images');

COMMIT;
