CREATE TABLE for_user_table(
    userid INT UNSIGENED NOT NULL,
    count TINYINT UNSIGENED,
    grades MEDIUMINT UNSIGENED,
    last_login_day TINYINT CHECK(last_login_day =< 31 and last_login_day >= 1),
    PRIMARY KEY(userid)
);
INSERT INTO for_user_table (userid) VALUES (0000)