ALTER TABLE Requests MODIFY description TEXT;

INSERT INTO Users (userName, password, displayName, roleId) VALUES ('admin', '0000', 'Admin', 1);
