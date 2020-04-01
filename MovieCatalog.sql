-- ﻿DROP DATABASE IF EXISTS MovieCatalog;
-- GO
--
-- CREATE DATABASE MovieCatalog;
-- GO
--
-- USE MovieCatalog;
-- GO

CREATE TABLE Users(
	userId					INT UNSIGNED        NOT NULL    AUTO_INCREMENT,
	userName				VARCHAR(50)		    NOT NULL,
	password				VARCHAR(100)	    NOT NULL,
	displayName			VARCHAR(200),
	isDeleted				TINYINT(1)				NOT NULL	DEFAULT 0,
	PRIMARY KEY     (userId)
);

CREATE TABLE Genres(
	genreId					INT UNSIGNED 		NOT NULL	   AUTO_INCREMENT,
  description	    VARCHAR(200)	        NOT NULL,
	isDeleted				TINYINT(1)				NOT NULL	DEFAULT 0,
	PRIMARY KEY     (genreId)
);

CREATE TABLE Requests(
	requestId					INT UNSIGNED		NOT NULL	AUTO_INCREMENT,
	userId						INT UNSIGNED		NOT NULL,
	requestDate				DATETIME		NOT NULL	DEFAULT CURRENT_TIMESTAMP,
	requestName				VARCHAR(100),
	description		    VARCHAR(300),
	PRIMARY KEY       (requestId),
	FOREIGN KEY       (userId) REFERENCES Users(userId)
);

CREATE TABLE Actors(
	actorId					INT	UNSIGNED			NOT NULL	AUTO_INCREMENT,
	requestId				INT	UNSIGNED			NOT NULL,
	actorName				VARCHAR(100)	NOT NULL,
	actorLink				VARCHAR(2000),
	isDeleted				TINYINT(1)				NOT NULL	DEFAULT 0,
	PRIMARY KEY     (actorId),
	FOREIGN KEY     (requestId) REFERENCES Requests(requestId)
);

CREATE TABLE Movies(
	movieId					INT	UNSIGNED			NOT NULL	AUTO_INCREMENT,
	requestId				INT	UNSIGNED			NOT NULL,
	title						VARCHAR(200)	NOT NULL,
	description			VARCHAR(3000),
	keywords				VARCHAR(2000),
	imdbLink				VARCHAR(3000),
	image						VARBINARY(500),
	imageAddress		VARCHAR(3000),
	rating					FLOAT,
	isDeleted				TINYINT(1)				NOT NULL	DEFAULT 0,
	PRIMARY KEY     (movieId),
	FOREIGN KEY     (requestId) REFERENCES Requests(requestId)
);

CREATE TABLE Comments(
	commentId				INT	UNSIGNED			NOT NULL	AUTO_INCREMENT,
	userId					INT	UNSIGNED			NOT NULL,
	movieId					INT	UNSIGNED			NOT NULL,
	commentText			VARCHAR(3000)	        NOT NULL    DEFAULT '',
	PRIMARY KEY     (commentId),
	FOREIGN KEY     (userId)      REFERENCES      Users(userId),
	FOREIGN KEY     (movieId)     REFERENCES	  Movies(movieId)
);

CREATE TABLE ActorMovie(
	actorMovieId		INT	UNSIGNED			NOT NULL	AUTO_INCREMENT,
	actorId					INT	UNSIGNED			NOT NULL,
	movieId					INT	UNSIGNED			NOT NULL,
	PRIMARY KEY         (actorMovieId),
	FOREIGN KEY         (actorId)    REFERENCES	Actors(actorId),
	FOREIGN KEY         (movieId)    REFERENCES	Movies(movieId)
);

CREATE TABLE GenreMovie(
	genreMovieId		INT	UNSIGNED			NOT NULL	AUTO_INCREMENT,
	genreId					INT	UNSIGNED			NOT NULL,
	movieId					INT	UNSIGNED			NOT NULL,
	PRIMARY KEY         (genreMovieId),
	FOREIGN KEY         (genreId)         REFERENCES	Genres(genreId),
  FOREIGN KEY         (movieId)         REFERENCES	Movies(movieId)
);
