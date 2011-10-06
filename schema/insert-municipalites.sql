LOAD DATA LOCAL INFILE "municipalities_ath_gr.csv"
INTO TABLE `roommates`.`municipalities`
CHARACTER SET 'UTF8'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
(name);
