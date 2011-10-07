INSERT INTO `roommates`.`preferences` ( `age_min`,
                                        `age_max`,
                                        `mates_min`,
                                        `pref_gender`,
                                        `pref_smoker`,
                                        `pref_pet`,
                                        `pref_child`,
                                        `pref_couple`)

                                VALUES (18,
                                        35,
                                        2,
                                        0, -- male
                                        2,
                                        1,
                                        0,
                                        0);

-- insert Bob
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `dob`, 
                                    `gender`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`,
                                    `preference_id`,
                                    `user_id`)

                            VALUES ('bob',
                                    'getaroommate',
                                    'bob@supercorp.it',
                                    1984,
                                    '0',
                                    '2101234567',
                                    '1', -- smoker
                                    '1', -- pet
                                    '0', -- child
                                    '0', -- couple
                                    '2', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW(),
                                    LAST_INSERT_ID(),
                                    1); -- These are normally handled by cakephp
 


INSERT INTO `roommates`.`preferences` ( `age_min`,
                                        `age_max`,
                                        `mates_min`,
                                        `pref_gender`,
                                        `pref_smoker`,
                                        `pref_pet`,
                                        `pref_child`,
                                        `pref_couple`)

                                VALUES (18,
                                        35,
                                        1,
                                        1, -- female
                                        0,
                                        2,
                                        0,
                                        0);

-- insert Sally
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `dob`, 
                                    `gender`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`,
                                    `preference_id`,
                                    `user_id`)

                            VALUES ('sally',
                                    'hasahouse',
                                    'sally@tiredof.me',
                                    1990,
                                    '1',
                                    '2101234567',
                                    '0', -- smoker
                                    '0', -- pet
                                    '0', -- child
                                    '1', -- couple
                                    '1', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW(),
                                    LAST_INSERT_ID(),
                                    1); -- These are normally handled by cakephp


INSERT INTO `roommates`.`preferences` ( `age_min`,
                                        `age_max`,
                                        `mates_min`,
                                        `pref_gender`,
                                        `pref_smoker`,
                                        `pref_pet`,
                                        `pref_child`,
                                        `pref_couple`)

                                VALUES (18,
                                        35,
                                        2,
                                        0, -- male
                                        2,
                                        2,
                                        0,
                                        0);
-- insert david
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `dob`, 
                                    `gender`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`,
                                    `preference_id`,
                                    `user_id`)

                            VALUES ('david',
                                    'isbroke',
                                    'david@on.it',
                                    1986,
                                    '0',
                                    '2101234567',
                                    '1', -- smoker
                                    '0', -- pet
                                    '1', -- child
                                    '1', -- couple
                                    '1', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW(), 
                                    LAST_INSERT_ID(), -- These are normally handled by cakephp
                                    1); -- These are normally handled by cakephp

INSERT INTO `roommates`.`preferences` ( `age_min`,
                                        `age_max`,
                                        `mates_min`,
                                        `pref_gender`,
                                        `pref_smoker`,
                                        `pref_pet`,
                                        `pref_child`,
                                        `pref_couple`)

                                VALUES (18,
                                        35,
                                        2,
                                        1, -- male
                                        1,
                                        2,
                                        2,
                                        2);

-- insert Clair
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `dob`, 
                                    `gender`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`,
                                    `preference_id`,
                                    `user_id`)

                            VALUES ('clair',
                                    'issingle',
                                    'clair@isnot.me',
                                    1987,
                                    '1',
                                    '2101234567',
                                    '0', -- smoker
                                    '1', -- pet
                                    '0', -- child
                                    '0', -- couple
                                    '4', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW(),
                                    LAST_INSERT_ID(),
                                    1);


INSERT INTO `roommates`.`preferences` ( `age_min`,
                                        `age_max`,
                                        `mates_min`,
                                        `pref_gender`,
                                        `pref_smoker`,
                                        `pref_pet`,
                                        `pref_child`,
                                        `pref_couple`)

                                VALUES (18,
                                        35,
                                        3,
                                        2, -- male
                                        2,
                                        2,
                                        2,
                                        2);
-- insert spock
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `dob`, 
                                    `gender`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`,
                                    `preference_id`,
                                    `user_id`)

                            VALUES ('spock',
                                    'isvulcan',
                                    'spock@uss.e',
                                    1979,
                                    '0',
                                    '2101234567',
                                    '0', -- smoker
                                    '0', -- pet
                                    '0', -- child
                                    '0', -- couple
                                    '1', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW(), -- These are handled by cakephp
                                    LAST_INSERT_ID(),
                                    1);

