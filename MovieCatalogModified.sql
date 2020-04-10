CREATE TABLE Roles (
    roleId              INT UNSIGNED            NOT NULL AUTO_INCREMENT,
    roleName            VARCHAR(200)            NOT NULL,
    PRIMARY KEY (roleId)
);

INSERT INTO Roles(roleName) VALUES ('Admin'), ('User'), ('Moderator');
ALTER TABLE Users ADD COLUMN roleId INT NOT NULL DEFAULT 2;
ALTER TABLE Users ADD FOREIGN KEY (roleId) REFERENCES Roles(roleId);

CREATE TABLE Status (
    statusId            INT UNSIGNED            NOT NULL    AUTO_INCREMENT,
    statusDescription   VARCHAR(200)            NOT NULL,
    PRIMARY KEY     (statusId)
);

INSERT INTO Status(statusDescription) VALUES ('Submitted'), ('Cancelled'), ('Approved'), ('Saved');
ALTER TABLE Requests ADD COLUMN statusId    INT NOT NULL    DEFAULT 1;
ALTER TABLE Requests ADD FOREIGN KEY (statusId) REFERENCES Status(statusId);

UPDATE Requests SET statusId = 3
WHERE requestId IN (SELECT DISTINCT requestId FROM Movies);
