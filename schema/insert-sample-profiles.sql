-- insert Bob
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `age`, 
                                    `sex`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`)

                            VALUES ('bob',
                                    'getaroommate',
                                    'bob@supercorp.it',
                                    '50',
                                    'άνδρας',
                                    '2101234567',
                                    '1', -- smoker
                                    '1', -- pet
                                    '0', -- child
                                    '0', -- couple
                                    '2', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW()); -- These are normally handled by cakephp
 

-- insert Sally
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `age`, 
                                    `sex`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`)

                            VALUES ('sally',
                                    'hasahouse',
                                    'sally@tiredof.me',
                                    '33',
                                    'γυναίκα',
                                    '2101234567',
                                    '0', -- smoker
                                    '0', -- pet
                                    '0', -- child
                                    '1', -- couple
                                    '1', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW()); -- These are normally handled by cakephp

-- insert david
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `age`, 
                                    `sex`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`)

                            VALUES ('david',
                                    'isbroke',
                                    'david@on.it',
                                    '25',
                                    'άνδρας',
                                    '2101234567',
                                    '1', -- smoker
                                    '0', -- pet
                                    '1', -- child
                                    '1', -- couple
                                    '1', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW()); -- These are handled by cakephp

-- insert Clair
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `age`, 
                                    `sex`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`)

                            VALUES ('clair',
                                    'issingle',
                                    'clair@isnot.me',
                                    '22',
                                    'γυναίκα',
                                    '2101234567',
                                    '0', -- smoker
                                    '1', -- pet
                                    '0', -- child
                                    '0', -- couple
                                    '4', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW()); -- These are normally handled by cakephp


-- insert spock
INSERT INTO `roommates`.`profiles` (`firstname`,
                                    `lastname`, 
                                    `email`, 
                                    `age`, 
                                    `sex`,
                                    `phone`,
                                    `smoker`,
                                    `pet`, 
                                    `child`, 
                                    `couple`, 
                                    `max_roommates`,
                                    `visible`,
                                    `created`,
                                    `modified`)

                            VALUES ('spock',
                                    'isvulcan',
                                    'spock@uss.e',
                                    '35',
                                    'άνδρας',
                                    '2101234567',
                                    '0', -- smoker
                                    '0', -- pet
                                    '0', -- child
                                    '0', -- couple
                                    '1', -- max roommate#
                                    '1', -- visible
                                    NOW(),  --
                                    NOW()); -- These are handled by cakephp
