CREATE TABLE userinfo_table{
    editid INT UNSIGENED NOT NULL AUTO_INCREMENT,
    userid INT UNSIGENED NOT NULL,
    id INT UNSIGNED NOT NULL,
    chapter TINYINT UNSIGNED,
    section TINYINT UNSIGNED,
    term VARCHAR(500),
    explanation VARCHAR(5000),
    PRIMARY KEY(editid),
    FOREIGN KEY(userid),
    FOREIGN KEY(id)
}