-- auto-generated definition
CREATE TABLE task
(
  id   INT AUTO_INCREMENT
    PRIMARY KEY,
  task TEXT                    NOT NULL,
  hash VARCHAR(128) DEFAULT '' NOT NULL
)
  ENGINE = InnoDB;
